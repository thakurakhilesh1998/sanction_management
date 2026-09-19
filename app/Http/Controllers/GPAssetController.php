<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Gp_List;
use App\Models\PriAsset;
use App\Models\PriAssetSubmission;


class GPAssetController extends Controller
{
    /**
     * Show the Add Asset page.
     */
    public function addAsset()
    {
       if ($this->isGpFrozen()) {
       return redirect()
        ->route('gp.assets.index')
        ->withErrors([
            'error' => 'Building information has been finally submitted and is now frozen. New buildings cannot be added.'
        ]);
}
        return view('GP.Asset.add-asset');
    }


   /**
 * Store a new PRI Asset / Building.
 */
public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Check GP Freeze Status
    |--------------------------------------------------------------------------
    */

    if ($this->isGpFrozen()) {

        return redirect()
            ->route('gp.assets.index')
            ->withErrors([
                'error' =>
                    'Building information has been finally submitted and is now frozen. New buildings cannot be added.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    $validator = Validator::make($request->all(), [

        'asset_category' => [
            'required',
            'in:official,community,commercial'
        ],

        'asset_sub_category' => [
            'required',
            'string',
            'max:100'
        ],

        'building_name' => [
            'required',
            'string',
            'max:255'
        ],

        /*
        |--------------------------------------------------------------------------
        | Ownership
        |--------------------------------------------------------------------------
        |
        | Ownership is NOT required at this stage because:
        |
        | Panchayat Ghar:
        | - Owned by GP -> automatically gram_panchayat
        | - Taken on Rent -> NULL
        |
        | Other buildings:
        | - Ownership will be checked separately below.
        |
        */

        'ownership' => [
            'nullable',
            'in:gram_panchayat,panchayat_samiti,zila_parishad'
        ],

        'panchayat_office_status' => [
            'nullable',
            'in:owned_by_gram_panchayat,taken_on_rent'
        ],

        'latitude' => [
            'required',
            'numeric',
            'between:-90,90'
        ],

        'longitude' => [
            'required',
            'numeric',
            'between:-180,180'
        ],

        'location_accuracy' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'location_captured_at' => [
            'nullable',
            'date'
        ],

        'asset_image' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png',
            'max:1024'
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Validation Failure
    |--------------------------------------------------------------------------
    */

    if ($validator->fails()) {

        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Get Logged-in GP Details
    |--------------------------------------------------------------------------
    */

    $user = Auth::user();

    if (!$user) {

        return redirect()
            ->back()
            ->withErrors([
                'error' => 'User session could not be identified.'
            ])
            ->withInput();

    }


    $gpName = $user->gp_name;
    $block = $user->block_name;
    $district = $user->district;


    /*
    |--------------------------------------------------------------------------
    | Find GP from gp_list
    |--------------------------------------------------------------------------
    */

    $gp = Gp_List::where('district_name', $district)
        ->where('block_name', $block)
        ->where('gp_name', $gpName)
        ->first();


    /*
    |--------------------------------------------------------------------------
    | GP Not Found
    |--------------------------------------------------------------------------
    */

    if (!$gp) {

        return redirect()
            ->back()
            ->withErrors([
                'error' =>
                    'Gram Panchayat could not be identified from your login details.'
            ])
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Panchayat Ghar / Panchayat Office Validation
    |--------------------------------------------------------------------------
    */

    if (
        $request->asset_sub_category === 'panchayat_ghar'
        &&
        !$request->panchayat_office_status
    ) {

        return redirect()
            ->back()
            ->withErrors([
                'panchayat_office_status' =>
                    'Please select whether the Panchayat Ghar / Panchayat Office is owned by the Gram Panchayat or taken on rent.'
            ])
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Determine Ownership
    |--------------------------------------------------------------------------
    |
    | Panchayat Ghar:
    |
    | Owned by GP -> gram_panchayat
    | Taken on Rent -> NULL
    |
    | Other buildings:
    |
    | Ownership must be selected by the user.
    |
    */

    $ownership = null;


    if (
        $request->asset_sub_category === 'panchayat_ghar'
    ) {

        if (
            $request->panchayat_office_status ===
            'owned_by_gram_panchayat'
        ) {

            $ownership = 'gram_panchayat';

        }
        elseif (
            $request->panchayat_office_status ===
            'taken_on_rent'
        ) {

            $ownership = null;

        }

    }
    else {

        /*
        |--------------------------------------------------------------------------
        | Ownership Required for Other Buildings
        |--------------------------------------------------------------------------
        */

        if (!$request->ownership) {

            return redirect()
                ->back()
                ->withErrors([
                    'ownership' =>
                        'Please select the ownership of the building.'
                ])
                ->withInput();

        }

        $ownership =
            $request->ownership;

    }


    /*
    |--------------------------------------------------------------------------
    | Panchayat Office Status
    |--------------------------------------------------------------------------
    */

    $panchayatOfficeStatus = null;

    if (
        $request->asset_sub_category === 'panchayat_ghar'
    ) {

        $panchayatOfficeStatus =
            $request->panchayat_office_status;

    }


    /*
    |--------------------------------------------------------------------------
    | Upload Photograph
    |--------------------------------------------------------------------------
    */

    $imagePath = null;

    if ($request->hasFile('asset_image')) {

        $imagePath = $request
            ->file('asset_image')
            ->store(
                'pri_assets',
                'public'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Save Asset
    |--------------------------------------------------------------------------
    */

    $asset = new PriAsset();

    $asset->gp_id =
        $gp->id;

    $asset->asset_category =
        $request->asset_category;

    $asset->asset_sub_category =
        $request->asset_sub_category;

    $asset->building_name =
        $request->building_name;

    /*
    | Save calculated ownership
    */
    $asset->ownership =
        $ownership;

    $asset->panchayat_office_status =
        $panchayatOfficeStatus;

    $asset->latitude =
        $request->latitude;

    $asset->longitude =
        $request->longitude;

    $asset->location_accuracy =
        $request->location_accuracy;

    $asset->location_captured_at =
        $request->location_captured_at;

    $asset->asset_image =
        $imagePath;

    $asset->is_active = true;


    /*
    |--------------------------------------------------------------------------
    | Database Save
    |--------------------------------------------------------------------------
    */

    $asset->save();


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->back()
        ->with(
            'message',
            'Building / Asset has been saved successfully.'
        );
}



    /**
     * Display all assets/buildings entered by the logged-in Gram Panchayat.
     */
    public function viewAssets()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in GP Details
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->back()
                ->withErrors([
                    'error' => 'User session could not be identified.'
                ]);

        }


        $gpName = $user->gp_name;
        $block = $user->block_name;
        $district = $user->district;


        /*
        |--------------------------------------------------------------------------
        | Find Logged-in Gram Panchayat
        |--------------------------------------------------------------------------
        */

        $gp = Gp_List::where('district_name', $district)
            ->where('block_name', $block)
            ->where('gp_name', $gpName)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | If GP Not Found
        |--------------------------------------------------------------------------
        */

        if (!$gp) {

            return redirect()
                ->back()
                ->withErrors([
                    'error' =>
                        'Gram Panchayat could not be identified from your login details.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Get Assets of this GP
        |--------------------------------------------------------------------------
        |
        | Soft deleted buildings are automatically excluded.
        |
        */

        $assets = PriAsset::where('gp_id', $gp->id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $submission = $this->getGpSubmission();
        $isFrozen = $submission && $submission->status === 'submitted'; 
        /*
        |--------------------------------------------------------------------------
        | Send data to View
        |--------------------------------------------------------------------------
        */

        return view(
         'GP.Asset.view-assets',
        compact('assets', 'gp', 'submission', 'isFrozen')
);
    }



    /**
     * Edit Building
     */
    public function edit($id)
    {
    
        if ($this->isGpFrozen()) {
        return redirect()
        ->route('gp.assets.index')
        ->withErrors([
            'error' => 'Building information has been finally submitted and is now frozen. Buildings cannot be edited.'
        ]);
}
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in GP ID
        |--------------------------------------------------------------------------
        */

        $gpId = $this->getLoggedInGpId();


        if (!$gpId) {

            return redirect()
                ->back()
                ->withErrors([
                    'error' =>
                        'Gram Panchayat could not be identified from your login details.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Get Building
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Only the building belonging to the logged-in GP can be edited.
        |
        */

        $asset = PriAsset::where('id', $id)
            ->where('gp_id', $gpId)
            ->where('is_active', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Open Edit Page
        |--------------------------------------------------------------------------
        */

        return view(
            'GP.Asset.edit-asset',
            compact('asset')
        );
    }



    /**
     * Update Building
     */
   /**
 * Update Building
 */
public function update(Request $request, $id)
{
    /*
    |--------------------------------------------------------------------------
    | Check GP Freeze Status
    |--------------------------------------------------------------------------
    */

    if ($this->isGpFrozen()) {

        return redirect()
            ->route('gp.assets.index')
            ->withErrors([
                'error' =>
                    'Building information has been finally submitted and is now frozen. Buildings cannot be edited.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Logged-in GP ID
    |--------------------------------------------------------------------------
    */

    $gpId = $this->getLoggedInGpId();


    if (!$gpId) {

        return redirect()
            ->back()
            ->withErrors([
                'error' =>
                    'Gram Panchayat could not be identified from your login details.'
            ])
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Get Building
    |--------------------------------------------------------------------------
    |
    | Only the logged-in GP can update its own building.
    |
    */

    $asset = PriAsset::where('id', $id)
        ->where('gp_id', $gpId)
        ->where('is_active', true)
        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    $validator = Validator::make($request->all(), [

        'asset_category' => [
            'required',
            'in:official,community,commercial'
        ],

        'asset_sub_category' => [
            'required',
            'string',
            'max:100'
        ],

        'building_name' => [
            'required',
            'string',
            'max:255'
        ],

        /*
        |--------------------------------------------------------------------------
        | Ownership is conditionally handled below
        |--------------------------------------------------------------------------
        */

        'ownership' => [
            'nullable',
            'in:gram_panchayat,panchayat_samiti,zila_parishad'
        ],

        'panchayat_office_status' => [
            'nullable',
            'in:owned_by_gram_panchayat,taken_on_rent'
        ],

        'latitude' => [
            'required',
            'numeric',
            'between:-90,90'
        ],

        'longitude' => [
            'required',
            'numeric',
            'between:-180,180'
        ],

        'location_accuracy' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'location_captured_at' => [
            'nullable',
            'date'
        ],

        /*
        |--------------------------------------------------------------------------
        | Photograph is optional during editing
        |--------------------------------------------------------------------------
        */

        'asset_image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png',
            'max:1024'
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Validation Failure
    |--------------------------------------------------------------------------
    */

    if ($validator->fails()) {

        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Panchayat Ghar / Panchayat Office Validation
    |--------------------------------------------------------------------------
    */

    if (
        $request->asset_sub_category === 'panchayat_ghar'
        &&
        !$request->panchayat_office_status
    ) {

        return redirect()
            ->back()
            ->withErrors([
                'panchayat_office_status' =>
                    'Please select whether the Panchayat Ghar / Panchayat Office is owned by the Gram Panchayat or taken on rent.'
            ])
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Determine Ownership
    |--------------------------------------------------------------------------
    |
    | Panchayat Ghar:
    |
    | Owned by GP -> gram_panchayat
    | Taken on Rent -> NULL
    |
    | Other buildings:
    |
    | Ownership must be selected.
    |
    */

    $ownership = null;


    if (
        $request->asset_sub_category === 'panchayat_ghar'
    ) {

        if (
            $request->panchayat_office_status ===
            'owned_by_gram_panchayat'
        ) {

            $ownership =
                'gram_panchayat';

        }
        elseif (
            $request->panchayat_office_status ===
            'taken_on_rent'
        ) {

            $ownership =
                null;

        }

    }
    else {

        /*
        |--------------------------------------------------------------------------
        | Ownership Required for Other Buildings
        |--------------------------------------------------------------------------
        */

        if (!$request->ownership) {

            return redirect()
                ->back()
                ->withErrors([
                    'ownership' =>
                        'Please select the ownership of the building.'
                ])
                ->withInput();

        }

        $ownership =
            $request->ownership;

    }


    /*
    |--------------------------------------------------------------------------
    | Panchayat Office Status
    |--------------------------------------------------------------------------
    */

    $panchayatOfficeStatus = null;

    if (
        $request->asset_sub_category === 'panchayat_ghar'
    ) {

        $panchayatOfficeStatus =
            $request->panchayat_office_status;

    }


    /*
    |--------------------------------------------------------------------------
    | Update Building Details
    |--------------------------------------------------------------------------
    */

    $asset->asset_category =
        $request->asset_category;

    $asset->asset_sub_category =
        $request->asset_sub_category;

    $asset->building_name =
        $request->building_name;

    /*
    | Save calculated ownership
    */
    $asset->ownership =
        $ownership;

    $asset->panchayat_office_status =
        $panchayatOfficeStatus;

    $asset->latitude =
        $request->latitude;

    $asset->longitude =
        $request->longitude;

    $asset->location_accuracy =
        $request->location_accuracy;

    $asset->location_captured_at =
        $request->location_captured_at;


    /*
    |--------------------------------------------------------------------------
    | Replace Photograph
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('asset_image')) {

        /*
        |--------------------------------------------------------------------------
        | Delete old photograph
        |--------------------------------------------------------------------------
        */

        if ($asset->asset_image) {

            Storage::disk('public')
                ->delete($asset->asset_image);

        }


        /*
        |--------------------------------------------------------------------------
        | Store new photograph
        |--------------------------------------------------------------------------
        */

        $newImagePath = $request
            ->file('asset_image')
            ->store(
                'pri_assets',
                'public'
            );


        $asset->asset_image =
            $newImagePath;

    }


    /*
    |--------------------------------------------------------------------------
    | Save Updated Building
    |--------------------------------------------------------------------------
    */

    $asset->save();


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('gp.assets.index')
        ->with(
            'message',
            'Building details have been updated successfully.'
        );
}


    /**
     * Delete Building
     *
     * This performs SOFT DELETE.
     */
    public function destroy($id)
    {
        
        if ($this->isGpFrozen()) {
        return redirect()
            ->route('gp.assets.index')
            ->withErrors([
                'error' => 'Building information has been finally submitted and is now frozen. Buildings cannot be deleted.'
            ]);
    }
        $gpId = $this->getLoggedInGpId();


        if (!$gpId) {

            return redirect()
                ->back()
                ->withErrors([
                    'error' =>
                        'Gram Panchayat could not be identified from your login details.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Get Building
        |--------------------------------------------------------------------------
        |
        | Only the GP that owns the building can delete it.
        |
        */

        $asset = PriAsset::where('id', $id)
            ->where('gp_id', $gpId)
            ->where('is_active', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Soft Delete
        |--------------------------------------------------------------------------
        |
        | The record will NOT be physically deleted.
        | deleted_at will be populated.
        |
        */

        $asset->delete();


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('gp.assets.index')
            ->with(
                'message',
                'Building has been deleted successfully.'
            );
    }



    /**
     * Get the ID of the logged-in Gram Panchayat.
     *
     * This uses the same GP identification logic
     * already working in store() and viewAssets().
     */
    private function getLoggedInGpId()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        if (!$user) {

            return null;

        }


        /*
        |--------------------------------------------------------------------------
        | Get GP Details from Logged-in User
        |--------------------------------------------------------------------------
        */

        $gpName = $user->gp_name;
        $block = $user->block_name;
        $district = $user->district;


        /*
        |--------------------------------------------------------------------------
        | Find GP in gp_list
        |--------------------------------------------------------------------------
        */

        $gp = Gp_List::where('district_name', $district)
            ->where('block_name', $block)
            ->where('gp_name', $gpName)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Return GP ID
        |--------------------------------------------------------------------------
        */

        return $gp ? $gp->id : null;
    }

    private function getGpSubmission()
    {
        $gpId = $this->getLoggedInGpId();

        if (!$gpId) {
            return null;
        }

        return PriAssetSubmission::firstOrCreate(
            ['gp_id' => $gpId],
            ['status' => 'draft']
        );
    }
    private function isGpFrozen()
    {
        $submission = $this->getGpSubmission();

        return $submission && $submission->status === 'submitted';
    }
   /**
 * =========================================================================
 * FINAL SUBMIT / FREEZE BUILDING INFORMATION
 * =========================================================================
 */
public function finalSubmit(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Get Logged-in GP ID
    |--------------------------------------------------------------------------
    */

    $gpId = $this->getLoggedInGpId();


    if (!$gpId) {

        return redirect()
            ->back()
            ->withErrors([
                'error' =>
                    'Gram Panchayat could not be identified from your login details.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Submission Record
    |--------------------------------------------------------------------------
    */

    $submission = PriAssetSubmission::where(
        'gp_id',
        $gpId
    )->first();


    if (!$submission) {

        return redirect()
            ->back()
            ->withErrors([
                'error' =>
                    'Submission record could not be found.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Check Already Submitted / Frozen
    |--------------------------------------------------------------------------
    */

    if ($submission->status === 'submitted') {

        return redirect()
            ->route('gp.assets.index')
            ->with(
                'message',
                'Building information has already been finally submitted.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Certification Checkbox Validation
    |--------------------------------------------------------------------------
    */

    $request->validate(
        [
            'certification' =>
                'required|accepted',
        ],
        [
            'certification.required' =>
                'Please certify that the information is complete and correct.',

            'certification.accepted' =>
                'Please certify that the information is complete and correct before final submission.',
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Check Total Building Information
    |--------------------------------------------------------------------------
    |
    | At least one active building must be entered.
    |
    */

    $buildingCount = PriAsset::where(
        'gp_id',
        $gpId
    )
        ->where(
            'is_active',
            true
        )
        ->count();


    if ($buildingCount === 0) {

        return redirect()
            ->back()
            ->withErrors([
                'error' =>
                    'Please add the building information before final submission.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK PANCHAYAT GHAR / PANCHAYAT OFFICE
    |--------------------------------------------------------------------------
    |
    | Panchayat Ghar / Panchayat Office is mandatory.
    |
    | The GP cannot freeze its building information until
    | Panchayat Ghar / Panchayat Office has been entered.
    |
    */

    $panchayatOfficeExists = PriAsset::where(
        'gp_id',
        $gpId
    )
        ->where(
            'is_active',
            true
        )
        ->whereRaw(
            'LOWER(TRIM(asset_sub_category)) = ?',
            ['panchayat_ghar']
        )
        ->exists();


    /*
    |--------------------------------------------------------------------------
    | Panchayat Ghar Not Added
    |--------------------------------------------------------------------------
    */

    if (!$panchayatOfficeExists) {

        return redirect()
            ->back()
            ->withErrors([
                'error' =>
                    'Panchayat Ghar / Panchayat Office information is mandatory. Please add the Panchayat Ghar / Panchayat Office details before final submission.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FINAL SUBMISSION / FREEZE
    |--------------------------------------------------------------------------
    */

    $submission->status =
        'submitted';


    $submission->submitted_at =
        now();


    $submission->submitted_by =
        Auth::id();


    $submission->save();


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'gp.assets.index'
        )
        ->with(
            'message',
            'Building information has been finally submitted and frozen successfully.'
        );
}
}