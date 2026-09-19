@extends('layouts.dir')

@section('main')

<style>

/* =========================================================
   STATE PRI BUILDING DASHBOARD
   ========================================================= */

.asset-dashboard {
    background: #f5f7fb;
    min-height: calc(100vh - 70px);
    padding: 25px;
}


/* ---------------------------------------------------------
   Dashboard Header
--------------------------------------------------------- */

.dashboard-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border-radius: 15px;
    padding: 25px 30px;
    color: #fff;
    margin-bottom: 25px;
    box-shadow: 0 8px 25px rgba(78, 115, 223, 0.20);
}

.dashboard-header h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.dashboard-header p {
    margin-bottom: 0;
    font-size: 13px;
    opacity: 0.90;
}


/* ---------------------------------------------------------
   Section Title
--------------------------------------------------------- */

.dashboard-section-title {
    display: flex;
    align-items: center;
    margin: 28px 0 15px;
}

.dashboard-section-title .section-icon {
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

.dashboard-section-title h5 {
    margin: 0;
    font-weight: 700;
    color: #343a40;
}

.dashboard-section-title small {
    display: block;
    color: #858796;
    margin-top: 2px;
}


/* ---------------------------------------------------------
   Summary Cards
--------------------------------------------------------- */

.summary-card {
    background: #fff;
    border: 0;
    border-radius: 14px;
    padding: 20px;
    height: 100%;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.10);
}

.summary-card::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    right: -25px;
    top: -25px;
    border-radius: 50%;
    background: rgba(78, 115, 223, 0.06);
}

.summary-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 18px;
}

.summary-card h3 {
    font-size: 25px;
    font-weight: 700;
    margin-bottom: 3px;
    color: #343a40;
}

.summary-card p {
    margin: 0;
    font-size: 12px;
    color: #858796;
    font-weight: 600;
}


/* Card icon backgrounds */

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

.icon-red {
    background: #fdeaea;
    color: #e74a3b;
}

.icon-purple {
    background: #f0eafd;
    color: #6f42c1;
}

.icon-teal {
    background: #e7f8f7;
    color: #20c9a6;
}


/* ---------------------------------------------------------
   Analysis Cards
--------------------------------------------------------- */

.analysis-card {
    background: #fff;
    border: 0;
    border-radius: 15px;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
    height: 100%;
}

.analysis-card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f5;
}

.analysis-card-header h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #343a40;
}

.analysis-card-header small {
    color: #858796;
}

.analysis-card-body {
    padding: 20px;
}


/* ---------------------------------------------------------
   Building Category List
--------------------------------------------------------- */

.category-row {
    margin-bottom: 18px;
}

.category-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.category-label span:first-child {
    font-size: 12px;
    font-weight: 600;
    color: #555;
}

.category-label span:last-child {
    font-size: 12px;
    font-weight: 700;
    color: #343a40;
}

.progress {
    height: 9px;
    border-radius: 20px;
    background: #edf0f5;
}

.progress-bar {
    border-radius: 20px;
}


/* ---------------------------------------------------------
   Statistics
--------------------------------------------------------- */

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

.mini-stat-content {
    flex: 1;
}

.mini-stat-content strong {
    display: block;
    font-size: 17px;
    color: #343a40;
}

.mini-stat-content span {
    font-size: 11px;
    color: #858796;
}


/* ---------------------------------------------------------
   Panchayat Ghar Analysis
--------------------------------------------------------- */

.office-analysis {
    text-align: center;
    padding: 10px;
}

.office-circle {
    width: 115px;
    height: 115px;
    border-radius: 50%;
    margin: 5px auto 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eef2ff;
    border: 10px solid #dce5ff;
}

.office-circle span {
    font-size: 24px;
    font-weight: 700;
    color: #4e73df;
}

.office-analysis h6 {
    font-weight: 700;
    margin-bottom: 3px;
}

.office-analysis small {
    color: #858796;
}


/* ---------------------------------------------------------
   District Table
--------------------------------------------------------- */

.district-card {
    background: #fff;
    border-radius: 15px;
    border: 0;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.district-card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f5;

    display: flex;
    align-items: center;
    justify-content: space-between;
}

.district-card-header h6 {
    margin: 0;
    font-weight: 700;
}

.district-table {
    margin-bottom: 0;
}

.district-table thead th {
    background: #f8f9fc;
    color: #6c757d;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    border: 0;
    padding: 13px 15px;
    white-space: nowrap;
}

.district-table tbody td {
    padding: 13px 15px;
    font-size: 12px;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.district-name {
    font-weight: 700;
    color: #343a40;
}

.district-name a {
    color: #4e73df;
    text-decoration: none;
}

.district-name a:hover {
    text-decoration: underline;
}


/* Status badges */

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


/* District progress */

.district-progress {
    min-width: 130px;
}

.district-progress .progress {
    height: 7px;
}

.district-progress-text {
    font-size: 10px;
    color: #858796;
    margin-top: 4px;
}


/* ---------------------------------------------------------
   View Button
--------------------------------------------------------- */

.view-btn {
    border-radius: 7px;
    font-size: 11px;
    padding: 5px 10px;
}


.district-link {
    display: inline-flex;
    align-items: center;
    text-decoration: none !important;
    color: #303645;
    font-weight: 700;
    transition: all .2s ease;
}

.district-link:hover {
    color: #4169d8;
    transform: translateX(3px);
}

.district-link-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: #edf2ff;
    color: #4169d8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 9px;
    font-size: 12px;
}

.district-link-text {
    font-weight: 700;
}

.district-arrow {
    margin-left: 9px;
    font-size: 9px;
    color: #a0a6b2;
    transition: transform .2s ease;
}

.district-link:hover .district-arrow {
    transform: translateX(4px);
    color: #4169d8;
}

/* ---------------------------------------------------------
   Responsive
--------------------------------------------------------- */

@media (max-width: 768px) {

    .asset-dashboard {
        padding: 15px;
    }

    .dashboard-header {
        padding: 20px;
    }

    .dashboard-header h2 {
        font-size: 20px;
    }

    .summary-card {
        margin-bottom: 15px;
    }

}

</style>


<div class="asset-dashboard">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="dashboard-header">

        <div class="d-flex align-items-center">

            <div class="mr-3"
                 style="
                    width:50px;
                    height:50px;
                    border-radius:13px;
                    background:rgba(255,255,255,.16);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                 ">

                <i class="fas fa-building fa-lg"></i>

            </div>

            <div>

                <h2>
                    PRI Building Information Dashboard
                </h2>

                <p>
                    State Level Monitoring of Building Information
                    submitted by Gram Panchayats
                </p>

            </div>

        </div>

    </div>


    <!-- =====================================================
         OVERALL SUMMARY
    ====================================================== -->

    <div class="dashboard-section-title">

        <div class="section-icon">
            <i class="fas fa-chart-line"></i>
        </div>

        <div>
            <h5>Overall Status</h5>
            <small>State-wide PRI building information</small>
        </div>

    </div>


    <div class="row">

        <!-- Districts -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-blue">
                    <i class="fas fa-map-marked-alt"></i>
                </div>

                <h3>{{ $totalDistricts ?? 0 }}</h3>

                <p>Total Districts</p>

            </div>

        </div>


        <!-- Blocks -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-purple">
                    <i class="fas fa-sitemap"></i>
                </div>

                <h3>{{ $totalBlocks ?? 0 }}</h3>

                <p>Total Blocks</p>

            </div>

        </div>


        <!-- GPs -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-teal">
                    <i class="fas fa-home"></i>
                </div>

                <h3>{{ $totalGps ?? 0 }}</h3>

                <p>Total Gram Panchayats</p>

            </div>

        </div>


        <!-- Buildings -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-blue">
                    <i class="fas fa-building"></i>
                </div>

                <h3>{{ $totalBuildings ?? 0 }}</h3>

                <p>Total Buildings</p>

            </div>

        </div>


        <!-- Started -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-orange">
                    <i class="fas fa-edit"></i>
                </div>

                <h3>{{ $dataStartedGps ?? 0 }}</h3>

                <p>GPs Data Entry Started</p>

            </div>

        </div>


        <!-- Frozen -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">

            <div class="summary-card">

                <div class="summary-icon icon-green">
                    <i class="fas fa-lock"></i>
                </div>

                <h3>{{ $frozenGps ?? 0 }}</h3>

                <p>GPs Data Frozen</p>

            </div>

        </div>

    </div>



    <!-- =====================================================
         BUILDING ANALYSIS
    ====================================================== -->

    <div class="dashboard-section-title">

        <div class="section-icon">
            <i class="fas fa-chart-pie"></i>
        </div>

        <div>

            <h5>Building Analysis</h5>

            <small>
                Category-wise analysis of buildings reported by PRIs
            </small>

        </div>

    </div>


    <div class="row">


        <!-- Building Category -->
        <div class="col-lg-7 mb-4">

            <div class="analysis-card">

                <div class="analysis-card-header">

                    <h6>
                        <i class="fas fa-layer-group mr-2"></i>
                        Building Category-wise
                    </h6>

                    <small>
                        Number of buildings reported
                    </small>

                </div>


                <div class="analysis-card-body">

                    @php

                        /*
                         * These values will be supplied by the
                         * controller later.
                         *
                         * Example:
                         *
                         * $categoryAnalysis = [
                         *     'Official' => 250,
                         *     'Community' => 850,
                         *     'Commercial' => 420
                         * ];
                         */

                        $categoryAnalysis = $categoryAnalysis ?? [
                            'Official' => 0,
                            'Community' => 0,
                            'Commercial' => 0
                        ];

                        $categoryTotal = array_sum($categoryAnalysis);

                    @endphp


                    @foreach($categoryAnalysis as $category => $count)

                        @php
                            $percentage = $categoryTotal > 0
                                ? round(($count / $categoryTotal) * 100, 1)
                                : 0;
                        @endphp

                        <div class="category-row">

                            <div class="category-label">

                                <span>
                                    @if($category == 'Official')
                                        <i class="fas fa-landmark mr-1"></i>
                                    @elseif($category == 'Community')
                                        <i class="fas fa-users mr-1"></i>
                                    @else
                                        <i class="fas fa-store mr-1"></i>
                                    @endif

                                    {{ $category }}
                                </span>

                                <span>
                                    {{ number_format($count) }}
                                    ({{ $percentage }}%)
                                </span>

                            </div>


                            <div class="progress">

                                <div class="progress-bar bg-primary"
                                     role="progressbar"
                                     style="width: {{ $percentage }}%">
                                </div>

                            </div>

                        </div>

                    @endforeach


                    <div class="text-center mt-4">

                        <span class="text-muted"
                              style="font-size:11px;">

                            Total Buildings:
                            <strong>
                                {{ number_format($categoryTotal) }}
                            </strong>

                        </span>

                    </div>

                </div>

            </div>

        </div>



        <!-- Panchayat Ghar Analysis -->
        <div class="col-lg-5 mb-4">

            <div class="analysis-card">

                <div class="analysis-card-header">

                    <h6>
                        <i class="fas fa-home mr-2"></i>
                        Panchayat Ghar Status
                    </h6>

                    <small>
                        Status of Panchayat Ghar reported by GPs
                    </small>

                </div>


                <div class="analysis-card-body">

                    @php

                        $totalPanchayatGharGps =
                            $panchayatGharAnalysis['total'] ?? 0;

                        $ownedPanchayatGhar =
                            $panchayatGharAnalysis['owned'] ?? 0;

                        $rentedPanchayatGhar =
                            $panchayatGharAnalysis['rented'] ?? 0;

                        $ownedPercentage =
                            $totalPanchayatGharGps > 0
                            ? round(
                                ($ownedPanchayatGhar /
                                $totalPanchayatGharGps) * 100,
                                1
                            )
                            : 0;

                    @endphp


                    <div class="office-analysis">

                        <div class="office-circle">

                            <span>
                                {{ $ownedPercentage }}%
                            </span>

                        </div>

                        <h6>
                            Panchayat Ghar Owned
                        </h6>

                        <small>
                            {{ number_format($ownedPanchayatGhar) }}
                            out of
                            {{ number_format($totalPanchayatGharGps) }}
                            reported
                        </small>

                    </div>


                    <div class="row mt-3">

                        <div class="col-6">

                            <div class="mini-stat">

                                <div class="mini-stat-icon icon-green">

                                    <i class="fas fa-home"></i>

                                </div>

                                <div class="mini-stat-content">

                                    <strong>
                                        {{ number_format($ownedPanchayatGhar) }}
                                    </strong>

                                    <span>
                                        Owned
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="mini-stat">

                                <div class="mini-stat-icon icon-orange">

                                    <i class="fas fa-key"></i>

                                </div>

                                <div class="mini-stat-content">

                                    <strong>
                                        {{ number_format($rentedPanchayatGhar) }}
                                    </strong>

                                    <span>
                                        Taken on Rent
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
         OWNERSHIP ANALYSIS
    ====================================================== -->

    <div class="row">

        <div class="col-lg-5 mb-4">

            <div class="analysis-card">

                <div class="analysis-card-header">

                    <h6>
                        <i class="fas fa-user-shield mr-2"></i>
                        Building Ownership
                    </h6>

                    <small>
                        Ownership of buildings reported by PRIs
                    </small>

                </div>


                <div class="analysis-card-body">

                    @php

                        $ownershipAnalysis = $ownershipAnalysis ?? [
                            'Gram Panchayat' => 0,
                            'Panchayat Samiti' => 0,
                            'Zila Parishad' => 0
                        ];

                    @endphp


                    @foreach($ownershipAnalysis as $owner => $count)

                        <div class="mini-stat">

                            <div class="mini-stat-icon icon-blue">

                                @if($owner == 'Gram Panchayat')
                                    <i class="fas fa-home"></i>
                                @elseif($owner == 'Panchayat Samiti')
                                    <i class="fas fa-sitemap"></i>
                                @else
                                    <i class="fas fa-landmark"></i>
                                @endif

                            </div>


                            <div class="mini-stat-content">

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



        <!-- Data Entry Progress -->
        <div class="col-lg-7 mb-4">

            <div class="analysis-card">

                <div class="analysis-card-header">

                    <h6>
                        <i class="fas fa-tasks mr-2"></i>
                        Data Entry Progress
                    </h6>

                    <small>
                        Overall status of Gram Panchayats
                    </small>

                </div>


                <div class="analysis-card-body">

                    @php

                        $totalGpsValue = $totalGps ?? 0;

                        $startedValue = $dataStartedGps ?? 0;

                        $frozenValue = $frozenGps ?? 0;

                        $notStartedValue =
                            max(
                                0,
                                $totalGpsValue - $startedValue
                            );

                        $startedPercentage =
                            $totalGpsValue > 0
                            ? round(
                                ($startedValue /
                                $totalGpsValue) * 100,
                                1
                            )
                            : 0;

                        $frozenPercentage =
                            $totalGpsValue > 0
                            ? round(
                                ($frozenValue /
                                $totalGpsValue) * 100,
                                1
                            )
                            : 0;

                    @endphp


                    <!-- Started -->

                    <div class="category-row">

                        <div class="category-label">

                            <span>
                                <i class="fas fa-edit mr-1"></i>
                                Data Entry Started
                            </span>

                            <span>
                                {{ number_format($startedValue) }}
                                / {{ number_format($totalGpsValue) }}
                            </span>

                        </div>


                        <div class="progress">

                            <div class="progress-bar bg-warning"
                                 style="width: {{ $startedPercentage }}%">
                            </div>

                        </div>

                    </div>


                    <!-- Frozen -->

                    <div class="category-row">

                        <div class="category-label">

                            <span>
                                <i class="fas fa-lock mr-1"></i>
                                Data Frozen
                            </span>

                            <span>
                                {{ number_format($frozenValue) }}
                                / {{ number_format($totalGpsValue) }}
                            </span>

                        </div>


                        <div class="progress">

                            <div class="progress-bar bg-success"
                                 style="width: {{ $frozenPercentage }}%">
                            </div>

                        </div>

                    </div>


                    <!-- Not Started -->

                    <div class="category-row">

                        <div class="category-label">

                            <span>
                                <i class="fas fa-clock mr-1"></i>
                                Data Entry Not Started
                            </span>

                            <span>
                                {{ number_format($notStartedValue) }}
                            </span>

                        </div>


                        <div class="progress">

                            <div class="progress-bar bg-danger"
                                 style="
                                    width:
                                    {{ $totalGpsValue > 0
                                        ? round(
                                            ($notStartedValue /
                                            $totalGpsValue) * 100,
                                            1
                                        )
                                        : 0
                                    }}%">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         DISTRICT-WISE STATUS
    ====================================================== -->

    <div class="dashboard-section-title">

        <div class="section-icon">
            <i class="fas fa-map-marked-alt"></i>
        </div>

        <div>

            <h5>District-wise Progress</h5>

            <small>
                Gram Panchayat data entry and final submission status
            </small>

        </div>

    </div>


    <div class="district-card">

        <div class="district-card-header">

            <div>

                <h6>
                    District-wise Building Information Status
                </h6>

            </div>

            <span class="badge badge-primary">
                State Level
            </span>

        </div>


        <div class="table-responsive">

            <table class="table district-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>District</th>

                        <th class="text-center">
                            Blocks
                        </th>

                        <th class="text-center">
                            Total GPs
                        </th>

                        <th class="text-center">
                            Data Started
                        </th>

                        <th class="text-center">
                            Data Frozen
                        </th>

                        <th class="text-center">
                            Not Started
                        </th>

                        <th>
                            Progress
                        </th>

                        <th class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($districts ?? [] as $key => $district)

                        @php

                            $districtTotal =
                                $district->total_gps ?? 0;

                            $districtStarted =
                                $district->data_started ?? 0;

                            $districtFrozen =
                                $district->data_frozen ?? 0;

                            $districtNotStarted =
                                max(
                                    0,
                                    $districtTotal -
                                    $districtStarted
                                );

                            $districtPercentage =
                                $districtTotal > 0
                                ? round(
                                    ($districtStarted /
                                    $districtTotal) * 100,
                                    1
                                )
                                : 0;

                        @endphp


                        <tr>

                            <td>
                                {{ $key + 1 }}
                            </td>

                    <td>
                        <a href="{{ route('state.asset-report.district',['district' => $district->district_name]) }}"
                        class="district-link">

                            <span class="district-link-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>

                            <span class="district-link-text">
                                {{ $district->district_name }}
                            </span>

                            <i class="fas fa-chevron-right district-arrow"></i>

                        </a>
                    </td>


                            <td class="text-center">

                                <strong>
                                    {{ $district->total_blocks ?? 0 }}
                                </strong>

                            </td>


                            <td class="text-center">

                                <strong>
                                    {{ number_format($districtTotal) }}
                                </strong>

                            </td>


                            <td class="text-center">

                                <span class="status-badge status-started">

                                    <i class="fas fa-edit mr-1"></i>

                                    {{ number_format($districtStarted) }}

                                </span>

                            </td>


                            <td class="text-center">

                                <span class="status-badge status-frozen">

                                    <i class="fas fa-lock mr-1"></i>

                                    {{ number_format($districtFrozen) }}

                                </span>

                            </td>


                            <td class="text-center">

                                <span class="text-danger font-weight-bold">

                                    {{ number_format($districtNotStarted) }}

                                </span>

                            </td>


                            <td>

                                <div class="district-progress">

                                    <div class="progress">

                                        <div class="progress-bar bg-primary"
                                             style="
                                                width:
                                                {{ $districtPercentage }}%;
                                             ">
                                        </div>

                                    </div>

                                    <div class="district-progress-text">

                                        {{ $districtPercentage }}%
                                        GPs started

                                    </div>

                                </div>

                            </td>


                            <td class="text-center">

                                {{-- <a href="{{ route(
                                    'state.asset-report.district',
                                    $district->district_name
                                ) }}"
                                   class="btn btn-primary btn-sm view-btn">

                                    <i class="fas fa-eye mr-1"></i>

                                    View

                                </a> --}}

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-database fa-2x mb-3"></i>

                                    <br>

                                    No district data available.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


            </table>
            <a href="{{ route('state.asset-report.export') }}"
   class="btn btn-success"
   style="font-weight:600; border-radius:8px; padding:9px 16px; margin-top:15px;">

    <i class="fas fa-file-csv me-2"></i>
    Download Building Details
</a>

        </div>

    </div>


</div>

@endsection