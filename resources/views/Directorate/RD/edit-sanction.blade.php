@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Directorate Home Page</h3>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{$error}}</div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-body">
        <form action="{{url('dir/sanction-update-rd/'.$sanction->id)}}"  id="sanction" method="POST">
            @csrf
            @method('PUT')
            {{-- Financial Year --}}
            <div class="mb-3">
                <label for="Financial Year" class="form-label">Select Financial Year</label>
                <select name="financial_year" id="financial_year" class="form-control">
                    <option value="-1">--Select F.Y.--</option>
                    <option value="2020-21" {{$sanction->financial_year=='2020-21'?'selected':''}}>2020-21</option>
                    <option value="2021-22" {{$sanction->financial_year=='2021-22'?'selected':''}}>2021-22</option>
                    <option value="2022-23" {{$sanction->financial_year=='2022-23'?'selected':''}}>2022-23</option>
                    <option value="2023-24" {{$sanction->financial_year=='2023-24'?'selected':''}}>2023-24</option>
                    <option value="2024-25" {{$sanction->financial_year=='2024-25'?'selected':''}}>2024-25</option>
                    <option value="2025-26" {{$sanction->financial_year=='2025-26'?'selected':''}}>2025-26</option>
                </select>
            </div>
            {{-- District Name --}}
            <div class="mb-3" id="district-block">
                <label for="District name" class="form-label">Select District Name</label>
                <select id="district-list" class="form-control" name="district">
                    <option value="-1">--Select District--</option>
                    <option value="Bilaspur" {{$sanction->district=='Bilaspur'?'selected':''}}>Bilaspur</option>
                    <option value="Chamba" {{$sanction->district=='Chamba'?'selected':''}}>Chamba</option>
                    <option value="Hamirpur" {{$sanction->district=='Hamirpur'?'selected':''}}>Hamirpur</option>
                    <option value="Kangra" {{$sanction->district=='Kangra'?'selected':''}}>Kangra</option>
                    <option value="Kinnaur" {{$sanction->district=='Kinnaur'?'selected':''}}>Kinnaur</option>
                    <option value="Lahul And Spiti" {{$sanction->district=='Lahul And Spiti'?'selected':''}}>Lahul And Spiti</option>
                    <option value="Shimla" {{$sanction->district=='Shimla'?'selected':''}}>Shimla</option>
                    <option value="Sirmaur" {{$sanction->district=='Sirmaur'?'selected':''}}>Sirmaur</option>
                    <option value="Solan" {{$sanction->district=='Solan'?'selected':''}}>Solan</option>
                    <option value="Una" {{$sanction->district=='Una'?'selected':''}}>Una</option>
                    <option value="Kullu" {{$sanction->district=='Kullu'?'selected':''}}>Kullu</option>
                    <option value="Mandi" {{$sanction->district=='Mandi'?'selected':''}}>Mandi</option>
                </select>
            </div>
            {{-- Block Name --}}
            <div class="mb-3" id="blocks-block">
                <label for="Block name" class="form-label">Select Block Name</label>
                <select id="block-list" class="form-control" name="block">
                    <option value="{{$sanction->block}}">{{$sanction->block}}</option>
                </select>
            </div>
             
            {{-- Amount --}}
            <div class="mb-3">
                <label for="Block Name" class="form-label">Enter Sanction Amount(in Rs.)</label>
                <input type="number" name="san_amount" id="sanction_amt" class="form-control" value="{{$sanction->san_amount}}">
            </div>
            {{-- Sanction Date --}}
           <div class="mb-3">
                <label for="Date" class="form-label">Select Date of Sanction</label>
                <input type="date" name="sanction_date" class="form-control" id="sanction_date" value="{{$sanction->sanction_date}}">
           </div>
           {{-- Head of Sanction --}}
           <div class="mb-3">
            <label for="Head of Sanction" class="form-label">Enter Sanction Head</label>
                <select name="sanction_head" id="sanction_head" class="form-control">
                    <option value="-1">--Select Head--</option>
                    <option value="Head 1" {{$sanction->sanction_head=='Head 1'?'selected':''}}>Head 1</option>
                    <option value="Head 2" {{$sanction->sanction_head=='Head 2'?'selected':''}}>Head 2</option>
                </select>
           </div>
           {{-- Purpose of sanction --}}
           <div class="mb-3">
            <label for="purpose of sanction" class="form-label">Purpose of Sanction</label>
            <select name="sanction_purpose" id="sanction_purpose" class="form-control">
                <option value="-1">Select Sanction Purpose</option>
                <option value="Construction of BDO Office" {{$sanction->sanction_purpose=='Construction of BDO Office'?'selected':''}}>Construction of BDO Office</option>
                <option value="Construction of MMLB" {{$sanction->sanction_purpose=='Construction of MMLB'?'selected':''}}>Construction of MMLB</option>
            </select>
           </div>

           {{-- Executing Agency --}}
           <div class="mb-3">
            <label for="Executing Agency" class="form-label">Work is to be executed by:</label>
            <select name="agency" id="agency" class="form-control">
                <option value="-1">Select Executing Agency</option>
                <option value="xen" {{$sanction->agency=='xen'?'selected':''}}>Executive Engineer</option>
            </select>
           </div>

           <button type="submit" class="btn btn-primary">Update Sanction Details</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
   $(document).ready(function() {
            // Load the JSON data
            $.getJSON("{{asset('assets/json/output.json')}}", function(data) {
                // Display the list of districts
               
                // displayDistricts(data.districts);

                // Handle district selection
            $("#district-block").on("change", "#district-list", function() {
                var selectedDistrict = $(this).val();
                displayBlocks(Object.keys(data.data[selectedDistrict]));
            });

               // Handle district selection
               $("#district-block").on("click", "#district-list", function() {
                var selectedDistrict = $(this).val();
                displayBlocks(Object.keys(data.data[selectedDistrict]));
            });

            // Handle block selection
            $("#blocks-block").on("change", "#block-list", function() {
                var selectedDistrict = $("#district-list").val();
                var selectedBlock = $(this).val();
                displayPanchayats(data.data[selectedDistrict][selectedBlock]);
            });

            // Handle block selection
            $("#blocks-block").on("click", "#block-list", function() {
                var selectedDistrict = $("#district-list").val();
                var selectedBlock = $(this).val();
                displayPanchayats(data.data[selectedDistrict][selectedBlock]);
            });
        });
        });
    // function displayDistricts(districts) {

    //         var districtList = '<label for="District name" class="form-label">Select District Name</label><select id="district-list" class="form-control" name="district"><option value="-1">--Select District--</option>';
    //         $.each(districts, function(index, district) {
    //             districtList += '<option value="' + district + '">' + district + '</option>';
    //         });
    //         districtList += '</select>';
    //         $("#district-block").html(districtList);

    //     }
    function displayBlocks(blocks) {
        var blockList = '<label for="Block name" class="form-label">Select Block Name</label><select id="block-list" class="form-control" name="block"><option value="-1">--Select Block--</option>';
        $.each(blocks, function(index, block) {
            blockList += '<option value="' + block + '">' + block + '</option>';
        });
        blockList += '</select>';
        $("#blocks-block").html(blockList);
    }

    function displayPanchayats(panchayats) {
        console.log(panchayats);
        var panchayatList = '<label for="Gram Panchayat name" class="form-label">Select Gram Panchayat Name</label><select id="panchayat-list" class="form-control" name="gp"><option value="-1">--Select Gram Panchayat--</option>';
        $.each(panchayats, function(panchayat, constituencies) {
            panchayatList += '<option value="' + panchayat + '">' + panchayat + '</option>';
        });
        panchayatList += '</select>';
        $("#gp-block").html(panchayatList);
    }
   
</script>
<script src="{{asset('assets/js/val_rd_san.js')}}"></script>
@endsection