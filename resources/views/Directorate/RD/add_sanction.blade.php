@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Directorate Home Page - RD Sanction</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        @if($errors->has('financial_year'))
            <div class="alert alert-danger">
                {{ $errors->first('financial_year') }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <h4>Add Sanction</h3>
        <form action="{{url('dir/rd-saveSan')}}"  id="sanction" method="POST">
            @csrf
            {{-- Financial Year --}}
            <div class="mb-3">
                <label for="Financial Year" class="form-label">Select Financial Year</label>
                <select name="financial_year" id="financial_year" class="form-control">
                    <option value="-1">--Select F.Y.--</option>
                    {{-- <option value="{{$current}}">{{$current}}</option> --}}
                    <option value="2020-21">2020-21</option>
                    <option value="2021-22">2021-22</option>
                    <option value="2022-23">2022-23</option>
                    <option value="2023-24">2023-24</option>
                    <option value="2024-25">2024-25</option>
                    <option value="2025-26">2025-26</option>
                </select>
            </div>
            {{-- District Name --}}
            <div class="mb-3" id="district-block">
                
            </div>
            {{-- Block Name --}}
            <div class="mb-3" id="blocks-block">
                
            </div>
           
            {{-- Amount --}}
            <div class="mb-3">
                <label for="Block Name" class="form-label">Enter Sanction Amount(in Rs.)</label>
                <input type="number" name="san_amount" id="sanction_amt" class="form-control">
            </div>
            {{-- Sanction Date --}}
           <div class="mb-3">
                <label for="Date" class="form-label">Select Date of Sanction</label>
                <input type="date" name="sanction_date" class="form-control" id="sanction_date">
           </div>
           {{-- Head of Sanction --}}
           <div class="mb-3">
            <label for="Head of Sanction" class="form-label">Select Sanction Head</label>
            <select name="sanction_head" id="sanction_head" class="form-control">
                <option value="-1">--Select Head--</option>
                <option value="Head 1">2515-00-102-14-SOON</option>
                <option value="Head 2">2515-00-102-16-SOON</option>
            </select>
            {{-- <input type="text" name="sanction_head" id="sanction_head" class="form-control"> --}}
           </div>
           {{-- Purpose of sanction --}}
           <div class="mb-3">
            <label for="purpose of sanction" class="form-label">Purpose of Sanction</label>
            <select name="sanction_purpose" id="sanction_purpose" class="form-control">
                <option value="-1">Select Sanction Purpose</option>
                <option value="Construction of BDO Office">Construction of Office Building</option>
                <option value="Construction of MMLB">Construction of Residential Building</option>
            </select>
           </div>

           {{-- Execution of Work --}}
           <div class="mb-3">
            <label for="Executing Agency" class="form-label">Work is to be executed by:</label>
            <select name="agency" id="agency" class="form-control">
                <option value="-1">Select Executing Agency</option>
                <option value="xen">Executive Engineer</option>
            </select>
           </div>

           <button type="submit" class="btn btn-primary">Add Sanction</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Load the JSON data
        $.getJSON("{{ asset('assets/json/output.json') }}", function(data) {
            // Display the list of districts
            displayDistricts(Object.keys(data.data));
            // Handle district selection
            $("#district-block").on("change", "#district-list", function() {
                var selectedDistrict = $(this).val();
                displayBlocks(Object.keys(data.data[selectedDistrict]));
            });
            // Handle block selection
            $("#blocks-block").on("change", "#block-list", function() {
                var selectedDistrict = $("#district-list").val();
                var selectedBlock = $(this).val();
                displayPanchayats(data.data[selectedDistrict][selectedBlock]);
            // Handle Gram Panchayat Selection
            });
        });
    });

    function displayDistricts(districts) {
        var districtList = '<label for="District name" class="form-label">Select District Name</label><select id="district-list" class="form-control" name="district"><option value="-1">--Select District--</option>';
        $.each(districts, function(index, district) {
            districtList += '<option value="' + district + '">' + district + '</option>';
        });
        districtList += '</select>';
        $("#district-block").html(districtList);
    }

    function displayBlocks(blocks) {
        var blockList = '<label for="Block name" class="form-label">Select Block Name</label><select id="block-list" class="form-control" name="block"><option value="-1">--Select Block--</option>';
        $.each(blocks, function(index, block) {
            blockList += '<option value="' + block + '">' + block + '</option>';
        });
        blockList += '</select>';
        $("#blocks-block").html(blockList);
    }
</script>

<script src="{{asset('assets/js/val_rd_san.js')}}"></script>
@endsection