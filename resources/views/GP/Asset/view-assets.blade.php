@extends('layouts.gp')

@section('main')

<style>
    .asset-page {
        padding: 25px 30px 40px;
        background: #f6f8fb;
        min-height: calc(100vh - 80px);
    }

    .page-header {
        background: #ffffff;
        border-radius: 12px;
        padding: 22px 25px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .page-title {
        font-size: 23px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .gp-info {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid #edf0f3;
    }

    .gp-info-item {
        min-width: 150px;
    }

    .gp-info-label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #8a94a6;
        margin-bottom: 3px;
    }

    .gp-info-value {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    /* Summary Cards */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .035);
    }

    .summary-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: #eef2ff;
        color: #4f46e5;
    }

    .summary-card:nth-child(2) .summary-icon {
        background: #ecfdf5;
        color: #059669;
    }

    .summary-card:nth-child(3) .summary-icon {
        background: #fff7ed;
        color: #ea580c;
    }

    .summary-card:nth-child(4) .summary-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .summary-number {
        font-size: 23px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    .summary-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    /* Main Card */

    .building-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .035);
    }

    .building-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .building-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #374151;
        margin: 0;
    }

    .building-count {
        font-size: 12px;
        background: #f3f4f6;
        color: #4b5563;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .add-building-btn {
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 13px;
    }

    /* Table */

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .building-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .building-table th {
        background: #f9fafb;
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 700;
        padding: 12px 15px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .building-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #f0f1f3;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .building-table tbody tr:hover {
        background: #fafbfc;
    }

    .building-name {
        font-weight: 650;
        color: #1f2937;
    }

    .building-subcategory {
        font-size: 11px;
        color: #8a94a6;
        margin-top: 3px;
    }

    /* Badges */

    .category-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .category-official {
        background: #eef2ff;
        color: #4338ca;
    }

    .category-community {
        background: #ecfdf5;
        color: #047857;
    }

    .category-commercial {
        background: #fff7ed;
        color: #c2410c;
    }

    .ownership-text {
        font-size: 12px;
        color: #4b5563;
    }

    /* Buttons */

    .action-buttons {
        display: flex;
        gap: 6px;
        align-items: center;
        white-space: nowrap;
    }

    .action-buttons .btn {
        font-size: 11px;
        padding: 5px 8px;
        border-radius: 5px;
    }

    .photo-link {
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    /* Empty */

    .empty-state {
        text-align: center;
        padding: 55px 20px;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f3f4f6;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
    }

    .empty-state h5 {
        color: #374151;
        font-weight: 650;
        margin-bottom: 5px;
    }

    .empty-state p {
        color: #8a94a6;
        font-size: 13px;
        margin-bottom: 18px;
    }

    /* Submission Section */

    .submission-card {
        margin-top: 20px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
    }

    .submission-title {
        font-size: 15px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 10px;
    }

    .certification-box {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 14px 15px;
        margin-bottom: 15px;
    }

    .certification-box label {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0;
        cursor: pointer;
        font-size: 13px;
        line-height: 1.5;
        color: #4b5563;
    }

    .certification-box input {
        margin-top: 3px;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .submit-area {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
    }

    .submit-warning {
        font-size: 12px;
        color: #9ca3af;
    }

    .freeze-btn {
        border-radius: 7px;
        font-weight: 600;
        padding: 9px 16px;
        font-size: 13px;
    }

    /* Frozen */

    .frozen-card {
        margin-top: 20px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .frozen-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .frozen-title {
        font-size: 15px;
        font-weight: 700;
        color: #166534;
        margin-bottom: 3px;
    }

    .frozen-text {
        font-size: 12px;
        color: #4b5563;
        margin: 0;
    }

    /* Alerts */

    .page-alert {
        margin-bottom: 15px;
    }

    /* Responsive */

    @media (max-width: 1000px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {
        .asset-page {
            padding: 15px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            padding: 18px;
        }

        .building-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .submit-area {
            align-items: flex-start;
            flex-direction: column;
        }

        .frozen-card {
            align-items: flex-start;
        }
    }

    /* =========================================
   FINAL SUBMISSION WARNING MODAL
   ========================================= */

.final-submit-modal {
    display: none;
    position: fixed;
    z-index: 99999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(17, 24, 39, 0.65);

    align-items: center;
    justify-content: center;

    padding: 20px;
}


/* Modal Box */

.final-submit-dialog {
    width: 100%;
    max-width: 570px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;

    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.25);

    animation: finalSubmitModalIn 0.2s ease-out;
}


@keyframes finalSubmitModalIn {

    from {
        opacity: 0;
        transform: translateY(-15px) scale(.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

}


/* Header */

.final-submit-header {
    background: #dc2626;
    color: #ffffff;

    padding: 18px 22px;

    display: flex;
    align-items: center;
    gap: 14px;
}


.warning-icon {
    width: 46px;
    height: 46px;

    border-radius: 50%;

    background: rgba(255, 255, 255, 0.18);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 21px;
}


.warning-title {
    font-size: 18px;
    font-weight: 700;
}


.warning-subtitle {
    font-size: 12px;
    margin-top: 2px;
    opacity: .9;
}


/* Body */

.final-submit-body {
    padding: 23px;
}


.warning-question {
    font-size: 15px;
    line-height: 1.55;
    color: #1f2937;

    margin-bottom: 18px;
}


.hindi-text {
    margin-top: 7px;
    color: #4b5563;
    font-size: 14px;
    line-height: 1.55;
}


/* Red Warning Box */

.danger-message {
    display: flex;
    gap: 13px;

    padding: 15px;

    background: #fef2f2;

    border: 1px solid #fecaca;

    border-left: 4px solid #dc2626;

    border-radius: 7px;

    color: #7f1d1d;

    margin-bottom: 15px;
}


.danger-message-icon {
    font-size: 18px;
    color: #dc2626;

    padding-top: 2px;
}


.danger-message strong {
    font-size: 13px;
}


.danger-message p {
    margin: 5px 0 0;

    font-size: 13px;

    line-height: 1.5;
}


/* Irreversible warning */

.irreversible-warning {

    display: flex;
    align-items: flex-start;

    gap: 10px;

    padding: 12px 14px;

    background: #fff7ed;

    border: 1px solid #fed7aa;

    border-radius: 7px;

    color: #9a3412;

    font-size: 13px;
}


.irreversible-warning > i {
    margin-top: 3px;
}


/* Footer */

.final-submit-footer {

    padding: 15px 22px;

    background: #f9fafb;

    border-top: 1px solid #e5e7eb;

    display: flex;

    justify-content: flex-end;

    align-items: center;

    gap: 10px;
}


.cancel-submit-btn {

    border: 1px solid #d1d5db;

    font-size: 13px;

    padding: 9px 15px;

    border-radius: 7px;
}


.confirm-submit-btn {

    font-size: 12px;

    font-weight: 600;

    line-height: 1.35;

    padding: 8px 16px;

    border-radius: 7px;

    min-width: 190px;
}


/* Mobile */

@media (max-width: 600px) {

    .final-submit-dialog {
        max-width: 100%;
    }

    .final-submit-footer {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .cancel-submit-btn,
    .confirm-submit-btn {
        width: 100%;
    }

}
</style>


<div class="asset-page">

    {{-- Success Message --}}
    @if(session('message'))
        <div class="alert alert-success page-alert">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('message') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger page-alert">
            <i class="fas fa-exclamation-circle mr-1"></i>

            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif


    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h1 class="page-title">
                <i class="fas fa-building mr-2"></i>
                PRI Buildings
            </h1>

            <p class="page-subtitle">
                View and manage buildings owned or managed by the Gram Panchayat.
            </p>
        </div>

        <div class="gp-info">

            <div class="gp-info-item">
                <span class="gp-info-label">District</span>
                <span class="gp-info-value">
                    {{ $gp->district_name ?? 'N/A' }}
                </span>
            </div>

            <div class="gp-info-item">
                <span class="gp-info-label">Development Block</span>
                <span class="gp-info-value">
                    {{ $gp->block_name ?? 'N/A' }}
                </span>
            </div>

            <div class="gp-info-item">
                <span class="gp-info-label">Gram Panchayat</span>
                <span class="gp-info-value">
                    {{ $gp->gp_name ?? 'N/A' }}
                </span>
            </div>

        </div>
    </div>


    {{-- Summary Cards --}}
    @php
        $totalBuildings = $assets->count();

        $officialCount = $assets->where('asset_category', 'official')->count();

        $communityCount = $assets->where('asset_category', 'community')->count();

        $commercialCount = $assets->where('asset_category', 'commercial')->count();
    @endphp

    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-building"></i>
            </div>

            <div>
                <div class="summary-number">
                    {{ $totalBuildings }}
                </div>

                <div class="summary-label">
                    Total Buildings
                </div>
            </div>
        </div>


        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-landmark"></i>
            </div>

            <div>
                <div class="summary-number">
                    {{ $officialCount }}
                </div>

                <div class="summary-label">
                    Official
                </div>
            </div>
        </div>


        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-users"></i>
            </div>

            <div>
                <div class="summary-number">
                    {{ $communityCount }}
                </div>

                <div class="summary-label">
                    Community
                </div>
            </div>
        </div>


        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-store"></i>
            </div>

            <div>
                <div class="summary-number">
                    {{ $commercialCount }}
                </div>

                <div class="summary-label">
                    Commercial
                </div>
            </div>
        </div>

    </div>


    {{-- Building List --}}
    <div class="building-card">

        <div class="building-card-header">

            <div>
                <h3 class="building-card-title">
                    Building Information
                </h3>

                <span class="building-count">
                    {{ $totalBuildings }} {{ $totalBuildings == 1 ? 'Building' : 'Buildings' }}
                </span>
            </div>

            {{-- Add Building --}}
            @if(!$isFrozen ?? true)
                <a href="{{ route('gp.assets.create') }}"
                   class="btn btn-primary add-building-btn">

                    <i class="fas fa-plus mr-1"></i>
                    Add Building

                </a>
            @endif

        </div>


        @if($assets->count() > 0)

            <div class="table-responsive">

                <table class="building-table">

                    <thead>

                        <tr>
                            <th width="5%">#</th>
                            <th>Building Name</th>
                            <th>Category</th>
                            <th>Ownership</th>
                            <th>Location</th>
                            <th>Photograph</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($assets as $index => $asset)

                            @php

                                $categoryClass = match($asset->asset_category) {
                                    'official' => 'category-official',
                                    'community' => 'category-community',
                                    'commercial' => 'category-commercial',
                                    default => ''
                                };

                            @endphp

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    <div class="building-name">
                                        {{ $asset->building_name }}
                                    </div>

                                    <div class="building-subcategory">
                                        {{ ucwords(str_replace('_', ' ', $asset->asset_sub_category)) }}
                                    </div>

                                </td>


                                <td>

                                    <span class="category-badge {{ $categoryClass }}">
                                        {{ ucfirst($asset->asset_category) }}
                                    </span>

                                </td>


                                <td>

                                    <span class="ownership-text">

                                        @switch($asset->ownership)

                                            @case('gram_panchayat')
                                                Gram Panchayat
                                                @break

                                            @case('panchayat_samiti')
                                                Panchayat Samiti
                                                @break

                                            @case('zila_parishad')
                                                Zila Parishad
                                                @break

                                            @default
                                                {{ $asset->ownership }}

                                        @endswitch

                                    </span>

                                </td>


                                <td>

                                    <a href="https://www.google.com/maps?q={{ $asset->latitude }},{{ $asset->longitude }}"
                                       target="_blank"
                                       class="photo-link">

                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        View Location

                                    </a>

                                </td>


                                <td>

                                    @if($asset->asset_image)

                                        <a href="{{ asset('storage/' . $asset->asset_image) }}"
                                           target="_blank"
                                           class="photo-link">

                                            <i class="fas fa-image mr-1"></i>
                                            View Photograph

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            Not Available
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if(!$isFrozen ?? true)

                                        <div class="action-buttons">

                                            <a href="{{ route('gp.assets.edit', $asset->id) }}"
                                               class="btn btn-outline-primary">

                                                <i class="fas fa-edit"></i>
                                                Edit

                                            </a>


                                            <form action="{{ route('gp.assets.destroy', $asset->id) }}"
                                                  method="POST"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this building?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-outline-danger">

                                                    <i class="fas fa-trash-alt"></i>
                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    @else

                                        <span class="text-success">
                                            <i class="fas fa-lock"></i>
                                            Frozen
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="fas fa-building"></i>
                </div>

                <h5>No Buildings Added</h5>

                <p>
                    No building information has been added for this Gram Panchayat yet.
                </p>

                <a href="{{ route('gp.assets.create') }}"
                   class="btn btn-primary add-building-btn">

                    <i class="fas fa-plus mr-1"></i>
                    Add First Building

                </a>

            </div>

        @endif

    </div>


    {{-- Submission Section --}}
    @if(!$isFrozen ?? true)

        <div class="submission-card">

            <div class="submission-title">
                <i class="fas fa-clipboard-check mr-1"></i>
                Certification & Final Submission
            </div>

            <div class="certification-box">

                <label>

                    <input type="checkbox"
                           id="certification"
                           name="certification"
                           form="finalSubmitForm"
                           value="1">

                    <span>
                        I hereby certify that the information provided above regarding
                        the buildings owned/managed by the Gram Panchayat is complete
                        and correct to the best of my knowledge.
                    </span>

                </label>

            </div>


            <div class="submit-area">

                <div class="submit-warning">

                    <i class="fas fa-info-circle mr-1"></i>

                    Final submission will freeze the information and
                    no further changes will be permitted.

                </div>


               <form id="finalSubmitForm"
                action="{{ route('gp.assets.final-submit') }}"
                method="POST">

                    @csrf

                    <button type="button"
            id="finalSubmitBtn"
            class="btn btn-danger freeze-btn"
            disabled
            onclick="openFinalSubmitModal()">

            <i class="fas fa-lock mr-1"></i>
            Freeze & Final Submit

            </button>

                </form>

            </div>

        </div>

    @else

        {{-- Frozen Status --}}
        <div class="frozen-card">

            <div class="frozen-icon">
                <i class="fas fa-lock"></i>
            </div>

            <div>

                <div class="frozen-title">
                    Final Submission Completed
                </div>

                <p class="frozen-text">

                    The building information for this Gram Panchayat has been
                    finally submitted and is now frozen.

                    @if(isset($submission) && $submission->submitted_at)

                        Submitted on:
                        <strong>
                            {{ $submission->submitted_at->format('d-m-Y h:i A') }}
                        </strong>

                    @endif

                </p>

            </div>

        </div>

    @endif

</div>

{{-- Final Submission Warning Modal --}}
<div id="finalSubmitModal" class="final-submit-modal">

    <div class="final-submit-dialog">

        {{-- Red Warning Header --}}
        <div class="final-submit-header">

            <div class="warning-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <div>
                <div class="warning-title">
                    Warning / चेतावनी
                </div>

                <div class="warning-subtitle">
                    Final Submission Confirmation
                </div>
            </div>

        </div>


        {{-- Modal Body --}}
        <div class="final-submit-body">

            <div class="warning-question">

                <strong>
                    Are you sure you want to Freeze & Final Submit
                    the building information?
                </strong>

                <div class="hindi-text">
                    क्या आप भवन संबंधी जानकारी को
                    Freeze एवं Final Submit करना चाहते हैं?
                </div>

            </div>


            <div class="danger-message">

                <div class="danger-message-icon">
                    <i class="fas fa-lock"></i>
                </div>

                <div>

                    <strong>
                        Important:
                    </strong>

                    <p>
                        After final submission, you will not be able
                        to add, edit or delete buildings.
                    </p>

                    <p class="hindi-text">
                        अंतिम सबमिशन के बाद आप कोई नया भवन जोड़,
                        संपादित या हटा नहीं सकेंगे।
                    </p>

                </div>

            </div>


            <div class="irreversible-warning">

                <i class="fas fa-exclamation-circle"></i>

                <div>

                    <strong>
                        This action cannot be undone.
                    </strong>

                    <div class="hindi-text">
                        यह कार्य वापस नहीं किया जा सकता।
                    </div>

                </div>

            </div>

        </div>


        {{-- Modal Footer --}}
        <div class="final-submit-footer">

            <button type="button"
                    class="btn btn-light cancel-submit-btn"
                    onclick="closeFinalSubmitModal()">

                <i class="fas fa-times mr-1"></i>
                Cancel / रद्द करें

            </button>


            <button type="button"
                    class="btn btn-danger confirm-submit-btn"
                    onclick="confirmFinalSubmit()">

                <i class="fas fa-lock mr-1"></i>
                Yes, Freeze & Submit
                <br>
                हाँ, Freeze एवं Submit करें

            </button>

        </div>

    </div>

</div>
<script>

    /*
     * Enable Freeze & Final Submit only after
     * certification checkbox is selected.
     */

    const certification = document.getElementById('certification');
    const finalSubmitBtn = document.getElementById('finalSubmitBtn');

    if (certification && finalSubmitBtn) {

        certification.addEventListener('change', function () {

            finalSubmitBtn.disabled = !this.checked;

        });

    }


    /*
     * Final confirmation before freezing.
     */

    function openFinalSubmitModal() {
        document.getElementById('finalSubmitModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeFinalSubmitModal() {
        document.getElementById('finalSubmitModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function confirmFinalSubmit() {
        document.getElementById('finalSubmitForm').submit();
    }

</script>

@endsection