@extends('layouts.admin')

@section('main')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">PRI Building Information</h4>
            <p class="text-muted mb-0">
                View and manage frozen Gram Panchayat building information
            </p>
        </div>
    </div>


    {{-- Success Message --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Error Message --}}
    @if($errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Filter Section --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Select Location</h5>
        </div>

        <div class="card-body">

       <form method="GET"
      action="{{ route('admin.assets.index') }}">

    <div class="row">

        {{-- District --}}
        <div class="col-md-5 mb-3">

            <label for="district" class="form-label">
                District <span class="text-danger">*</span>
            </label>

            <select name="district"
                    id="district"
                    class="form-select"
                    required>

                <option value="">
                    Select District
                </option>

                @foreach($districts as $district)

                    <option value="{{ $district }}"
                        {{ $selectedDistrict == $district ? 'selected' : '' }}>

                        {{ $district }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- Block --}}
        <div class="col-md-5 mb-3">

            <label for="block" class="form-label">
                Block <span class="text-danger">*</span>
            </label>

            <select name="block"
                    id="block"
                    class="form-select"
                    {{ !$selectedDistrict ? 'disabled' : '' }}
                    required>

                <option value="">
                    Select Block
                </option>

                @foreach($blocks as $block)

                    <option value="{{ $block }}"
                        {{ $selectedBlock == $block ? 'selected' : '' }}>

                        {{ $block }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- Search --}}
        <div class="col-md-2 mb-3 d-flex align-items-end">

            <button type="submit"
                    class="btn btn-primary w-100">

                <i class="bi bi-search"></i>
                Search

            </button>

        </div>

    </div>

</form>

        </div>

    </div>


    {{-- Frozen GP List --}}
    @if($selectedDistrict && $selectedBlock)

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            Frozen Gram Panchayats
                        </h5>

                        <small class="text-muted">

                            District:
                            <strong>{{ $selectedDistrict }}</strong>

                            &nbsp; | &nbsp;

                            Block:
                            <strong>{{ $selectedBlock }}</strong>

                        </small>
                    </div>

                    <span class="badge bg-success">
                        {{ $gps->count() }} Frozen GP(s)
                    </span>

                </div>

            </div>


            <div class="card-body">

                @if($gps->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="6%">
                                        S.No.
                                    </th>

                                    <th>
                                        Gram Panchayat
                                    </th>

                                    <th class="text-center">
                                        No. of Buildings
                                    </th>

                                    <th>
                                        Submitted On
                                    </th>

                                    <th>
                                        Submitted By
                                    </th>

                                    <th class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($gps as $index => $gp)

                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $gp->gp_name }}
                                            </strong>
                                        </td>


                                        <td class="text-center">

                                            <span class="badge bg-info text-dark">

                                                {{ $gp->priAssets->count() }}

                                            </span>

                                        </td>


                                        <td>

                                            @if($gp->priAssetSubmission &&
                                                $gp->priAssetSubmission->submitted_at)

                                                {{ \Carbon\Carbon::parse(
                                                    $gp->priAssetSubmission->submitted_at
                                                )->format('d-m-Y h:i A') }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        <td>

                                            @if($gp->priAssetSubmission &&
                                                $gp->priAssetSubmission->submitted_by)

                                                {{ $gp->priAssetSubmission->submitted_by }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        <td class="text-center">

                                            {{-- View --}}
                                            <a href="{{ route(
                                                'admin.assets.view-gp',
                                                $gp->id
                                            ) }}"
                                               class="btn btn-sm btn-primary">

                                                <i class="bi bi-eye"></i>
                                                View

                                            </a>


                                            {{-- Unfreeze --}}
                                            <form action="{{ route(
                                                'admin.assets.unfreeze',
                                                $gp->id
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to unfreeze the building information of this Gram Panchayat?'
                                                  );">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-warning">

                                                    <i class="bi bi-unlock"></i>
                                                    Unfreeze

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-info-circle fs-1 text-muted"></i>

                        <h5 class="mt-3">
                            No Frozen Gram Panchayat Found
                        </h5>

                        <p class="text-muted mb-0">
                            No Gram Panchayat in the selected Block has
                            finally submitted the building information.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

@section('scripts')

<script>

$(document).ready(function () {

    $('#district').on('change', function () {

        let district = $(this).val();

        let blockDropdown = $('#block');

        // Clear existing blocks
        blockDropdown.empty();

        blockDropdown.append(
            '<option value="">Select Block</option>'
        );

        // No district selected
        if (!district) {

            blockDropdown.prop('disabled', true);

            return;
        }

        // Disable while loading
        blockDropdown.prop('disabled', true);

        $.ajax({

            url: "{{ route('admin.assets.get-blocks') }}",

            type: "GET",

            data: {
                district: district
            },

            success: function (blocks) {

                if (blocks.length > 0) {

                    $.each(blocks, function (index, block) {

                        blockDropdown.append(
                            $('<option>', {
                                value: block,
                                text: block
                            })
                        );

                    });

                    blockDropdown.prop('disabled', false);

                } else {

                    blockDropdown.append(
                        '<option value="">No Block Found</option>'
                    );

                }

            },

            error: function (xhr) {

                console.log(xhr);

                alert(
                    'Unable to load Blocks. Please try again.'
                );

                blockDropdown.prop('disabled', true);

            }

        });

    });

});

</script>

@endsection
@endsection