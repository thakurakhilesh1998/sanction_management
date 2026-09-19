@extends('layouts.dir')

@section('main')

<style>

/* =========================================================
   GP ASSET REPORT
   ========================================================= */

.gp-page {
    background: #f5f7fb;
    min-height: calc(100vh - 70px);
    padding: 25px;
}


/* ---------------------------------------------------------
   Header
--------------------------------------------------------- */

.gp-header {
    background: linear-gradient(
        135deg,
        #4e73df 0%,
        #224abe 100%
    );

    border-radius: 16px;

    padding: 24px 28px;

    color: #fff;

    margin-bottom: 25px;

    box-shadow: 0 8px 25px rgba(78,115,223,.20);
}

.gp-header h3 {
    margin: 0;

    font-size: 23px;

    font-weight: 700;
}

.gp-header p {
    margin: 5px 0 0;

    font-size: 12px;

    opacity: .9;
}


/* ---------------------------------------------------------
   Breadcrumb
--------------------------------------------------------- */

.report-breadcrumb {
    margin-bottom: 8px;
}

.report-breadcrumb a {
    color: rgba(255,255,255,.85);

    text-decoration: none;

    font-size: 11px;

    font-weight: 600;
}

.report-breadcrumb a:hover {
    color: #fff;
}

.report-breadcrumb span {
    margin: 0 6px;

    opacity: .55;

    font-size: 10px;
}


/* ---------------------------------------------------------
   Header Status
--------------------------------------------------------- */

.header-status {
    display: inline-flex;

    align-items: center;

    padding: 6px 11px;

    border-radius: 20px;

    background: rgba(255,255,255,.14);

    font-size: 10px;

    font-weight: 700;

    margin-top: 8px;
}


/* ---------------------------------------------------------
   Summary Cards
--------------------------------------------------------- */

.summary-card {
    background: #fff;

    border-radius: 14px;

    padding: 20px;

    height: 100%;

    box-shadow: 0 3px 15px rgba(0,0,0,.06);

    transition: all .25s ease;

    position: relative;

    overflow: hidden;
}

.summary-card:hover {
    transform: translateY(-4px);

    box-shadow: 0 8px 22px rgba(0,0,0,.10);
}

.summary-icon {
    width: 44px;
    height: 44px;

    border-radius: 11px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-bottom: 12px;

    font-size: 17px;
}

.summary-card h3 {
    font-size: 24px;

    font-weight: 700;

    margin: 0 0 3px;

    color: #343a40;
}

.summary-card p {
    margin: 0;

    font-size: 11px;

    color: #858796;

    font-weight: 600;
}


.icon-blue {
    background: #e8efff;
    color: #4e73df;
}

.icon-purple {
    background: #f0eafd;
    color: #6f42c1;
}

.icon-teal {
    background: #e7f8f7;
    color: #20c9a6;
}

.icon-green {
    background: #e8f8ef;
    color: #1cc88a;
}

.icon-orange {
    background: #fff3e5;
    color: #f6c23e;
}


/* ---------------------------------------------------------
   Section
--------------------------------------------------------- */

.section-title {
    display: flex;

    align-items: center;

    margin: 28px 0 15px;
}

.section-icon {
    width: 38px;
    height: 38px;

    border-radius: 10px;

    background: #eef2ff;

    color: #4e73df;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-right: 12px;
}

.section-title h5 {
    margin: 0;

    font-weight: 700;

    color: #343a40;
}

.section-title small {
    display: block;

    color: #858796;

    margin-top: 2px;
}


/* ---------------------------------------------------------
   Analysis Cards
--------------------------------------------------------- */

.analysis-card {
    background: #fff;

    border-radius: 15px;

    box-shadow: 0 3px 15px rgba(0,0,0,.06);

    height: 100%;
}

.analysis-header {
    padding: 17px 20px;

    border-bottom: 1px solid #edf0f5;
}

.analysis-header h6 {
    margin: 0;

    font-weight: 700;

    color: #343a40;
}

.analysis-body {
    padding: 20px;
}


/* ---------------------------------------------------------
   Category Stats
--------------------------------------------------------- */

.category-item {
    display: flex;

    align-items: center;

    padding: 12px 0;

    border-bottom: 1px solid #f0f0f0;
}

.category-item:last-child {
    border-bottom: 0;
}

.category-icon {
    width: 38px;
    height: 38px;

    border-radius: 9px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-right: 12px;
}

.category-content {
    flex: 1;
}

.category-content strong {
    display: block;

    font-size: 17px;

    color: #343a40;
}

.category-content span {
    font-size: 11px;

    color: #858796;
}


/* ---------------------------------------------------------
   Building Table
--------------------------------------------------------- */

.building-card {
    background: #fff;

    border-radius: 15px;

    overflow: hidden;

    box-shadow: 0 3px 15px rgba(0,0,0,.06);
}

.building-card-header {
    padding: 18px 20px;

    border-bottom: 1px solid #edf0f5;

    display: flex;

    align-items: center;

    justify-content: space-between;
}

.building-card-header h6 {
    margin: 0;

    font-weight: 700;

    color: #343a40;
}

.building-card-header small {
    color: #858796;
}


.building-table {
    margin-bottom: 0;
}

.building-table thead th {
    background: #f8f9fc;

    color: #6c757d;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: .4px;

    border: 0;

    padding: 13px 15px;

    white-space: nowrap;
}

.building-table tbody td {
    padding: 14px 15px;

    font-size: 12px;

    vertical-align: middle;

    border-color: #f0f0f0;
}


/* ---------------------------------------------------------
   Building Name
--------------------------------------------------------- */

.building-name {
    display: flex;

    align-items: center;

    font-weight: 700;

    color: #343a40;
}

.building-icon {
    width: 32px;
    height: 32px;

    border-radius: 8px;

    background: #edf2ff;

    color: #4169d8;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    margin-right: 9px;

    font-size: 11px;
}


/* ---------------------------------------------------------
   Category Badge
--------------------------------------------------------- */

.category-badge {
    display: inline-flex;

    align-items: center;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;

    font-weight: 700;
}

.category-official {
    background: #e8efff;
    color: #4169d8;
}

.category-community {
    background: #f0eafd;
    color: #6f42c1;
}

.category-commercial {
    background: #fff3e5;
    color: #c98b00;
}


/* ---------------------------------------------------------
   Ownership Badge
--------------------------------------------------------- */

.ownership-badge {
    display: inline-flex;

    align-items: center;

    padding: 5px 9px;

    border-radius: 20px;

    background: #f1f3f5;

    color: #495057;

    font-size: 10px;

    font-weight: 600;
}


/* ---------------------------------------------------------
   Location
--------------------------------------------------------- */

.location-link {
    color: #4169d8;

    text-decoration: none !important;

    font-size: 11px;

    font-weight: 600;
}

.location-link:hover {
    text-decoration: underline !important;
}


/* ---------------------------------------------------------
   Photo
--------------------------------------------------------- */

.building-photo {
    width: 55px;
    height: 45px;

    object-fit: cover;

    border-radius: 7px;

    border: 1px solid #e5e7eb;
}

.no-photo {
    width: 55px;
    height: 45px;

    border-radius: 7px;

    background: #f1f3f5;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    color: #adb5bd;

    font-size: 15px;
}


/* ---------------------------------------------------------
   Panchayat Ghar Status
--------------------------------------------------------- */

.office-status {
    display: inline-flex;

    align-items: center;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;

    font-weight: 700;
}

.office-owned {
    background: #e8f8ef;

    color: #159764;
}

.office-rented {
    background: #fff4df;

    color: #c98b00;
}


/* ---------------------------------------------------------
   Empty
--------------------------------------------------------- */

.empty-data {
    padding: 55px 20px;

    text-align: center;

    color: #858796;
}


/* ---------------------------------------------------------
   Responsive
--------------------------------------------------------- */

@media (max-width: 768px) {

    .gp-page {
        padding: 15px;
    }

    .gp-header {
        padding: 20px;
    }

    .gp-header h3 {
        font-size: 20px;
    }

}

</style>


<div class="gp-page">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="gp-header">

        <div class="report-breadcrumb">

            <a href="{{ route('state.asset-report.dashboard') }}">

                <i class="fas fa-home mr-1"></i>

                State Dashboard

            </a>

            <span>/</span>

            <a href="{{ route(
                'state.asset-report.district',
                ['district' => $district]
            ) }}">

                {{ $district }}

            </a>

            <span>/</span>

            <a href="{{ route(
                'state.asset-report.block',
                [
                    'district' => $district,
                    'block' => $block
                ]
            ) }}">

                {{ $block }}

            </a>

        </div>


        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h3>

                    <i class="fas fa-home mr-2"></i>

                    {{ $gpData->gp_name }}

                </h3>

                <p>
                    Gram Panchayat-wise PRI Building Information
                </p>


                @if($isFrozen)

                    <div class="header-status">

                        <i class="fas fa-lock mr-1"></i>

                        Data Frozen / Final Submitted

                    </div>

                @else

                    <div class="header-status">

                        <i class="fas fa-edit mr-1"></i>

                        Data Entry In Progress

                    </div>

                @endif

            </div>


            <div style="
                width:52px;
                height:52px;
                border-radius:14px;
                background:rgba(255,255,255,.15);
                display:flex;
                align-items:center;
                justify-content:center;
            ">

                <i class="fas fa-building fa-lg"></i>

            </div>

        </div>

    </div>



    <!-- =====================================================
         SUMMARY
    ====================================================== -->

    <div class="row">


        <!-- Total Buildings -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-blue">

                    <i class="fas fa-building"></i>

                </div>

                <h3>
                    {{ number_format($totalBuildings) }}
                </h3>

                <p>
                    Total Buildings
                </p>

            </div>

        </div>


        <!-- Official -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-purple">

                    <i class="fas fa-landmark"></i>

                </div>

                <h3>
                    {{ number_format($officialBuildings) }}
                </h3>

                <p>
                    Official Buildings
                </p>

            </div>

        </div>


        <!-- Community -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-teal">

                    <i class="fas fa-users"></i>

                </div>

                <h3>
                    {{ number_format($communityBuildings) }}
                </h3>

                <p>
                    Community Buildings
                </p>

            </div>

        </div>


        <!-- Commercial -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-orange">

                    <i class="fas fa-store"></i>

                </div>

                <h3>
                    {{ number_format($commercialBuildings) }}
                </h3>

                <p>
                    Commercial Buildings
                </p>

            </div>

        </div>

    </div>



    <!-- =====================================================
         ANALYSIS
    ====================================================== -->

    <div class="section-title">

        <div class="section-icon">

            <i class="fas fa-chart-pie"></i>

        </div>

        <div>

            <h5>
                Building Analysis
            </h5>

            <small>
                Category and ownership details
            </small>

        </div>

    </div>


    <div class="row">


        <!-- Category -->

        <div class="col-lg-6 mb-4">

            <div class="analysis-card">

                <div class="analysis-header">

                    <h6>

                        <i class="fas fa-layer-group mr-2"></i>

                        Building Category

                    </h6>

                </div>


                <div class="analysis-body">


                    <div class="category-item">

                        <div class="
                            category-icon
                            icon-purple
                        ">

                            <i class="fas fa-landmark"></i>

                        </div>

                        <div class="category-content">

                            <strong>
                                {{ number_format($officialBuildings) }}
                            </strong>

                            <span>
                                Official Buildings
                            </span>

                        </div>

                    </div>


                    <div class="category-item">

                        <div class="
                            category-icon
                            icon-teal
                        ">

                            <i class="fas fa-users"></i>

                        </div>

                        <div class="category-content">

                            <strong>
                                {{ number_format($communityBuildings) }}
                            </strong>

                            <span>
                                Community Buildings
                            </span>

                        </div>

                    </div>


                    <div class="category-item">

                        <div class="
                            category-icon
                            icon-orange
                        ">

                            <i class="fas fa-store"></i>

                        </div>

                        <div class="category-content">

                            <strong>
                                {{ number_format($commercialBuildings) }}
                            </strong>

                            <span>
                                Commercial Buildings
                            </span>

                        </div>

                    </div>


                </div>

            </div>

        </div>



        <!-- Ownership -->

        <div class="col-lg-6 mb-4">

            <div class="analysis-card">

                <div class="analysis-header">

                    <h6>

                        <i class="fas fa-user-shield mr-2"></i>

                        Building Ownership

                    </h6>

                </div>


                <div class="analysis-body">


                    <div class="category-item">

                        <div class="
                            category-icon
                            icon-blue
                        ">

                            <i class="fas fa-home"></i>

                        </div>

                        <div class="category-content">

                            <strong>
                                {{ number_format(
                                    $gramPanchayatBuildings
                                ) }}
                            </strong>

                            <span>
                                Gram Panchayat
                            </span>

                        </div>

                    </div>


                    <div class="category-item">

                        <div class="
                            category-icon
                            icon-purple
                        ">

                            <i class="fas fa-sitemap"></i>

                        </div>

                        <div class="category-content">

                            <strong>
                                {{ number_format(
                                    $panchayatSamitiBuildings
                                ) }}
                            </strong>

                            <span>
                                Panchayat Samiti
                            </span>

                        </div>

                    </div>


                    <div class="category-item">

                        <div class="
                            category-icon
                            icon-green
                        ">

                            <i class="fas fa-landmark"></i>

                        </div>

                        <div class="category-content">

                            <strong>
                                {{ number_format(
                                    $zilaParishadBuildings
                                ) }}
                            </strong>

                            <span>
                                Zila Parishad
                            </span>

                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         PANCHAYAT GHAR
    ====================================================== -->

    @if($panchayatGharCount > 0)

        <div class="section-title">

            <div class="section-icon">

                <i class="fas fa-home"></i>

            </div>

            <div>

                <h5>
                    Panchayat Ghar Status
                </h5>

                <small>
                    Panchayat Office ownership / rental status
                </small>

            </div>

        </div>


        <div class="row">

            <div class="col-md-4 mb-4">

                <div class="summary-card">

                    <div class="
                        summary-icon
                        icon-blue
                    ">

                        <i class="fas fa-home"></i>

                    </div>

                    <h3>
                        {{ number_format($panchayatGharCount) }}
                    </h3>

                    <p>
                        Panchayat Ghar Reported
                    </p>

                </div>

            </div>


            <div class="col-md-4 mb-4">

                <div class="summary-card">

                    <div class="
                        summary-icon
                        icon-green
                    ">

                        <i class="fas fa-check-circle"></i>

                    </div>

                    <h3>
                        {{ number_format($panchayatGharOwned) }}
                    </h3>

                    <p>
                        Owned by Gram Panchayat
                    </p>

                </div>

            </div>


            <div class="col-md-4 mb-4">

                <div class="summary-card">

                    <div class="
                        summary-icon
                        icon-orange
                    ">

                        <i class="fas fa-key"></i>

                    </div>

                    <h3>
                        {{ number_format($panchayatGharRented) }}
                    </h3>

                    <p>
                        Taken on Rent
                    </p>

                </div>

            </div>

        </div>

    @endif



    <!-- =====================================================
         BUILDING DETAILS
    ====================================================== -->

    <div class="section-title">

        <div class="section-icon">

            <i class="fas fa-building"></i>

        </div>

        <div>

            <h5>
                Building Details
            </h5>

            <small>
                Complete list of buildings reported by this Gram Panchayat
            </small>

        </div>

    </div>


    <div class="building-card">


        <div class="building-card-header">

            <div>

                <h6>

                    <i class="fas fa-list mr-2"></i>

                    PRI Buildings

                </h6>

                <small>

                    {{ $district }}
                    /
                    {{ $block }}
                    /
                    {{ $gpData->gp_name }}

                </small>

            </div>


            <span class="badge badge-primary">

                {{ number_format($totalBuildings) }}

                Buildings

            </span>

        </div>



        <div class="table-responsive">

            <table class="table building-table">

                <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Building
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Sub-Category
                        </th>

                        <th>
                            Ownership
                        </th>

                        <th>
                            Panchayat Ghar Status
                        </th>

                        <th>
                            Location
                        </th>

                        <th>
                            Photo
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse($buildings as $key => $building)

                        <tr>


                            <!-- # -->

                            <td>

                                {{ $key + 1 }}

                            </td>



                            <!-- Building -->

                            <td>

                                <div class="building-name">

                                    <span class="building-icon">

                                        <i class="fas fa-building"></i>

                                    </span>

                                    <span>

                                        {{ $building->building_name }}

                                    </span>

                                </div>

                            </td>



                            <!-- Category -->

                            <td>

                                @if(
                                    $building->asset_category
                                    === 'Official'
                                )

                                    <span class="
                                        category-badge
                                        category-official
                                    ">

                                        <i class="
                                            fas
                                            fa-landmark
                                            mr-1
                                        "></i>

                                        Official

                                    </span>


                                @elseif(
                                    $building->asset_category
                                    === 'Community'
                                )

                                    <span class="
                                        category-badge
                                        category-community
                                    ">

                                        <i class="
                                            fas
                                            fa-users
                                            mr-1
                                        "></i>

                                        Community

                                    </span>


                                @else

                                    <span class="
                                        category-badge
                                        category-commercial
                                    ">

                                        <i class="
                                            fas
                                            fa-store
                                            mr-1
                                        "></i>

                                        Commercial

                                    </span>

                                @endif

                            </td>



                            <!-- Sub Category -->

                            <td>

                                {{ $building->asset_sub_category }}

                            </td>



                            <!-- Ownership -->

                            <td>

                                @if(
                                    $building->ownership
                                    === 'gram_panchayat'
                                )

                                    <span class="
                                        ownership-badge
                                    ">

                                        <i class="
                                            fas
                                            fa-home
                                            mr-1
                                        "></i>

                                        Gram Panchayat

                                    </span>


                                @elseif(
                                    $building->ownership
                                    === 'panchayat_samiti'
                                )

                                    <span class="
                                        ownership-badge
                                    ">

                                        <i class="
                                            fas
                                            fa-sitemap
                                            mr-1
                                        "></i>

                                        Panchayat Samiti

                                    </span>


                                @else

                                    <span class="
                                        ownership-badge
                                    ">

                                        <i class="
                                            fas
                                            fa-landmark
                                            mr-1
                                        "></i>

                                        Zila Parishad

                                    </span>

                                @endif

                            </td>



                            <!-- Panchayat Ghar Status -->

                            <td>

                                @if(
                                    $building->asset_sub_category
                                    === 'panchayat_ghar'
                                )

                                    @if(
                                        $building->panchayat_office_status
                                        === 'owned_by_gram_panchayat'
                                    )

                                        <span class="
                                            office-status
                                            office-owned
                                        ">

                                            <i class="
                                                fas
                                                fa-check-circle
                                                mr-1
                                            "></i>

                                            Owned

                                        </span>


                                    @elseif(
                                        $building->panchayat_office_status
                                        === 'taken_on_rent'
                                    )

                                        <span class="
                                            office-status
                                            office-rented
                                        ">

                                            <i class="
                                                fas
                                                fa-key
                                                mr-1
                                            "></i>

                                            Taken on Rent

                                        </span>


                                    @else

                                        <span class="text-muted">

                                            —

                                        </span>

                                    @endif


                                @else

                                    <span class="text-muted">

                                        —

                                    </span>

                                @endif

                            </td>



                            <!-- Location -->

                            <td>

                                @if(
                                    $building->latitude
                                    &&
                                    $building->longitude
                                )

                                    <a
                                        href="https://www.google.com/maps?q={{ $building->latitude }},{{ $building->longitude }}"
                                        target="_blank"
                                        class="location-link"
                                    >

                                        <i class="
                                            fas
                                            fa-map-marker-alt
                                            mr-1
                                        "></i>

                                        View Location

                                    </a>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            <!-- Photo -->

                            <td>

                                @if($building->asset_image)

                                    <a
                                        href="{{ asset(
                                            'storage/' .
                                            $building->asset_image
                                        ) }}"
                                        target="_blank"
                                    >

                                        <img
                                            src="{{ asset(
                                                'storage/' .
                                                $building->asset_image
                                            ) }}"
                                            class="building-photo"
                                            alt="Building"
                                        >

                                    </a>

                                @else

                                    <span class="no-photo">

                                        <i class="
                                            fas
                                            fa-image
                                        "></i>

                                    </span>

                                @endif

                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty-data"
                            >

                                <i class="
                                    fas
                                    fa-building
                                    fa-2x
                                    mb-3
                                "></i>

                                <br>

                                No building information has been
                                entered by this Gram Panchayat.

                            </td>

                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>

    </div>


</div>

@endsection