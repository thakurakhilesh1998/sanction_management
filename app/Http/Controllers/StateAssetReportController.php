<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Gp_List;
use App\Models\PriAsset;
use App\Models\PriAssetSubmission;

class StateAssetReportController extends Controller
{
    /**
     * =========================================================================
     * STATE LEVEL DASHBOARD
     * =========================================================================
     */
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL DISTRICTS
        |--------------------------------------------------------------------------
        */

        $totalDistricts = Gp_List::query()
            ->whereNotNull('district_name')
            ->where('district_name', '!=', '')
            ->pluck('district_name')
            ->map(function ($district) {
                return trim($district);
            })
            ->filter()
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL BLOCKS
        |--------------------------------------------------------------------------
        */

        $totalBlocks = Gp_List::query()
            ->select(
                'district_name',
                'block_name'
            )
            ->whereNotNull('district_name')
            ->where('district_name', '!=', '')
            ->whereNotNull('block_name')
            ->where('block_name', '!=', '')
            ->get()
            ->map(function ($row) {

                return trim($row->district_name)
                    . '|' .
                    trim($row->block_name);

            })
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL GRAM PANCHAYATS
        |--------------------------------------------------------------------------
        */

        $totalGps = Gp_List::query()
            ->whereNotNull('gp_name')
            ->where('gp_name', '!=', '')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DATA ENTRY STARTED
        |--------------------------------------------------------------------------
        |
        | A GP is considered started when at least one active building
        | has been entered.
        |
        */

        $dataStartedGps = PriAsset::query()
            ->where('is_active', true)
            ->distinct()
            ->count('gp_id');


        /*
        |--------------------------------------------------------------------------
        | DATA FROZEN
        |--------------------------------------------------------------------------
        */

        $frozenGps = PriAssetSubmission::query()
            ->where('status', 'submitted')
            ->distinct()
            ->count('gp_id');


        /*
        |--------------------------------------------------------------------------
        | DATA NOT STARTED
        |--------------------------------------------------------------------------
        */

        $notStartedGps = max(
            0,
            $totalGps - $dataStartedGps
        );


        /*
        |--------------------------------------------------------------------------
        | GET FROZEN GP IDs
        |--------------------------------------------------------------------------
        |
        | All building-related statistics below are based ONLY on these GPs.
        |
        */

        $frozenGpIds = $this->getFrozenGpIds();


        /*
        |--------------------------------------------------------------------------
        | FROZEN BUILDING QUERY
        |--------------------------------------------------------------------------
        */

        $assetQuery = PriAsset::query()
            ->where('is_active', true)
            ->whereIn('gp_id', $frozenGpIds);


        /*
        |--------------------------------------------------------------------------
        | TOTAL BUILDINGS
        |--------------------------------------------------------------------------
        */

        $totalBuildings = (clone $assetQuery)->count();


        /*
        |--------------------------------------------------------------------------
        | BUILDING CATEGORY ANALYSIS
        |--------------------------------------------------------------------------
        */

        $categoryData = (clone $assetQuery)
            ->select(
                DB::raw(
                    'LOWER(TRIM(asset_category)) as category'
                ),
                DB::raw(
                    'COUNT(*) as total'
                )
            )
            ->groupBy(
                DB::raw(
                    'LOWER(TRIM(asset_category))'
                )
            )
            ->pluck(
                'total',
                'category'
            )
            ->toArray();


        $categoryAnalysis = [

            'Official' =>
                $categoryData['official'] ?? 0,

            'Community' =>
                $categoryData['community'] ?? 0,

            'Commercial' =>
                $categoryData['commercial'] ?? 0,

        ];


        /*
        |--------------------------------------------------------------------------
        | PANCHAYAT GHAR ANALYSIS
        |--------------------------------------------------------------------------
        */

        $panchayatGharQuery = (clone $assetQuery)
            ->whereRaw(
                'LOWER(TRIM(asset_sub_category)) = ?',
                ['panchayat_ghar']
            );


        /*
        | Total GPs having Panchayat Ghar
        */

        $totalPanchayatGharGps =
            (clone $panchayatGharQuery)
                ->distinct()
                ->count('gp_id');


        /*
        | Owned by Gram Panchayat
        */

        $ownedPanchayatGharGps =
            (clone $panchayatGharQuery)
                ->whereRaw(
                    'LOWER(TRIM(panchayat_office_status)) = ?',
                    ['owned_by_gram_panchayat']
                )
                ->distinct()
                ->count('gp_id');


        /*
        | Taken on Rent
        */

        $rentedPanchayatGharGps =
            (clone $panchayatGharQuery)
                ->whereRaw(
                    'LOWER(TRIM(panchayat_office_status)) = ?',
                    ['taken_on_rent']
                )
                ->distinct()
                ->count('gp_id');


        $panchayatGharAnalysis = [

            'total' =>
                $totalPanchayatGharGps,

            'owned' =>
                $ownedPanchayatGharGps,

            'rented' =>
                $rentedPanchayatGharGps,

        ];


        /*
        |--------------------------------------------------------------------------
        | OWNERSHIP ANALYSIS
        |--------------------------------------------------------------------------
        */

        $ownershipData = (clone $assetQuery)
            ->whereNotNull('ownership')
            ->select(
                DB::raw(
                    'LOWER(TRIM(ownership)) as ownership_type'
                ),
                DB::raw(
                    'COUNT(*) as total'
                )
            )
            ->groupBy(
                DB::raw(
                    'LOWER(TRIM(ownership))'
                )
            )
            ->pluck(
                'total',
                'ownership_type'
            )
            ->toArray();


        $ownershipAnalysis = [

            'Gram Panchayat' =>
                $ownershipData['gram_panchayat'] ?? 0,

            'Panchayat Samiti' =>
                $ownershipData['panchayat_samiti'] ?? 0,

            'Zila Parishad' =>
                $ownershipData['zila_parishad'] ?? 0,

        ];


        /*
        |--------------------------------------------------------------------------
        | DISTRICT-WISE REPORT
        |--------------------------------------------------------------------------
        */

        $districtRows = Gp_List::query()
            ->select(
                'district_name',
                'block_name',
                'id'
            )
            ->whereNotNull('district_name')
            ->where('district_name', '!=', '')
            ->get();


        $districts = $districtRows
            ->groupBy(function ($item) {

                return trim(
                    $item->district_name
                );

            })
            ->map(function ($rows, $districtName) {

                /*
                |--------------------------------------------------------------------------
                | Total Blocks
                |--------------------------------------------------------------------------
                */

                $totalDistrictBlocks = $rows
                    ->pluck('block_name')
                    ->filter(function ($block) {

                        return $block !== null
                            && trim($block) !== '';

                    })
                    ->map(function ($block) {

                        return trim($block);

                    })
                    ->unique()
                    ->count();


                /*
                |--------------------------------------------------------------------------
                | GP IDs
                |--------------------------------------------------------------------------
                */

                $gpIds = $rows
                    ->pluck('id')
                    ->filter()
                    ->unique()
                    ->values();


                $totalDistrictGps =
                    $gpIds->count();


                /*
                |--------------------------------------------------------------------------
                | Data Started
                |--------------------------------------------------------------------------
                */

                $startedGps = PriAsset::query()
                    ->where('is_active', true)
                    ->whereIn(
                        'gp_id',
                        $gpIds
                    )
                    ->distinct()
                    ->count('gp_id');


                /*
                |--------------------------------------------------------------------------
                | Data Frozen
                |--------------------------------------------------------------------------
                */

                $frozenGps = PriAssetSubmission::query()
                    ->where(
                        'status',
                        'submitted'
                    )
                    ->whereIn(
                        'gp_id',
                        $gpIds
                    )
                    ->distinct()
                    ->count('gp_id');


                /*
                |--------------------------------------------------------------------------
                | Frozen Buildings
                |--------------------------------------------------------------------------
                */

                $frozenDistrictGpIds =
                    PriAssetSubmission::query()
                        ->where(
                            'status',
                            'submitted'
                        )
                        ->whereIn(
                            'gp_id',
                            $gpIds
                        )
                        ->pluck('gp_id')
                        ->unique();


                $totalDistrictBuildings =
                    PriAsset::query()
                        ->where(
                            'is_active',
                            true
                        )
                        ->whereIn(
                            'gp_id',
                            $frozenDistrictGpIds
                        )
                        ->count();


                return (object) [

                    'district_name' =>
                        $districtName,

                    'total_blocks' =>
                        $totalDistrictBlocks,

                    'total_gps' =>
                        $totalDistrictGps,

                    'data_started' =>
                        $startedGps,

                    'data_frozen' =>
                        $frozenGps,

                    'total_buildings' =>
                        $totalDistrictBuildings,

                ];

            })
            ->sortBy('district_name')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN STATE DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'Directorate.BuildingReport.dashboard',
            compact(

                'totalDistricts',

                'totalBlocks',

                'totalGps',

                'totalBuildings',

                'dataStartedGps',

                'frozenGps',

                'notStartedGps',

                'categoryAnalysis',

                'panchayatGharAnalysis',

                'ownershipAnalysis',

                'districts'

            )
        );
    }


    /**
     * =========================================================================
     * DISTRICT LEVEL REPORT
     * =========================================================================
     */
    public function district($district)
    {
        $district = trim($district);


        /*
        |--------------------------------------------------------------------------
        | GET DISTRICT GPs
        |--------------------------------------------------------------------------
        */

        $gpRows = Gp_List::query()
            ->whereRaw(
                'TRIM(district_name) = ?',
                [$district]
            )
            ->get();


        if ($gpRows->isEmpty()) {

            return redirect()
                ->route(
                    'state.asset-report.dashboard'
                )
                ->withErrors([
                    'error' =>
                        'District information not found.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | GP IDs
        |--------------------------------------------------------------------------
        */

        $gpIds = $gpRows
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | COMMON ANALYSIS
        |--------------------------------------------------------------------------
        */

        $analysis =
            $this->getReportAnalysis(
                $gpIds
            );


        /*
        |--------------------------------------------------------------------------
        | BLOCK-WISE REPORT
        |--------------------------------------------------------------------------
        */

        $blocks = $gpRows
            ->groupBy(function ($row) {

                return trim(
                    $row->block_name
                );

            })
            ->map(function ($rows, $blockName) {

                $blockGpIds = $rows
                    ->pluck('id')
                    ->filter()
                    ->unique()
                    ->values();


                $blockAnalysis =
                    $this->getReportAnalysis(
                        $blockGpIds
                    );


                /*
                |--------------------------------------------------------------------------
                | Block Progress
                |--------------------------------------------------------------------------
                */

                $blockProgress =
                    $blockAnalysis['totalGps'] > 0
                        ? round(
                            (
                                $blockAnalysis['dataStartedGps']
                                /
                                $blockAnalysis['totalGps']
                            ) * 100,
                            1
                        )
                        : 0;


                return (object) [

                    'block_name' =>
                        $blockName,

                    'total_gps' =>
                        $blockAnalysis['totalGps'],

                    'total_buildings' =>
                        $blockAnalysis['totalBuildings'],

                    'data_started' =>
                        $blockAnalysis['dataStartedGps'],

                    'data_frozen' =>
                        $blockAnalysis['frozenGps'],

                    'not_started' =>
                        $blockAnalysis['notStartedGps'],

                    'progress' =>
                        $blockProgress,

                ];

            })
            ->filter(function ($block) {

                return $block->block_name !== '';

            })
            ->sortBy('block_name')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Total Blocks
        |--------------------------------------------------------------------------
        */

        $totalBlocks =
            $blocks->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN DISTRICT VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'Directorate.BuildingReport.district',

            array_merge(

                [

                    'district' =>
                        $district,

                    'totalBlocks' =>
                        $totalBlocks,

                    'blocks' =>
                        $blocks,

                ],

                $analysis

            )
        );
    }


    /**
     * =========================================================================
     * BLOCK LEVEL REPORT
     * =========================================================================
     */
    public function block($district, $block)
    {
        $district = trim($district);

        $block = trim($block);


        /*
        |--------------------------------------------------------------------------
        | GET GPs
        |--------------------------------------------------------------------------
        */

        $gpRows = Gp_List::query()
            ->whereRaw(
                'TRIM(district_name) = ?',
                [$district]
            )
            ->whereRaw(
                'TRIM(block_name) = ?',
                [$block]
            )
            ->get();


        if ($gpRows->isEmpty()) {

            return redirect()
                ->route(
                    'state.asset-report.district',
                    [
                        'district' =>
                            $district
                    ]
                )
                ->withErrors([
                    'error' =>
                        'Block information not found.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | GP IDs
        |--------------------------------------------------------------------------
        */

        $gpIds = $gpRows
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | COMMON ANALYSIS
        |--------------------------------------------------------------------------
        */

        $analysis =
            $this->getReportAnalysis(
                $gpIds
            );


        /*
        |--------------------------------------------------------------------------
        | GP-WISE TABLE
        |--------------------------------------------------------------------------
        */

        $gps = $gpRows
            ->groupBy('id')
            ->map(function ($rows) {

                $gp =
                    $rows->first();


                $gpId =
                    $gp->id;


                /*
                |--------------------------------------------------------------------------
                | Check Frozen
                |--------------------------------------------------------------------------
                */

                $isFrozen =
                    PriAssetSubmission::query()
                        ->where(
                            'gp_id',
                            $gpId
                        )
                        ->where(
                            'status',
                            'submitted'
                        )
                        ->exists();


                /*
                |--------------------------------------------------------------------------
                | Building Count
                |--------------------------------------------------------------------------
                |
                | Only frozen GP buildings are counted.
                |
                */

                $buildingCount = 0;


                if ($isFrozen) {

                    $buildingCount =
                        PriAsset::query()
                            ->where(
                                'gp_id',
                                $gpId
                            )
                            ->where(
                                'is_active',
                                true
                            )
                            ->count();
                }


                /*
                |--------------------------------------------------------------------------
                | Data Entry Started
                |--------------------------------------------------------------------------
                */

                $isStarted =
                    PriAsset::query()
                        ->where(
                            'gp_id',
                            $gpId
                        )
                        ->where(
                            'is_active',
                            true
                        )
                        ->exists();


                return (object) [

                    'gp_id' =>
                        $gpId,

                    'gp_name' =>
                        $gp->gp_name,

                    'total_buildings' =>
                        $buildingCount,

                    'is_started' =>
                        $isStarted,

                    'is_frozen' =>
                        $isFrozen,

                ];

            })
            ->sortBy('gp_name')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN BLOCK VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'Directorate.BuildingReport.block',

            array_merge(

                [

                    'district' =>
                        $district,

                    'block' =>
                        $block,

                    'gps' =>
                        $gps,

                ],

                $analysis

            )
        );
    }


    /**
     * =========================================================================
     * GP LEVEL REPORT
     * =========================================================================
     */
    public function gp(
        $district,
        $block,
        $gp
    ) {
        $district = trim(
            $district
        );

        $block = trim(
            $block
        );


        /*
        |--------------------------------------------------------------------------
        | GET GP
        |--------------------------------------------------------------------------
        */

        $gpData = Gp_List::query()
            ->where(
                'id',
                $gp
            )
            ->whereRaw(
                'TRIM(district_name) = ?',
                [$district]
            )
            ->whereRaw(
                'TRIM(block_name) = ?',
                [$block]
            )
            ->first();


        if (!$gpData) {

            return redirect()
                ->route(
                    'state.asset-report.block',
                    [
                        'district' =>
                            $district,

                        'block' =>
                            $block
                    ]
                )
                ->withErrors([
                    'error' =>
                        'Gram Panchayat information not found.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SUBMISSION STATUS
        |--------------------------------------------------------------------------
        */

        $submission =
            PriAssetSubmission::query()
                ->where(
                    'gp_id',
                    $gpData->id
                )
                ->first();


        $isFrozen =
            $submission &&
            $submission->status === 'submitted';


        /*
        |--------------------------------------------------------------------------
        | GET BUILDINGS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Only frozen GP buildings are shown.
        |
        */

        if ($isFrozen) {

            $buildings =
                PriAsset::query()
                    ->where(
                        'gp_id',
                        $gpData->id
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->orderBy(
                        'asset_category'
                    )
                    ->orderBy(
                        'asset_sub_category'
                    )
                    ->orderBy(
                        'building_name'
                    )
                    ->get();

        } else {

            $buildings =
                collect();

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL BUILDINGS
        |--------------------------------------------------------------------------
        */

        $totalBuildings =
            $buildings->count();


        /*
        |--------------------------------------------------------------------------
        | CATEGORY-WISE ANALYSIS
        |--------------------------------------------------------------------------
        */

        $officialBuildings =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->asset_category
                        )
                    ) === 'official';

                })
                ->count();


        $communityBuildings =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->asset_category
                        )
                    ) === 'community';

                })
                ->count();


        $commercialBuildings =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->asset_category
                        )
                    ) === 'commercial';

                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | PANCHAYAT GHAR ANALYSIS
        |--------------------------------------------------------------------------
        */

        $panchayatGhar =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->asset_sub_category
                        )
                    ) === 'panchayat_ghar';

                });


        $panchayatGharCount =
            $panchayatGhar->count();


        $panchayatGharOwned =
            $panchayatGhar
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->panchayat_office_status
                        )
                    ) ===
                        'owned_by_gram_panchayat';

                })
                ->count();


        $panchayatGharRented =
            $panchayatGhar
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->panchayat_office_status
                        )
                    ) ===
                        'taken_on_rent';

                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | OWNERSHIP ANALYSIS
        |--------------------------------------------------------------------------
        */

        $gramPanchayatBuildings =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->ownership
                        )
                    ) === 'gram_panchayat';

                })
                ->count();


        $panchayatSamitiBuildings =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->ownership
                        )
                    ) === 'panchayat_samiti';

                })
                ->count();


        $zilaParishadBuildings =
            $buildings
                ->filter(function ($building) {

                    return strtolower(
                        trim(
                            (string)
                            $building->ownership
                        )
                    ) === 'zila_parishad';

                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN GP VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'Directorate.BuildingReport.gp',

            compact(

                'district',

                'block',

                'gpData',

                'buildings',

                'totalBuildings',

                'officialBuildings',

                'communityBuildings',

                'commercialBuildings',

                'panchayatGharCount',

                'panchayatGharOwned',

                'panchayatGharRented',

                'gramPanchayatBuildings',

                'panchayatSamitiBuildings',

                'zilaParishadBuildings',

                'submission',

                'isFrozen'

            )
        );
    }


    /**
     * =========================================================================
     * COMMON REPORT ANALYSIS
     * =========================================================================
     *
     * Used by:
     *
     * 1. District
     * 2. Block
     *
     * All BUILDING statistics are based ONLY on frozen GPs.
     *
     * GP progress statistics are based on ALL GPs.
     *
     */
    private function getReportAnalysis($gpIds)
    {
        /*
        |--------------------------------------------------------------------------
        | Clean GP IDs
        |--------------------------------------------------------------------------
        */

        $gpIds = collect($gpIds)
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Total GPs
        |--------------------------------------------------------------------------
        */

        $totalGps =
            $gpIds->count();


        /*
        |--------------------------------------------------------------------------
        | Frozen GP IDs
        |--------------------------------------------------------------------------
        */

        $frozenGpIds =
            PriAssetSubmission::query()
                ->whereIn(
                    'gp_id',
                    $gpIds
                )
                ->where(
                    'status',
                    'submitted'
                )
                ->pluck('gp_id')
                ->unique()
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Frozen Building Query
        |--------------------------------------------------------------------------
        */

        $assetQuery =
            PriAsset::query()
                ->where(
                    'is_active',
                    true
                )
                ->whereIn(
                    'gp_id',
                    $frozenGpIds
                );


        /*
        |--------------------------------------------------------------------------
        | Total Frozen Buildings
        |--------------------------------------------------------------------------
        */

        $totalBuildings =
            (clone $assetQuery)->count();


        /*
        |--------------------------------------------------------------------------
        | Data Entry Started
        |--------------------------------------------------------------------------
        |
        | This includes ALL GPs in the selected area.
        |
        */

        $dataStartedGps =
            PriAsset::query()
                ->where(
                    'is_active',
                    true
                )
                ->whereIn(
                    'gp_id',
                    $gpIds
                )
                ->distinct()
                ->count('gp_id');


        /*
        |--------------------------------------------------------------------------
        | Frozen GPs
        |--------------------------------------------------------------------------
        */

        $frozenGps =
            $frozenGpIds->count();


        /*
        |--------------------------------------------------------------------------
        | Not Started
        |--------------------------------------------------------------------------
        */

        $notStartedGps =
            max(
                0,
                $totalGps -
                $dataStartedGps
            );


        /*
        |--------------------------------------------------------------------------
        | CATEGORY-WISE ANALYSIS
        |--------------------------------------------------------------------------
        */

        $categoryData =
            (clone $assetQuery)
                ->select(

                    DB::raw(
                        'LOWER(TRIM(asset_category)) as category'
                    ),

                    DB::raw(
                        'COUNT(*) as total'
                    )

                )
                ->groupBy(
                    DB::raw(
                        'LOWER(TRIM(asset_category))'
                    )
                )
                ->pluck(
                    'total',
                    'category'
                )
                ->toArray();


        $categoryAnalysis = [

            'Official' =>
                $categoryData['official'] ?? 0,

            'Community' =>
                $categoryData['community'] ?? 0,

            'Commercial' =>
                $categoryData['commercial'] ?? 0,

        ];


        /*
        |--------------------------------------------------------------------------
        | PANCHAYAT GHAR
        |--------------------------------------------------------------------------
        */

        $panchayatGharQuery =
            (clone $assetQuery)
                ->whereRaw(
                    'LOWER(TRIM(asset_sub_category)) = ?',
                    ['panchayat_ghar']
                );


        /*
        | Total
        */

        $totalPanchayatGharGps =
            (clone $panchayatGharQuery)
                ->distinct()
                ->count('gp_id');


        /*
        | Owned
        */

        $ownedPanchayatGharGps =
            (clone $panchayatGharQuery)
                ->whereRaw(
                    'LOWER(TRIM(panchayat_office_status)) = ?',
                    ['owned_by_gram_panchayat']
                )
                ->distinct()
                ->count('gp_id');


        /*
        | Rented
        */

        $rentedPanchayatGharGps =
            (clone $panchayatGharQuery)
                ->whereRaw(
                    'LOWER(TRIM(panchayat_office_status)) = ?',
                    ['taken_on_rent']
                )
                ->distinct()
                ->count('gp_id');


        $panchayatGharAnalysis = [

            'total' =>
                $totalPanchayatGharGps,

            'owned' =>
                $ownedPanchayatGharGps,

            'rented' =>
                $rentedPanchayatGharGps,

        ];


        /*
        |--------------------------------------------------------------------------
        | OWNERSHIP ANALYSIS
        |--------------------------------------------------------------------------
        */

        $ownershipData =
            (clone $assetQuery)
                ->whereNotNull(
                    'ownership'
                )
                ->select(

                    DB::raw(
                        'LOWER(TRIM(ownership)) as ownership_type'
                    ),

                    DB::raw(
                        'COUNT(*) as total'
                    )

                )
                ->groupBy(
                    DB::raw(
                        'LOWER(TRIM(ownership))'
                    )
                )
                ->pluck(
                    'total',
                    'ownership_type'
                )
                ->toArray();


        $ownershipAnalysis = [

            'Gram Panchayat' =>
                $ownershipData[
                    'gram_panchayat'
                ] ?? 0,

            'Panchayat Samiti' =>
                $ownershipData[
                    'panchayat_samiti'
                ] ?? 0,

            'Zila Parishad' =>
                $ownershipData[
                    'zila_parishad'
                ] ?? 0,

        ];


        /*
        |--------------------------------------------------------------------------
        | PROGRESS PERCENTAGES
        |--------------------------------------------------------------------------
        */

        $startedPercentage =
            $totalGps > 0

                ? round(
                    (
                        $dataStartedGps /
                        $totalGps
                    ) * 100,
                    1
                )

                : 0;


        $frozenPercentage =
            $totalGps > 0

                ? round(
                    (
                        $frozenGps /
                        $totalGps
                    ) * 100,
                    1
                )

                : 0;


        $notStartedPercentage =
            $totalGps > 0

                ? round(
                    (
                        $notStartedGps /
                        $totalGps
                    ) * 100,
                    1
                )

                : 0;


        /*
        |--------------------------------------------------------------------------
        | RETURN ANALYSIS
        |--------------------------------------------------------------------------
        */

        return [

            'totalGps' =>
                $totalGps,

            'totalBuildings' =>
                $totalBuildings,

            'dataStartedGps' =>
                $dataStartedGps,

            'frozenGps' =>
                $frozenGps,

            'notStartedGps' =>
                $notStartedGps,

            'categoryAnalysis' =>
                $categoryAnalysis,

            'panchayatGharAnalysis' =>
                $panchayatGharAnalysis,

            'ownershipAnalysis' =>
                $ownershipAnalysis,

            'startedPercentage' =>
                $startedPercentage,

            'frozenPercentage' =>
                $frozenPercentage,

            'notStartedPercentage' =>
                $notStartedPercentage,

        ];
    }


    /**
     * =========================================================================
     * GET FROZEN GP IDs
     * =========================================================================
     */
    private function getFrozenGpIds()
    {
        return PriAssetSubmission::query()
            ->where(
                'status',
                'submitted'
            )
            ->pluck('gp_id')
            ->filter()
            ->unique()
            ->values();
    }


    /**
     * =========================================================================
     * CSV EXPORT
     * =========================================================================
     *
     * Only buildings belonging to frozen GPs are exported.
     *
     */
    public function export()
    {
        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PRI_Frozen_Building_Details_' .
            now()->format('Y-m-d_H-i-s') .
            '.csv';


        /*
        |--------------------------------------------------------------------------
        | Category Labels
        |--------------------------------------------------------------------------
        */

        $categoryLabels = [

            'official' =>
                'Official',

            'community' =>
                'Community',

            'commercial' =>
                'Commercial',

        ];


        /*
        |--------------------------------------------------------------------------
        | Sub Category Labels
        |--------------------------------------------------------------------------
        */

        $subCategoryLabels = [

            // -------------------------------------------------------------
            // Official
            // -------------------------------------------------------------

            'panchayat_ghar' =>
                'Panchayat Ghar / Panchayat Office',

            'panchayat_learning_centre' =>
                'Panchayat Learning Centre (PLC)',

            'common_service_centre' =>
                'Common Service Centre',


            // -------------------------------------------------------------
            // Community
            // -------------------------------------------------------------

            'mahila_mandal_bhawan' =>
                'Mahila Mandal Bhawan',

            'yuvak_mandal_bhawan' =>
                'Yuvak Mandal Bhawan',

            'ambedkar_bhawan' =>
                'Ambedkar Bhawan',

            'mukhya_mantri_lok_bhawan' =>
                'Mukhya Mantri Lok Bhawan',

            'marriage_hall' =>
                'Marriage Hall',

            'community_centre' =>
                'Community Centre',

            'library' =>
                'Library',


            // -------------------------------------------------------------
            // Commercial
            // -------------------------------------------------------------

            'shops_of_panchayat' =>
                'Shops of Panchayat',

            'commercial_complexes_of_panchayat' =>
                'Commercial Complexes of Panchayat',

            'godowns_of_panchayat' =>
                'Godowns of Panchayat',

            'guest_house_of_panchayat' =>
                'Guest House of Panchayat',

        ];


        /*
        |--------------------------------------------------------------------------
        | Ownership Labels
        |--------------------------------------------------------------------------
        */

        $ownershipLabels = [

            'gram_panchayat' =>
                'Gram Panchayat',

            'panchayat_samiti' =>
                'Panchayat Samiti',

            'zila_parishad' =>
                'Zila Parishad',

        ];


        /*
        |--------------------------------------------------------------------------
        | Panchayat Office Status Labels
        |--------------------------------------------------------------------------
        */

        $panchayatOfficeStatusLabels = [

            'owned_by_gram_panchayat' =>
                'Owned by Gram Panchayat',

            'taken_on_rent' =>
                'Taken on Rent',

        ];


        /*
        |--------------------------------------------------------------------------
        | GET ONLY FROZEN BUILDINGS
        |--------------------------------------------------------------------------
        */

        $assets = PriAsset::query()

            ->from(
                'pri_assets'
            )


            /*
            |--------------------------------------------------------------------------
            | Join Submission
            |--------------------------------------------------------------------------
            */

            ->join(
                'pri_asset_submissions',
                'pri_assets.gp_id',
                '=',
                'pri_asset_submissions.gp_id'
            )


            /*
            |--------------------------------------------------------------------------
            | Join GP Master
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'gp_list',
                'pri_assets.gp_id',
                '=',
                'gp_list.id'
            )


            /*
            |--------------------------------------------------------------------------
            | Only Frozen GPs
            |--------------------------------------------------------------------------
            */

            ->where(
                'pri_asset_submissions.status',
                'submitted'
            )


            /*
            |--------------------------------------------------------------------------
            | Only Active Buildings
            |--------------------------------------------------------------------------
            */

            ->where(
                'pri_assets.is_active',
                true
            )


            /*
            |--------------------------------------------------------------------------
            | Select
            |--------------------------------------------------------------------------
            */

            ->select(

                'pri_assets.asset_uuid',

                'gp_list.district_name',

                'gp_list.block_name',

                'gp_list.gp_name',

                'pri_assets.asset_category',

                'pri_assets.asset_sub_category',

                'pri_assets.building_name',

                'pri_assets.ownership',

                'pri_assets.panchayat_office_status',

                'pri_assets.latitude',

                'pri_assets.longitude',

                'pri_assets.location_accuracy',

                'pri_assets.location_captured_at',

                'pri_assets.asset_image',

                'pri_asset_submissions.submitted_at',

                'pri_assets.created_at',

                'pri_assets.updated_at'

            )


            /*
            |--------------------------------------------------------------------------
            | Sorting
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                'gp_list.district_name'
            )

            ->orderBy(
                'gp_list.block_name'
            )

            ->orderBy(
                'gp_list.gp_name'
            )

            ->orderBy(
                'pri_assets.building_name'
            )


            /*
            |--------------------------------------------------------------------------
            | Cursor
            |--------------------------------------------------------------------------
            */

            ->cursor();


        /*
        |--------------------------------------------------------------------------
        | CREATE CSV
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(

            function () use (
                $assets,
                $categoryLabels,
                $subCategoryLabels,
                $ownershipLabels,
                $panchayatOfficeStatusLabels
            ) {

                /*
                |--------------------------------------------------------------------------
                | Open Output
                |--------------------------------------------------------------------------
                */

                $handle =
                    fopen(
                        'php://output',
                        'w'
                    );


                /*
                |--------------------------------------------------------------------------
                | UTF-8 BOM
                |--------------------------------------------------------------------------
                */

                fwrite(
                    $handle,
                    "\xEF\xBB\xBF"
                );


                /*
                |--------------------------------------------------------------------------
                | CSV HEADER
                |--------------------------------------------------------------------------
                */

                fputcsv(
                    $handle,
                    [

                        'Asset UUID',

                        'District',

                        'Block',

                        'Gram Panchayat',

                        'Building Category',

                        'Building Sub-Category',

                        'Building Name',

                        'Ownership',

                        'Panchayat Office Status',

                        'Latitude',

                        'Longitude',

                        'Location Accuracy',

                        'Location Captured At',

                        'Photograph',

                        'Final Submitted At',

                        'Created At',

                        'Updated At',

                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | CSV RECORDS
                |--------------------------------------------------------------------------
                */

                foreach (
                    $assets as $asset
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Category
                    |--------------------------------------------------------------------------
                    */

                    $categoryKey =
                        strtolower(
                            trim(
                                (string)
                                $asset->asset_category
                            )
                        );


                    $category =
                        $categoryLabels[
                            $categoryKey
                        ]
                        ?? ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $categoryKey
                            )
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Sub Category
                    |--------------------------------------------------------------------------
                    */

                    $subCategoryKey =
                        strtolower(
                            trim(
                                (string)
                                $asset->asset_sub_category
                            )
                        );


                    $subCategory =
                        $subCategoryLabels[
                            $subCategoryKey
                        ]
                        ?? ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $subCategoryKey
                            )
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Ownership
                    |--------------------------------------------------------------------------
                    */

                    $ownershipKey =
                        strtolower(
                            trim(
                                (string)
                                $asset->ownership
                            )
                        );


                    $ownership =
                        $ownershipLabels[
                            $ownershipKey
                        ]
                        ?? ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $ownershipKey
                            )
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Panchayat Office Status
                    |--------------------------------------------------------------------------
                    */

                    $officeStatusKey =
                        strtolower(
                            trim(
                                (string)
                                $asset->panchayat_office_status
                            )
                        );


                    $officeStatus = '';


                    if (
                        $officeStatusKey !== ''
                    ) {

                        $officeStatus =
                            $panchayatOfficeStatusLabels[
                                $officeStatusKey
                            ]
                            ?? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $officeStatusKey
                                )
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | WRITE CSV ROW
                    |--------------------------------------------------------------------------
                    */

                    fputcsv(
                        $handle,
                        [

                            $asset->asset_uuid,

                            $asset->district_name,

                            $asset->block_name,

                            $asset->gp_name,

                            $category,

                            $subCategory,

                            $asset->building_name,

                            $ownership,

                            $officeStatus,

                            $asset->latitude,

                            $asset->longitude,

                            $asset->location_accuracy,

                            $asset->location_captured_at,

                            $asset->asset_image,

                            $asset->submitted_at,

                            $asset->created_at,

                            $asset->updated_at,

                        ]
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Close File
                |--------------------------------------------------------------------------
                */

                fclose(
                    $handle
                );

            },

            $fileName,

            [

                'Content-Type' =>
                    'text/csv; charset=UTF-8',

                'Content-Disposition' =>
                    'attachment; filename="' .
                    $fileName .
                    '"',

            ]

        );
    }
}