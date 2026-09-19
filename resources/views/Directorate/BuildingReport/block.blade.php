@extends('layouts.dir')

@section('main')

<style>
    .block-page {
        background: #f4f7fb;
        min-height: calc(100vh - 70px);
        padding: 25px;
    }

    /* Header */
    .block-header {
        background: linear-gradient(135deg, #263238, #455a64);
        border-radius: 16px;
        padding: 25px 30px;
        color: #fff;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
    }

    .block-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 26px;
    }

    .block-header p {
        margin: 7px 0 0;
        opacity: .85;
        font-size: 14px;
    }

    /* Breadcrumb */
    .breadcrumb-box {
        margin-bottom: 20px;
        font-size: 14px;
    }

    .breadcrumb-box a {
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-box span {
        margin: 0 7px;
        color: #9aa0a6;
    }

    /* Summary cards */
    .summary-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        min-height: 125px;
        box-shadow: 0 5px 18px rgba(0,0,0,.06);
        border: 1px solid #edf0f4;
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .summary-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
        background: #eef3f8;
        color: #455a64;
    }

    .summary-card h6 {
        color: #7a8490;
        font-size: 13px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .summary-card h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #263238;
    }

    /* Section */
    .report-section {
        background: #fff;
        border-radius: 15px;
        padding: 22px;
        margin-bottom: 25px;
        box-shadow: 0 5px 18px rgba(0,0,0,.05);
        border: 1px solid #edf0f4;
    }

    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title h5 {
        margin: 0;
        font-weight: 700;
        color: #263238;
    }

    .section-title small {
        color: #8a949e;
    }

    /* Analysis cards */
    .analysis-card {
        background: #f8fafc;
        border-radius: 13px;
        padding: 18px;
        border: 1px solid #edf0f4;
        height: 100%;
    }

    .analysis-card h6 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 16px;
        color: #37474f;
    }

    .analysis-number {
        font-size: 25px;
        font-weight: 700;
        color: #263238;
    }

    .analysis-label {
        font-size: 12px;
        color: #7c8791;
    }

    /* Progress */
    .progress {
        height: 9px;
        border-radius: 10px;
        background: #e9edf2;
        margin-top: 8px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .progress-row {
        margin-bottom: 18px;
    }

    .progress-row:last-child {
        margin-bottom: 0;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        color: #4b5661;
    }

    /* GP table */
    .gp-table {
        margin-bottom: 0;
    }

    .gp-table thead th {
        background: #f5f7fa;
        color: #59636d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .3px;
        border: 0;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .gp-table tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-color: #eef1f4;
        font-size: 14px;
    }

    .gp-link {
        color: #1565c0;
        text-decoration: none;
        font-weight: 700;
    }

    .gp-link:hover {
        text-decoration: underline;
    }

    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-started {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-not-started {
        background: #fff3e0;
        color: #ef6c00;
    }

    .status-frozen {
        background: #e3f2fd;
        color: #1565c0;
    }

    .status-draft {
        background: #fff8e1;
        color: #f57f17;
    }

    /* Empty */
    .empty-box {
        text-align: center;
        padding: 40px 20px;
        color: #89939d;
    }

    .empty-box i {
        font-size: 35px;
        margin-bottom: 10px;
    }

    /* Back button */
    .back-btn {
        color: #fff;
        border: 1px solid rgba(255,255,255,.4);
        border-radius: 8px;
        padding: 7px 14px;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
        margin-top: 15px;
    }

    .back-btn:hover {
        color: #fff;
        background: rgba(255,255,255,.1);
    }

    @media(max-width: 768px) {

        .block-page {
            padding: 15px;
        }

        .block-header {
            padding: 20px;
        }

        .block-header h2 {
            font-size: 21px;
        }

        .gp-table {
            min-width: 850px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }
</style>


<div class="block-page">

    {{-- Header --}}
    <div class="block-header">

        <div class="breadcrumb-box">

            <a href="{{ route('state.asset-report.dashboard') }}"
               style="color:#fff;">
                State
            </a>

            <span style="color:#cfd8dc;">/</span>

            <a href="{{ route('state.asset-report.district', ['district' => $district]) }}"
               style="color:#fff;">
                {{ $district }}
            </a>

            <span style="color:#cfd8dc;">/</span>

            <strong>{{ $block }}</strong>

        </div>

        <h2>
            <i class="fas fa-layer-group me-2"></i>
            {{ $block }} – Building Asset Report
        </h2>

        <p>
            District: {{ $district }}
        </p>

        <a href="{{ route('state.asset-report.district', ['district' => $district]) }}"
           class="back-btn">
            <i class="fas fa-arrow-left me-1"></i>
            Back to District
        </a>

    </div>


    {{-- Summary Cards --}}
    <div class="row">

        {{-- Total GPs --}}
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">

                <div class="icon">
                    <i class="fas fa-landmark"></i>
                </div>

                <h6>Total Gram Panchayats</h6>

                <h3>
                    {{ number_format($totalGps) }}
                </h3>

            </div>
        </div>


        {{-- Total Buildings --}}
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">

                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>

                <h6>Total Buildings</h6>

                <h3>
                    {{ number_format($totalBuildings) }}
                </h3>

            </div>
        </div>


        {{-- Started --}}
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">

                <div class="icon">
                    <i class="fas fa-edit"></i>
                </div>

                <h6>GPs Data Entry Started</h6>

                <h3>
                    {{ number_format($dataStartedGps) }}
                </h3>

            </div>
        </div>


        {{-- Frozen --}}
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">

                <div class="icon">
                    <i class="fas fa-lock"></i>
                </div>

                <h6>GPs Data Frozen</h6>

                <h3>
                    {{ number_format($frozenGps) }}
                </h3>

            </div>
        </div>

    </div>


    {{-- Progress Summary --}}
    <div class="report-section">

        <div class="section-title">

            <h5>
                <i class="fas fa-chart-line me-2"></i>
                Data Entry Progress
            </h5>

            <small>
                {{ number_format($dataStartedGps) }}
                of
                {{ number_format($totalGps) }}
                GPs started
            </small>

        </div>


        {{-- Started --}}
        <div class="progress-row">

            <div class="progress-info">

                <span>
                    Data Entry Started
                </span>

                <span>
                    {{ $startedPercentage }}%
                </span>

            </div>

            <div class="progress">

                <div class="progress-bar"
                     role="progressbar"
                     style="width: {{ $startedPercentage }}%;">
                </div>

            </div>

        </div>


        {{-- Frozen --}}
        <div class="progress-row">

            <div class="progress-info">

                <span>
                    Data Frozen
                </span>

                <span>
                    {{ $frozenPercentage }}%
                </span>

            </div>

            <div class="progress">

                <div class="progress-bar"
                     role="progressbar"
                     style="width: {{ $frozenPercentage }}%;">
                </div>

            </div>

        </div>


        {{-- Not Started --}}
        <div class="progress-row">

            <div class="progress-info">

                <span>
                    Data Entry Not Started
                </span>

                <span>
                    {{ $notStartedPercentage }}%
                </span>

            </div>

            <div class="progress">

                <div class="progress-bar"
                     role="progressbar"
                     style="width: {{ $notStartedPercentage }}%;">
                </div>

            </div>

        </div>

    </div>


    {{-- Building Category Analysis --}}
    <div class="report-section">

        <div class="section-title">

            <h5>
                <i class="fas fa-th-large me-2"></i>
                Building Category-wise Analysis
            </h5>

            <small>
                Total Buildings:
                {{ number_format($totalBuildings) }}
            </small>

        </div>


        <div class="row">

            @php
                $categoryIcons = [
                    'Official' => 'fa-landmark',
                    'Community' => 'fa-users',
                    'Commercial' => 'fa-store',
                ];
            @endphp


            @foreach($categoryAnalysis as $category => $total)

                @php
                    $percentage = $totalBuildings > 0
                        ? round(($total / $totalBuildings) * 100, 1)
                        : 0;
                @endphp

                <div class="col-lg-4 col-md-6 mb-3">

                    <div class="analysis-card">

                        <h6>

                            <i class="fas {{ $categoryIcons[$category] ?? 'fa-building' }} me-2"></i>

                            {{ $category }}

                        </h6>

                        <div class="analysis-number">
                            {{ number_format($total) }}
                        </div>

                        <div class="analysis-label">
                            Buildings
                        </div>

                        <div class="progress">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%;">
                            </div>

                        </div>

                        <small class="text-muted">
                            {{ $percentage }}% of total buildings
                        </small>

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- Panchayat Ghar --}}
    <div class="report-section">

        <div class="section-title">

            <h5>
                <i class="fas fa-home me-2"></i>
                Panchayat Ghar Analysis
            </h5>

            <small>
                Based on Gram Panchayats having Panchayat Ghar entry
            </small>

        </div>


        <div class="row">

            {{-- Total --}}
            <div class="col-md-4 mb-3">

                <div class="analysis-card">

                    <h6>Total GPs</h6>

                    <div class="analysis-number">
                        {{ number_format($panchayatGharAnalysis['total']) }}
                    </div>

                    <div class="analysis-label">
                        Having Panchayat Ghar entry
                    </div>

                </div>

            </div>


            {{-- Owned --}}
            <div class="col-md-4 mb-3">

                <div class="analysis-card">

                    <h6>Owned by GP</h6>

                    <div class="analysis-number">
                        {{ number_format($panchayatGharAnalysis['owned']) }}
                    </div>

                    <div class="analysis-label">
                        Panchayat Ghar owned by Gram Panchayat
                    </div>

                </div>

            </div>


            {{-- Rented --}}
            <div class="col-md-4 mb-3">

                <div class="analysis-card">

                    <h6>Taken on Rent</h6>

                    <div class="analysis-number">
                        {{ number_format($panchayatGharAnalysis['rented']) }}
                    </div>

                    <div class="analysis-label">
                        Panchayat Ghar taken on rent
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Ownership --}}
    <div class="report-section">

        <div class="section-title">

            <h5>
                <i class="fas fa-user-shield me-2"></i>
                Building Ownership
            </h5>

            <small>
                Ownership-wise building distribution
            </small>

        </div>


        <div class="row">

            @foreach($ownershipAnalysis as $ownership => $total)

                @php
                    $percentage = $totalBuildings > 0
                        ? round(($total / $totalBuildings) * 100, 1)
                        : 0;
                @endphp

                <div class="col-lg-4 col-md-6 mb-3">

                    <div class="analysis-card">

                        <h6>
                            {{ $ownership }}
                        </h6>

                        <div class="analysis-number">
                            {{ number_format($total) }}
                        </div>

                        <div class="analysis-label">
                            Buildings
                        </div>

                        <div class="progress">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%;">
                            </div>

                        </div>

                        <small class="text-muted">
                            {{ $percentage }}%
                        </small>

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- GP-wise Report --}}
    <div class="report-section">

        <div class="section-title">

            <div>

                <h5>
                    <i class="fas fa-list me-2"></i>
                    Gram Panchayat-wise Report
                </h5>

                <small>
                    Click on a Gram Panchayat name to view building details
                </small>

            </div>

            <span class="badge bg-secondary">
                {{ number_format($totalGps) }} GPs
            </span>

        </div>


        <div class="table-responsive">

            <table class="table gp-table">

                <thead>

                    <tr>

                        <th width="7%">
                            #
                        </th>

                        <th>
                            Gram Panchayat
                        </th>

                        <th class="text-center">
                            Buildings
                        </th>

                        <th class="text-center">
                            Data Entry
                        </th>

                        <th class="text-center">
                            Final Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($gps as $index => $gp)

                        <tr>

                            {{-- Serial --}}
                            <td>
                                {{ $index + 1 }}
                            </td>


                            {{-- GP --}}
                            <td>

                                <a href="{{ route(
                                    'state.asset-report.gp',
                                    [
                                        'district' => $district,
                                        'block' => $block,
                                        'gp' => $gp->gp_id
                                    ]
                                ) }}"
                                class="gp-link">

                                    <i class="fas fa-landmark me-2"></i>

                                    {{ $gp->gp_name }}

                                </a>

                            </td>


                            {{-- Buildings --}}
                            <td class="text-center">

                                <strong>
                                    {{ number_format($gp->total_buildings) }}
                                </strong>

                            </td>


                            {{-- Data Entry --}}
                            <td class="text-center">

                                @if($gp->is_started)

                                    <span class="status-badge status-started">

                                        <i class="fas fa-check me-1"></i>

                                        Started

                                    </span>

                                @else

                                    <span class="status-badge status-not-started">

                                        <i class="fas fa-clock me-1"></i>

                                        Not Started

                                    </span>

                                @endif

                            </td>


                            {{-- Final Status --}}
                            <td class="text-center">

                                @if($gp->is_frozen)

                                    <span class="status-badge status-frozen">

                                        <i class="fas fa-lock me-1"></i>

                                        Frozen

                                    </span>

                                @elseif($gp->is_started)

                                    <span class="status-badge status-draft">

                                        <i class="fas fa-edit me-1"></i>

                                        Draft

                                    </span>

                                @else

                                    <span class="status-badge status-not-started">

                                        <i class="fas fa-minus-circle me-1"></i>

                                        Not Started

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="empty-box">

                                    <i class="fas fa-folder-open"></i>

                                    <div>
                                        No Gram Panchayat data available.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection