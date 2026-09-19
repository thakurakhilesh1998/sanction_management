@extends('layouts.dir')

@section('main')

<style>

/* Keep your existing dashboard CSS here */

/* =========================================================
   DISTRICT REPORT
   ========================================================= */

.district-page {
    background: #f5f7fb;
    min-height: calc(100vh - 70px);
    padding: 25px;
}

.district-header {
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

.district-header h3 {
    margin: 0;
    font-size: 23px;
    font-weight: 700;
}

.district-header p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .9;
}

.summary-card,
.analysis-card,
.district-card {
    background: #fff;
    border-radius: 15px;
    border: 0;
    box-shadow: 0 3px 15px rgba(0,0,0,.06);
}

.summary-card {
    padding: 20px;
    height: 100%;
}

.summary-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 12px;
}

.summary-card h3 {
    font-size: 25px;
    font-weight: 700;
    margin-bottom: 3px;
}

.summary-card p {
    margin: 0;
    font-size: 12px;
    color: #858796;
    font-weight: 600;
}

.icon-blue {
    background: #e8efff;
    color: #4e73df;
}

.icon-green {
    background: #e8f8ef;
    color: #1cc88a;
}

.icon-orange {
    background: #fff3e5;
    color: #f6c23e;
}

.icon-purple {
    background: #f0eafd;
    color: #6f42c1;
}

.icon-teal {
    background: #e7f8f7;
    color: #20c9a6;
}

.icon-red {
    background: #fdeaea;
    color: #e74a3b;
}

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
    color: #858796;
}

.analysis-header,
.district-card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f5;
}

.analysis-header h6,
.district-card-header h6 {
    margin: 0;
    font-weight: 700;
}

.analysis-body {
    padding: 20px;
}

.category-row {
    margin-bottom: 18px;
}

.category-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 600;
}

.progress {
    height: 9px;
    border-radius: 20px;
    background: #edf0f5;
}

.progress-bar {
    border-radius: 20px;
}

.mini-stat {
    display: flex;
    align-items: center;
    padding: 13px 0;
    border-bottom: 1px solid #f0f0f0;
}

.mini-stat:last-child {
    border-bottom: 0;
}

.mini-stat-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-right: 12px;
}

.mini-stat-content strong {
    display: block;
    font-size: 17px;
}

.mini-stat-content span {
    font-size: 11px;
    color: #858796;
}

.district-table {
    margin-bottom: 0;
}

.district-table thead th {
    background: #f8f9fc;
    color: #6c757d;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .4px;
    border: 0;
    padding: 13px 15px;
    white-space: nowrap;
}

.district-table tbody td {
    padding: 14px 15px;
    font-size: 12px;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.block-link {
    display: inline-flex;
    align-items: center;

    color: #303645;
    text-decoration: none !important;
    font-weight: 700;

    transition: all .2s ease;
}

.block-link:hover {
    color: #4169d8;
    transform: translateX(3px);
}

.block-icon {
    width: 31px;
    height: 31px;
    border-radius: 8px;

    background: #edf2ff;
    color: #4169d8;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    margin-right: 9px;

    font-size: 11px;
}

.block-arrow {
    margin-left: 8px;
    font-size: 8px;
    color: #a0a6b2;
}

.status-badge {
    display: inline-flex;
    align-items: center;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 700;
}

.status-started {
    background: #fff4df;
    color: #c98b00;
}

.status-frozen {
    background: #e6f7ee;
    color: #159764;
}

</style>


<div class="district-page">


    <!-- HEADER -->

    <div class="district-header">

        <div class="mb-2">

            <a
                href="{{ route('state.asset-report.dashboard') }}"
                style="
                    color:rgba(255,255,255,.85);
                    font-size:11px;
                    text-decoration:none;
                "
            >

                <i class="fas fa-arrow-left mr-1"></i>

                State Dashboard

            </a>

        </div>


        <h3>

            <i class="fas fa-map-marker-alt mr-2"></i>

            {{ $district }}

        </h3>

        <p>
            District-wise PRI Building Information Dashboard
        </p>

    </div>



    <!-- =====================================================
         OVERALL STATUS
    ====================================================== -->

    <div class="section-title">

        <div class="section-icon">

            <i class="fas fa-chart-line"></i>

        </div>

        <div>

            <h5>
                Overall Status
            </h5>

            <small>
                Building information for {{ $district }}
            </small>

        </div>

    </div>


    <div class="row">


        <!-- Blocks -->

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-purple">

                    <i class="fas fa-sitemap"></i>

                </div>

                <h3>
                    {{ number_format($totalBlocks) }}
                </h3>

                <p>
                    Total Blocks
                </p>

            </div>

        </div>


        <!-- GPs -->

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-teal">

                    <i class="fas fa-home"></i>

                </div>

                <h3>
                    {{ number_format($totalGps) }}
                </h3>

                <p>
                    Total GPs
                </p>

            </div>

        </div>


        <!-- Buildings -->

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

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


        <!-- Started -->

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-orange">

                    <i class="fas fa-edit"></i>

                </div>

                <h3>
                    {{ number_format($dataStartedGps) }}
                </h3>

                <p>
                    Data Started
                </p>

            </div>

        </div>


        <!-- Frozen -->

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-green">

                    <i class="fas fa-lock"></i>

                </div>

                <h3>
                    {{ number_format($frozenGps) }}
                </h3>

                <p>
                    Data Frozen
                </p>

            </div>

        </div>


        <!-- Not Started -->

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-red">

                    <i class="fas fa-clock"></i>

                </div>

                <h3>
                    {{ number_format($notStartedGps) }}
                </h3>

                <p>
                    Not Started
                </p>

            </div>

        </div>

    </div>



    <!-- =====================================================
         BUILDING ANALYSIS
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
                Category-wise analysis for {{ $district }}
            </small>

        </div>

    </div>


    <div class="row">


        <!-- Category -->

        <div class="col-lg-7 mb-4">

            <div class="analysis-card">

                <div class="analysis-header">

                    <h6>
                        <i class="fas fa-layer-group mr-2"></i>
                        Building Category-wise
                    </h6>

                </div>


                <div class="analysis-body">

                    @php
                        $categoryTotal =
                            array_sum($categoryAnalysis);
                    @endphp


                    @foreach(
                        $categoryAnalysis
                        as $category => $count
                    )

                        @php

                            $percentage =
                                $categoryTotal > 0
                                ? round(
                                    ($count / $categoryTotal) * 100,
                                    1
                                )
                                : 0;

                        @endphp


                        <div class="category-row">

                            <div class="category-label">

                                <span>

                                    @if($category === 'Official')

                                        <i class="
                                            fas
                                            fa-landmark
                                            mr-1
                                        "></i>

                                    @elseif($category === 'Community')

                                        <i class="
                                            fas
                                            fa-users
                                            mr-1
                                        "></i>

                                    @else

                                        <i class="
                                            fas
                                            fa-store
                                            mr-1
                                        "></i>

                                    @endif

                                    {{ $category }}

                                </span>


                                <span>

                                    {{ number_format($count) }}

                                    ({{ $percentage }}%)

                                </span>

                            </div>


                            <div class="progress">

                                <div
                                    class="progress-bar bg-primary"
                                    style="
                                        width:
                                        {{ $percentage }}%;
                                    "
                                ></div>

                            </div>

                        </div>

                    @endforeach


                    <div class="text-center mt-4">

                        <small class="text-muted">

                            Total Buildings:

                            <strong>
                                {{ number_format($categoryTotal) }}
                            </strong>

                        </small>

                    </div>

                </div>

            </div>

        </div>



        <!-- Panchayat Ghar -->

        <div class="col-lg-5 mb-4">

            <div class="analysis-card">

                <div class="analysis-header">

                    <h6>

                        <i class="fas fa-home mr-2"></i>

                        Panchayat Ghar Status

                    </h6>

                </div>


                <div class="analysis-body">

                    @php

                        $pgTotal =
                            $panchayatGharAnalysis['total'];

                        $pgOwned =
                            $panchayatGharAnalysis['owned'];

                        $pgRented =
                            $panchayatGharAnalysis['rented'];

                        $pgPercentage =
                            $pgTotal > 0
                            ? round(
                                ($pgOwned / $pgTotal) * 100,
                                1
                            )
                            : 0;

                    @endphp


                    <div class="text-center">

                        <div style="
                            width:115px;
                            height:115px;
                            border-radius:50%;
                            margin:10px auto 15px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            background:#eef2ff;
                            border:10px solid #dce5ff;
                        ">

                            <strong
                                style="
                                    font-size:24px;
                                    color:#4e73df;
                                "
                            >

                                {{ $pgPercentage }}%

                            </strong>

                        </div>


                        <h6>
                            Panchayat Ghar Owned
                        </h6>


                        <small class="text-muted">

                            {{ number_format($pgOwned) }}

                            out of

                            {{ number_format($pgTotal) }}

                            reported

                        </small>

                    </div>


                    <div class="row mt-3">

                        <div class="col-6">

                            <div class="mini-stat">

                                <div class="
                                    mini-stat-icon
                                    icon-green
                                ">

                                    <i class="fas fa-home"></i>

                                </div>

                                <div class="
                                    mini-stat-content
                                ">

                                    <strong>
                                        {{ number_format($pgOwned) }}
                                    </strong>

                                    <span>
                                        Owned
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="mini-stat">

                                <div class="
                                    mini-stat-icon
                                    icon-orange
                                ">

                                    <i class="fas fa-key"></i>

                                </div>

                                <div class="
                                    mini-stat-content
                                ">

                                    <strong>
                                        {{ number_format($pgRented) }}
                                    </strong>

                                    <span>
                                        Rented
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         OWNERSHIP + PROGRESS
    ====================================================== -->

    <div class="row">


        <!-- Ownership -->

        <div class="col-lg-5 mb-4">

            <div class="analysis-card">

                <div class="analysis-header">

                    <h6>

                        <i class="fas fa-user-shield mr-2"></i>

                        Building Ownership

                    </h6>

                </div>


                <div class="analysis-body">


                    @foreach(
                        $ownershipAnalysis
                        as $owner => $count
                    )

                        <div class="mini-stat">

                            <div class="
                                mini-stat-icon
                                icon-blue
                            ">

                                <i class="
                                    fas
                                    fa-building
                                "></i>

                            </div>


                            <div class="
                                mini-stat-content
                            ">

                                <strong>
                                    {{ number_format($count) }}
                                </strong>

                                <span>
                                    {{ $owner }}
                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>



        <!-- Progress -->

        <div class="col-lg-7 mb-4">

            <div class="analysis-card">

                <div class="analysis-header">

                    <h6>

                        <i class="fas fa-tasks mr-2"></i>

                        Data Entry Progress

                    </h6>

                </div>


                <div class="analysis-body">


                    <div class="category-row">

                        <div class="category-label">

                            <span>
                                <i class="
                                    fas
                                    fa-edit
                                    mr-1
                                "></i>

                                Data Entry Started
                            </span>

                            <span>
                                {{ number_format($dataStartedGps) }}
                                /
                                {{ number_format($totalGps) }}
                            </span>

                        </div>


                        <div class="progress">

                            <div
                                class="
                                    progress-bar
                                    bg-warning
                                "
                                style="
                                    width:
                                    {{ $startedPercentage }}%;
                                "
                            ></div>

                        </div>

                    </div>


                    <div class="category-row">

                        <div class="category-label">

                            <span>
                                <i class="
                                    fas
                                    fa-lock
                                    mr-1
                                "></i>

                                Data Frozen
                            </span>

                            <span>
                                {{ number_format($frozenGps) }}
                                /
                                {{ number_format($totalGps) }}
                            </span>

                        </div>


                        <div class="progress">

                            <div
                                class="
                                    progress-bar
                                    bg-success
                                "
                                style="
                                    width:
                                    {{ $frozenPercentage }}%;
                                "
                            ></div>

                        </div>

                    </div>


                    <div class="category-row">

                        <div class="category-label">

                            <span>
                                <i class="
                                    fas
                                    fa-clock
                                    mr-1
                                "></i>

                                Data Entry Not Started
                            </span>

                            <span>
                                {{ number_format($notStartedGps) }}
                            </span>

                        </div>


                        <div class="progress">

                            <div
                                class="
                                    progress-bar
                                    bg-danger
                                "
                                style="
                                    width:
                                    {{ $notStartedPercentage }}%;
                                "
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         BLOCK-WISE
    ====================================================== -->

    <div class="section-title">

        <div class="section-icon">

            <i class="fas fa-sitemap"></i>

        </div>

        <div>

            <h5>
                Block-wise Progress
            </h5>

            <small>
                Click a block to view GP-wise information
            </small>

        </div>

    </div>


    <div class="district-card">


        <div class="district-card-header">

            <h6>

                <i class="fas fa-list mr-2"></i>

                Block-wise Building Information Status

            </h6>

        </div>


        <div class="table-responsive">

            <table class="table district-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Block</th>

                        <th class="text-center">
                            GPs
                        </th>

                        <th class="text-center">
                            Buildings
                        </th>

                        <th class="text-center">
                            Started
                        </th>

                        <th class="text-center">
                            Frozen
                        </th>

                        <th class="text-center">
                            Not Started
                        </th>

                        <th>
                            Progress
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse(
                        $blocks
                        as $key => $block
                    )

                        <tr>

                            <td>
                                {{ $key + 1 }}
                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'state.asset-report.block',
                                        [
                                            'district' =>
                                                $district,
                                            'block' =>
                                                $block->block_name
                                        ]
                                    ) }}"
                                    class="block-link"
                                >

                                    <span class="block-icon">

                                        <i class="
                                            fas
                                            fa-sitemap
                                        "></i>

                                    </span>


                                    <span>
                                        {{ $block->block_name }}
                                    </span>


                                    <i class="
                                        fas
                                        fa-chevron-right
                                        block-arrow
                                    "></i>

                                </a>

                            </td>


                            <td class="text-center">

                                <strong>
                                    {{ number_format(
                                        $block->total_gps
                                    ) }}
                                </strong>

                            </td>


                            <td class="text-center">

                                <strong>
                                    {{ number_format(
                                        $block->total_buildings
                                    ) }}
                                </strong>

                            </td>


                            <td class="text-center">

                                <span class="
                                    status-badge
                                    status-started
                                ">

                                    {{ number_format(
                                        $block->data_started
                                    ) }}

                                </span>

                            </td>


                            <td class="text-center">

                                <span class="
                                    status-badge
                                    status-frozen
                                ">

                                    {{ number_format(
                                        $block->data_frozen
                                    ) }}

                                </span>

                            </td>


                            <td class="text-center">

                                <strong class="text-danger">

                                    {{ number_format(
                                        $block->not_started
                                    ) }}

                                </strong>

                            </td>


                            <td>

                                <div style="min-width:130px;">

                                    <div class="progress">

                                        <div
                                            class="
                                                progress-bar
                                                bg-primary
                                            "
                                            style="
                                                width:
                                                {{ $block->progress }}%;
                                            "
                                        ></div>

                                    </div>


                                    <small
                                        class="text-muted"
                                        style="font-size:10px;"
                                    >

                                        {{ $block->progress }}%
                                        GPs started

                                    </small>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="
                                    text-center
                                    text-muted
                                    py-5
                                "
                            >

                                No block data available.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


</div>

@endsection