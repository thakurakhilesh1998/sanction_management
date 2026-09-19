<?php

namespace App\Http\Controllers;

use App\Models\Gp_List;
use App\Models\PriAsset;
use App\Models\PriAssetSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAssetController extends Controller
{
    /**
     * Admin Building Information Dashboard
     */
    public function index(Request $request)
    {
        // Get all districts
        $districts = Gp_List::query()
            ->select('district_name')
            ->whereNotNull('district_name')
            ->where('district_name', '!=', '')
            ->distinct()
            ->orderBy('district_name')
            ->pluck('district_name');

        $selectedDistrict = $request->district;
        $selectedBlock = $request->block;

        $blocks = collect();
        $gps = collect();

        /*
        |--------------------------------------------------------------------------
        | Load Blocks after District selection
        |--------------------------------------------------------------------------
        */
        if ($selectedDistrict) {

            $blocks = Gp_List::query()
                ->select('block_name')
                ->where('district_name', $selectedDistrict)
                ->whereNotNull('block_name')
                ->where('block_name', '!=', '')
                ->distinct()
                ->orderBy('block_name')
                ->pluck('block_name');
        }

        /*
        |--------------------------------------------------------------------------
        | Load only FROZEN / SUBMITTED GPs after Block selection
        |--------------------------------------------------------------------------
        */
        if ($selectedDistrict && $selectedBlock) {

            $gps = Gp_List::query()
                ->where('district_name', $selectedDistrict)
                ->where('block_name', $selectedBlock)

                // Only GPs whose submission is frozen/submitted
                ->whereHas('priAssetSubmission', function ($query) {
                    $query->where('status', 'submitted');
                })

                ->with([
                    'priAssetSubmission',
                    'priAssets' => function ($query) {
                        $query->where('is_active', true);
                    }
                ])

                ->orderBy('gp_name')
                ->get();
        }

        return view('Admin.Building.index', compact(
            'districts',
            'blocks',
            'gps',
            'selectedDistrict',
            'selectedBlock'
        ));
    }

    /**
 * Get blocks for selected district
 */
    public function getBlocks(Request $request)
    {
        $request->validate([
            'district' => 'required|string',
        ]);

        $blocks = Gp_List::query()
            ->select('block_name')
            ->where('district_name', $request->district)
            ->whereNotNull('block_name')
            ->where('block_name', '!=', '')
            ->distinct()
            ->orderBy('block_name')
            ->pluck('block_name');

        return response()->json($blocks);
    }


    /**
     * View all building details of a frozen GP
     */
    public function viewGp($gpId)
{
    // Only allow viewing of frozen/submitted GPs
    $gp = Gp_List::where('id', $gpId)
        ->whereHas('priAssetSubmission', function ($query) {
            $query->where('status', 'submitted');
        })
        ->with([
            'priAssetSubmission',
            'priAssets' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('asset_category')
                    ->orderBy('asset_sub_category')
                    ->orderBy('building_name');
            }
        ])
        ->firstOrFail();

    return view('Admin.Building.view', compact('gp'));
}


    /**
     * Unfreeze GP building information
     */
    public function unfreeze($gpId)
{
    $gp = Gp_List::findOrFail($gpId);

    // Find only currently frozen submission
    $submission = PriAssetSubmission::where('gp_id', $gp->id)
        ->where('status', 'submitted')
        ->first();

    if (!$submission) {

        return redirect()
            ->back()
            ->withErrors([
                'error' => 'This Gram Panchayat is not currently frozen.'
            ]);
    }

    // Change status back to draft
    $submission->status = 'draft';
    $submission->submitted_at = null;
    $submission->submitted_by = null;
    $submission->save();

    return redirect()
        ->route('admin.assets.index', [
            'district' => $gp->district_name,
            'block' => $gp->block_name
        ])
        ->with(
            'message',
            'Building information of ' . $gp->gp_name .
            ' has been unfrozen successfully.'
        );
}
}