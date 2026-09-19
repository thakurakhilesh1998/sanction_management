@extends('layouts.gp')

@section('main')

<style>

.add-building-wrapper {
    padding: 25px 35px 40px 35px;
}

.add-page-header {
    margin-bottom: 20px;
}

.add-page-header h1 {
    margin: 0;
    font-size: 25px;
    font-weight: 600;
    color: #252a34;
}

.add-page-header p {
    margin-top: 5px;
    margin-bottom: 0;
    color: #7c838d;
    font-size: 14px;
}

.add-card {
    background: #ffffff;
    border: 1px solid #e4e7ec;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,.05);
    overflow: hidden;
}

.add-card-header {
    padding: 17px 22px;
    border-bottom: 1px solid #e5e8ed;
    background: #fafbfc;
}

.add-card-header h5 {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    color: #343a40;
}

.add-card-header p {
    margin: 4px 0 0;
    font-size: 12px;
    color: #858b95;
}

.add-card-body {
    padding: 25px;
}

.section-heading {
    font-size: 16px;
    font-weight: 600;
    color: #0d6efd;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 9px;
    margin-top: 8px;
    margin-bottom: 20px;
}

.form-label {
    color: #343a40;
    font-weight: 600;
    font-size: 14px;
}

.form-control,
.form-select {
    min-height: 44px;
}

.input-group-text {
    min-width: 45px;
    justify-content: center;
    background: #f8f9fa;
}

.ownership-box {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px 18px;
    background: #fafafa;
}

.form-check-input {
    cursor: pointer;
}

.form-check-label {
    cursor: pointer;
}

.location-box {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

.coordinate-card {
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 7px;
    padding: 10px 13px;
}

.coordinate-card small {
    display: block;
    color: #858b95;
    font-size: 10px;
}

.coordinate-card div {
    font-weight: 600;
    font-size: 13px;
    word-break: break-word;
}

.photo-box {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

.photo-help {
    font-size: 11px;
    color: #858b95;
    margin-top: 8px;
}

.save-button {
    min-width: 180px;
}

.cancel-button {
    min-width: 100px;
}

@media(max-width: 768px) {

    .add-building-wrapper {
        padding: 18px;
    }

    .add-card-body {
        padding: 18px;
    }

}

</style>


<div class="add-building-wrapper">


    {{-- =========================================================
         PAGE HEADER
         ========================================================= --}}

    <div class="add-page-header">

        <h1>
            Add Building
        </h1>

        <p>
            Enter the details of the building owned or managed by this
            Gram Panchayat.
        </p>

    </div>



    {{-- =========================================================
         VALIDATION ERRORS
         ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    @endif



    {{-- =========================================================
         SUCCESS MESSAGE
         ========================================================= --}}

    @if(session('message'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle mr-2"></i>

            {{ session('message') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    @endif



    {{-- =========================================================
         ADD CARD
         ========================================================= --}}

    <div class="add-card">


        <div class="add-card-header">

            <h5>

                <i class="fas fa-building mr-2 text-primary"></i>

                Building Information

            </h5>

            <p>
                Enter the information carefully before saving.
            </p>

        </div>



        <div class="add-card-body">


            <form action="{{ route('gp.assets.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf



                {{-- =================================================
                     BUILDING DETAILS
                     ================================================= --}}

                <div class="section-heading">

                    <i class="fas fa-building mr-2"></i>

                    Building Details

                </div>



                <div class="row">


                    {{-- =================================================
                         CATEGORY
                         ================================================= --}}

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Building Category

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="fas fa-layer-group"></i>

                            </span>


                            <select class="form-control"
                                    name="asset_category"
                                    id="asset_category"
                                    required>

                                <option value="">
                                    Select Building Category
                                </option>

                                <option value="official"
                                    {{ old('asset_category') == 'official' ? 'selected' : '' }}>

                                    Official Buildings

                                </option>

                                <option value="community"
                                    {{ old('asset_category') == 'community' ? 'selected' : '' }}>

                                    Community Buildings

                                </option>

                                <option value="commercial"
                                    {{ old('asset_category') == 'commercial' ? 'selected' : '' }}>

                                    Commercial Buildings

                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- =================================================
                         SUB CATEGORY
                         ================================================= --}}

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Building Sub-Category

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="fas fa-list"></i>

                            </span>


                            <select class="form-control"
                                    name="asset_sub_category"
                                    id="asset_sub_category"
                                    required
                                    disabled>

                                <option value="">
                                    Select Building Sub-Category
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- =================================================
                         BUILDING NAME
                         ================================================= --}}

                    <div class="col-md-12 mb-4">

                        <label class="form-label">

                            Building Name

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="fas fa-signature"></i>

                            </span>


                            <input type="text"
                                   class="form-control"
                                   name="building_name"
                                   value="{{ old('building_name') }}"
                                   placeholder="Enter building name"
                                   required>

                        </div>

                    </div>


                </div>



                {{-- =================================================
                     PANCHAYAT GHAR STATUS
                     ================================================= --}}

                <div id="panchayatOfficeOwnership"
                     class="mb-4"
                     style="display:none;">

                    <div class="section-heading">

                        <i class="fas fa-home mr-2"></i>

                        Panchayat Ghar / Panchayat Office Status

                    </div>


                    <div class="ownership-box">


                        <label class="form-label">

                            Status of Panchayat Ghar / Panchayat Office

                            <span class="text-danger">*</span>

                        </label>


                        <div class="row mt-3">


                            <div class="col-md-6">

                                <div class="form-check">

                                    <input class="form-check-input"
                                           type="radio"
                                           name="panchayat_office_status"
                                           id="owned_by_gram_panchayat"
                                           value="owned_by_gram_panchayat"
                                           {{ old('panchayat_office_status') == 'owned_by_gram_panchayat' ? 'checked' : '' }}>


                                    <label class="form-check-label"
                                           for="owned_by_gram_panchayat">

                                        Owned by the Gram Panchayat

                                    </label>

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-check">

                                    <input class="form-check-input"
                                           type="radio"
                                           name="panchayat_office_status"
                                           id="taken_on_rent"
                                           value="taken_on_rent"
                                           {{ old('panchayat_office_status') == 'taken_on_rent' ? 'checked' : '' }}>


                                    <label class="form-check-label"
                                           for="taken_on_rent">

                                        Taken on Rent

                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     OWNERSHIP
                     ================================================= --}}

                <div id="ownershipSection">

                    <div class="section-heading">

                        <i class="fas fa-user-shield mr-2"></i>

                        Ownership

                    </div>


                    <div class="mb-4">

                        <label class="form-label">

                            Ownership of Building

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="fas fa-users"></i>

                            </span>


                            <select class="form-control"
                                    name="ownership"
                                    id="ownership">

                                <option value="">
                                    Select Ownership
                                </option>

                                <option value="gram_panchayat"
                                    {{ old('ownership') == 'gram_panchayat' ? 'selected' : '' }}>

                                    Gram Panchayat

                                </option>

                                <option value="panchayat_samiti"
                                    {{ old('ownership') == 'panchayat_samiti' ? 'selected' : '' }}>

                                    Panchayat Samiti

                                </option>

                                <option value="zila_parishad"
                                    {{ old('ownership') == 'zila_parishad' ? 'selected' : '' }}>

                                    Zila Parishad

                                </option>

                            </select>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     GEO LOCATION
                     ================================================= --}}

                <div class="section-heading">

                    <i class="fas fa-location-dot mr-2"></i>

                    Geo-Location

                </div>


                <div class="location-box mb-4">


                    <div class="d-flex flex-wrap align-items-center">


                        <button type="button"
                                id="getLocation"
                                class="btn btn-primary mr-3 mb-2">

                            <i class="fas fa-location-crosshairs mr-2"></i>

                            Capture Current Location

                        </button>


                        <span class="text-muted small mb-2">

                            Please capture the current location of the building.

                        </span>

                    </div>



                    <div class="row mt-3">


                        <div class="col-md-4 mb-2">

                            <div class="coordinate-card">

                                <small>
                                    Latitude
                                </small>

                                <div id="latitude_display">
                                    Not captured
                                </div>

                            </div>

                        </div>



                        <div class="col-md-4 mb-2">

                            <div class="coordinate-card">

                                <small>
                                    Longitude
                                </small>

                                <div id="longitude_display">
                                    Not captured
                                </div>

                            </div>

                        </div>



                        <div class="col-md-4 mb-2">

                            <div class="coordinate-card">

                                <small>
                                    Accuracy
                                </small>

                                <div id="accuracy_display">
                                    Not available
                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- Hidden GPS fields --}}

                    <input type="hidden"
                           name="latitude"
                           id="latitude"
                           value="{{ old('latitude') }}">


                    <input type="hidden"
                           name="longitude"
                           id="longitude"
                           value="{{ old('longitude') }}">


                    <input type="hidden"
                           name="location_accuracy"
                           id="location_accuracy"
                           value="{{ old('location_accuracy') }}">


                    <input type="hidden"
                           name="location_captured_at"
                           id="location_captured_at"
                           value="{{ old('location_captured_at') }}">

                </div>



                {{-- =================================================
                     PHOTOGRAPH
                     ================================================= --}}

                <div class="section-heading">

                    <i class="fas fa-camera mr-2"></i>

                    Building Photograph

                </div>


                <div class="photo-box mb-4">

                    <label class="form-label">

                        Building Photograph

                        <span class="text-danger">*</span>

                    </label>


                    <input type="file"
                           class="form-control"
                           name="asset_image"
                           id="asset_image"
                           accept="image/jpeg,image/jpg,image/png"
                           required>


                    <div class="photo-help">

                        <i class="fas fa-info-circle mr-1"></i>

                        Upload one recent photograph of the building.

                        JPG, JPEG or PNG up to 1 MB.

                    </div>

                </div>



                {{-- =================================================
                     BUTTONS
                     ================================================= --}}

                <div class="d-flex justify-content-end mt-4">


                    <a href="{{ route('gp.assets.index') }}"
                       class="btn btn-secondary cancel-button mr-2">

                        <i class="fas fa-arrow-left mr-1"></i>

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-success save-button">

                        <i class="fas fa-save mr-2"></i>

                        Save Building

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


@endsection



@section('scripts')

<script src="{{ asset('assets/js/gpstatus_validation.js') }}"></script>

<script src="{{ asset('assets/js/geolocation.js') }}"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const category =
        document.getElementById('asset_category');

    const subCategory =
        document.getElementById('asset_sub_category');

    const panchayatOfficeOwnership =
        document.getElementById('panchayatOfficeOwnership');

    const ownershipSection =
        document.getElementById('ownershipSection');

    const ownership =
        document.getElementById('ownership');


    /*
    |--------------------------------------------------------------------------
    | Sub Categories
    |--------------------------------------------------------------------------
    */

    const subCategories = {

        official: [

            {
                value: 'panchayat_ghar',
                text: 'Panchayat Ghar / Panchayat Office'
            },

            {
                value: 'panchayat_learning_centre',
                text: 'Panchayat Learning Centre (PLC)'
            },

            {
                value: 'common_service_centre',
                text: 'Common Service Centre'
            }

        ],


        community: [

            {
                value: 'mahila_mandal_bhawan',
                text: 'Mahila Mandal Bhawan'
            },

            {
                value: 'yuvak_mandal_bhawan',
                text: 'Yuvak Mandal Bhawan'
            },

            {
                value: 'ambedkar_bhawan',
                text: 'Ambedkar Bhawan'
            },

            {
                value: 'mukhya_mantri_lok_bhawan',
                text: 'Mukhya Mantri Lok Bhawan'
            },

            {
                value: 'marriage_hall',
                text: 'Marriage Hall'
            },

            {
                value: 'community_centre',
                text: 'Community Centre'
            },

            {
                value: 'library',
                text: 'Library'
            }

        ],


        commercial: [

            {
                value: 'shops',
                text: 'Shops of Panchayat'
            },

            {
                value: 'commercial_complex',
                text: 'Commercial Complexes of Panchayat'
            },

            {
                value: 'godown',
                text: 'Godowns of Panchayat'
            },

            {
                value: 'guest_house',
                text: 'Guest House of Panchayat'
            }

        ]

    };


    /*
    |--------------------------------------------------------------------------
    | Existing / Old Sub Category
    |--------------------------------------------------------------------------
    */

    const oldSubCategory =
        "{{ old('asset_sub_category') }}";


    /*
    |--------------------------------------------------------------------------
    | Load Sub Categories
    |--------------------------------------------------------------------------
    */

    function loadSubCategories(selectedValue = null)
    {

        subCategory.innerHTML =
            '<option value="">Select Building Sub-Category</option>';


        if (!category.value) {

            subCategory.disabled = true;

            return;

        }


        subCategory.disabled = false;


        if (subCategories[category.value]) {

            subCategories[category.value].forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value =
                    item.value;

                option.textContent =
                    item.text;


                if (
                    selectedValue &&
                    selectedValue === item.value
                ) {

                    option.selected = true;

                }


                subCategory.appendChild(option);

            });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Panchayat Ghar Status
    |--------------------------------------------------------------------------
    */

    function togglePanchayatOfficeStatus()
    {

        if (
            subCategory.value === 'panchayat_ghar'
        ) {

            panchayatOfficeOwnership.style.display =
                'block';

        } else {

            panchayatOfficeOwnership.style.display =
                'none';

            document
                .querySelectorAll(
                    'input[name="panchayat_office_status"]'
                )
                .forEach(function (radio) {

                    radio.checked = false;

                });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Ownership Handling
    |--------------------------------------------------------------------------
    |
    | Panchayat Ghar / Panchayat Office:
    |
    | Owned by GP  -> Gram Panchayat
    | Taken on Rent -> NULL
    |
    | Other buildings:
    | Ownership visible and required.
    |
    */

    function handleOwnership()
    {

        if (
            subCategory.value === 'panchayat_ghar'
        ) {

            ownershipSection.style.display =
                'none';

            ownership.required = false;


            const selectedStatus =
                document.querySelector(
                    'input[name="panchayat_office_status"]:checked'
                );


            if (
                selectedStatus &&
                selectedStatus.value ===
                'owned_by_gram_panchayat'
            ) {

                ownership.value =
                    'gram_panchayat';

            }
            else if (
                selectedStatus &&
                selectedStatus.value ===
                'taken_on_rent'
            ) {

                ownership.value =
                    '';

            }

        } else {

            ownershipSection.style.display =
                'block';

            ownership.required = true;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Panchayat Office Status Change
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            'input[name="panchayat_office_status"]'
        )
        .forEach(function (radio) {

            radio.addEventListener('change', function () {

                if (
                    this.value ===
                    'owned_by_gram_panchayat'
                ) {

                    ownership.value =
                        'gram_panchayat';

                }
                else if (
                    this.value ===
                    'taken_on_rent'
                ) {

                    ownership.value =
                        '';

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Category Change
    |--------------------------------------------------------------------------
    */

    category.addEventListener('change', function () {

        loadSubCategories();

        togglePanchayatOfficeStatus();

        handleOwnership();

    });


    /*
    |--------------------------------------------------------------------------
    | Sub Category Change
    |--------------------------------------------------------------------------
    */

    subCategory.addEventListener('change', function () {

        togglePanchayatOfficeStatus();

        handleOwnership();

    });


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    loadSubCategories(oldSubCategory);

    togglePanchayatOfficeStatus();

    handleOwnership();



    /*
    |--------------------------------------------------------------------------
    | Restore Old Panchayat Office Status
    |--------------------------------------------------------------------------
    */

    const oldOfficeStatus =
        "{{ old('panchayat_office_status') }}";


    if (
        oldSubCategory === 'panchayat_ghar' &&
        oldOfficeStatus
    ) {

        const oldRadio =
            document.querySelector(
                'input[name="panchayat_office_status"][value="' +
                oldOfficeStatus +
                '"]'
            );

        if (oldRadio) {

            oldRadio.checked = true;

        }

        handleOwnership();

    }



    /*
    |--------------------------------------------------------------------------
    | Current Location
    |--------------------------------------------------------------------------
    */

    const getLocation =
        document.getElementById('getLocation');


    getLocation.addEventListener('click', function () {


        if (!navigator.geolocation) {

            alert(
                'Geolocation is not supported by your browser.'
            );

            return;

        }


        getLocation.disabled = true;


        getLocation.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-2"></i>Capturing Location...';



        navigator.geolocation.getCurrentPosition(

            function (position) {


                const latitude =
                    position.coords.latitude;

                const longitude =
                    position.coords.longitude;

                const accuracy =
                    position.coords.accuracy;


                /*
                |----------------------------------------------------------
                | Set hidden fields
                |----------------------------------------------------------
                */

                document.getElementById('latitude').value =
                    latitude;

                document.getElementById('longitude').value =
                    longitude;

                document.getElementById('location_accuracy').value =
                    accuracy;

                document.getElementById('location_captured_at').value =
                    new Date().toISOString();


                /*
                |----------------------------------------------------------
                | Display values
                |----------------------------------------------------------
                */

                document.getElementById('latitude_display').textContent =
                    latitude.toFixed(7);

                document.getElementById('longitude_display').textContent =
                    longitude.toFixed(7);

                document.getElementById('accuracy_display').textContent =
                    accuracy.toFixed(2) + ' metres';


                /*
                |----------------------------------------------------------
                | Restore button
                |----------------------------------------------------------
                */

                getLocation.disabled = false;

                getLocation.innerHTML =
                    '<i class="fas fa-location-crosshairs mr-2"></i>Recapture Current Location';


            },


            function (error) {


                let message =
                    'Unable to capture your location.';


                if (error.code === 1) {

                    message =
                        'Location permission was denied. Please allow location access.';

                }

                else if (error.code === 2) {

                    message =
                        'Your location could not be determined.';

                }

                else if (error.code === 3) {

                    message =
                        'Location request timed out. Please try again.';

                }


                alert(message);


                getLocation.disabled = false;

                getLocation.innerHTML =
                    '<i class="fas fa-location-crosshairs mr-2"></i>Capture Current Location';

            },


            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    });

});

</script>

@endsection