@extends('layouts.admin')

@section('main')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Gram Panchayat Building Details
            </h4>

            <p class="text-muted mb-0">
                Submitted building information
            </p>
        </div>

        <a href="{{ route('admin.assets.index', [
            'district' => $gp->district_name,
            'block' => $gp->block_name
        ]) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Back

        </a>

    </div>


    {{-- GP Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Gram Panchayat Information
                </h5>

                <span class="badge bg-success">

                    <i class="bi bi-lock-fill"></i>
                    Frozen

                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        District
                    </small>

                    <div class="fw-semibold">
                        {{ $gp->district_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Development Block
                    </small>

                    <div class="fw-semibold">
                        {{ $gp->block_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Gram Panchayat
                    </small>

                    <div class="fw-semibold">
                        {{ $gp->gp_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Total Buildings
                    </small>

                    <div class="fw-semibold">
                        {{ $gp->priAssets->count() }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Submitted On
                    </small>

                    <div class="fw-semibold">

                        @if($gp->priAssetSubmission &&
                            $gp->priAssetSubmission->submitted_at)

                            {{ \Carbon\Carbon::parse(
                                $gp->priAssetSubmission->submitted_at
                            )->format('d-m-Y h:i A') }}

                        @else
                            -
                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>

                        <span class="badge bg-success">
                            Submitted / Frozen
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Buildings --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Building Information
            </h5>

        </div>


        <div class="card-body">

            @if($gp->priAssets->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>S.No.</th>

                                <th>Building Name</th>

                                <th>Category</th>

                                <th>Sub-Category</th>

                                <th>Ownership</th>

                                <th>Office Status</th>

                                <th>Location</th>

                                <th>Photograph</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($gp->priAssets as $index => $asset)

                                @php

                                    $categoryLabels = [

                                        'official' => 'Official',
                                        'community' => 'Community',
                                        'commercial' => 'Commercial',

                                    ];

                                    $subCategoryLabels = [

                                        'panchayat_ghar' =>
                                            'Panchayat Ghar / Panchayat Office',

                                        'panchayat_learning_centre' =>
                                            'Panchayat Learning Centre (PLC)',

                                        'common_service_centre' =>
                                            'Common Service Centre',

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

                                        'shops_of_panchayat' =>
                                            'Shops of Panchayat',

                                        'commercial_complexes_of_panchayat' =>
                                            'Commercial Complexes of Panchayat',

                                        'godowns_of_panchayat' =>
                                            'Godowns of Panchayat',

                                        'guest_house_of_panchayat' =>
                                            'Guest House of Panchayat',

                                    ];

                                    $ownershipLabels = [

                                        'gram_panchayat' =>
                                            'Gram Panchayat',

                                        'panchayat_samiti' =>
                                            'Panchayat Samiti',

                                        'zila_parishad' =>
                                            'Zila Parishad',

                                    ];

                                    $officeStatusLabels = [

                                        'owned_by_gram_panchayat' =>
                                            'Owned by Gram Panchayat',

                                        'taken_on_rent' =>
                                            'Taken on Rent',

                                    ];

                                @endphp

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>
                                        <strong>
                                            {{ $asset->building_name }}
                                        </strong>
                                    </td>


                                    <td>
                                        {{ $categoryLabels[
                                            strtolower(
                                                trim($asset->asset_category)
                                            )
                                        ] ?? $asset->asset_category }}
                                    </td>


                                    <td>

                                        {{ $subCategoryLabels[
                                            strtolower(
                                                trim(
                                                    $asset->asset_sub_category
                                                )
                                            )
                                        ] ?? ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $asset->asset_sub_category
                                            )
                                        ) }}

                                    </td>


                                    <td>

                                        @if($asset->ownership)

                                            {{ $ownershipLabels[
                                                strtolower(
                                                    trim($asset->ownership)
                                                )
                                            ] ?? $asset->ownership }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if($asset->panchayat_office_status)

                                            {{ $officeStatusLabels[
                                                strtolower(
                                                    trim(
                                                        $asset->panchayat_office_status
                                                    )
                                                )
                                            ] ?? $asset->panchayat_office_status }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if($asset->latitude &&
                                            $asset->longitude)

                                            <a href="https://www.google.com/maps?q={{ $asset->latitude }},{{ $asset->longitude }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary">

                                                <i class="bi bi-geo-alt"></i>
                                                View Map

                                            </a>

                                            <div class="small text-muted mt-1">

                                                {{ $asset->latitude }},
                                                {{ $asset->longitude }}

                                            </div>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if($asset->asset_image)

                                            <img src="{{ asset(
                                                'storage/' . $asset->asset_image
                                            ) }}"
                                                 alt="Building"
                                                 class="img-thumbnail building-image"
                                                 style="
                                                    width:90px;
                                                    height:70px;
                                                    object-fit:cover;
                                                    cursor:pointer;
                                                 "
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#imageModal"
                                                 data-image="{{ asset(
                                                    'storage/' . $asset->asset_image
                                                 ) }}">

                                        @else

                                            <span class="text-muted">
                                                No Image
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-building fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Building Information Found
                    </h5>

                </div>

            @endif

        </div>

    </div>


    {{-- Unfreeze --}}
    <div class="card shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1">
                        Administrative Action
                    </h6>

                    <small class="text-muted">
                        Unfreezing will allow the Gram Panchayat
                        to edit the building information again.
                    </small>

                </div>


                <form action="{{ route(
                    'admin.assets.unfreeze',
                    $gp->id
                ) }}"
                      method="POST"
                      onsubmit="return confirm(
                          'Are you sure you want to unfreeze this Gram Panchayat?'
                      );">

                    @csrf

                    <button type="submit"
                            class="btn btn-warning">

                        <i class="bi bi-unlock"></i>
                        Unfreeze

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- Image Modal --}}
<div class="modal fade"
     id="imageModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Building Photograph
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body text-center">

                <img id="modalImage"
                     src=""
                     class="img-fluid"
                     style="max-height:70vh;">

            </div>

        </div>

    </div>

</div>


@endsection


@section('scripts')

<script>

$(document).ready(function () {

    $('.building-image').on('click', function () {

        let image = $(this).data('image');

        $('#modalImage').attr('src', image);

    });

});

</script>

@endsection