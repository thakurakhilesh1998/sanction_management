@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Directorate Home Page</h3>
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
        <form action="{{url('dir/sanction-add')}}"  id="sanction" method="POST">
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
             {{-- GramPanchayat Name --}}
             <div class="mb-3" id="gp-block">
                
            </div>
            {{-- Assembly Constituency --}}
            <div class="mb-3" id="constituency-block">
            </div>
            {{-- Previous Sanctions to that GP --}}
            <div class="mb-3" id="pre-san">

            </div>
            
            {{-- New Gram Panchayat Check --}}
            <div class="mb-3 row">
                <label for="newGP" class="form-label col-md-5">Whether this Gram Panchayat is newly created?</label>
                <div class="col-md-1 d-flex align-items-center ml-2">
                    <input type="radio" id="yes" name="newGP" value="Yes" class="form-check-input">
                    <label for="yes" class="form-check-label ml-2">Yes</label>
                </div>
                <div class="col-md-1 d-flex align-items-center ml-2">
                    <input type="radio" id="no" name="newGP" value="No" class="form-check-input">
                    <label for="no" class="form-check-label ml-2">No</label>
                </div>
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
                <option value="State Finance">State Finance</option>
                <option value="RGSA">RGSA</option>
            </select>
            {{-- <input type="text" name="sanction_head" id="sanction_head" class="form-control"> --}}
           </div>
           {{-- Purpose of sanction --}}
           <div class="mb-3">
            <label for="purpose of sanction" class="form-label">Purpose of Sanction</label>
            <select name="sanction_purpose" id="sanction_purpose" class="form-control" name="sanction_purpose">
                <option value="-1">Select Sanction Purpose</option>
                <option value="New Panchayat Ghar">New Panchayat Ghar</option>
                <option value="Repair and Upgradation of Panchayat Ghar">Repair and Upgradation of Panchayat Ghar</option>
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
            $('#gp-block').on("change", "#panchayat-list", function() {
                let selectedDistrict = $("#district-list").val();
                let selectedBlock = $("#block-list").val();
                let selectedGramPanchayat = $(this).val();
                let selectedConstituency = data.data[selectedDistrict][selectedBlock][selectedGramPanchayat][0];
                let constituencyList = "<label for='Constituency' class='form-label'>Constituency Name:</label>";
                constituencyList += "<input type='text' id='selected-constituency' value='" + selectedConstituency + "' class='form-control' name='ac' readonly>";
                $('#constituency-block').html(constituencyList);
                 $.ajax({
                   url:'/dir/view-sanction',
                   type:'GET',
                    data:{
                        district:$('#district-list').val(),
                        block:$('#block-list').val(),
                        gp:selectedGramPanchayat
                    },
                    success:function(sanctions)
                    {  
                        if(sanctions.message)
                        {
                            $('#pre-san').html("No previous sanction found for the specified parameters");
                        }
                        else
                        {
                            let prev_sanction="<h4>Previous Sancton(s) to this Gram Panchayat:</h4><table class='table table-bordered text-center'><thead><tr><th>Sr. No</th><th>Financial Year</th><th>Sanction Date</th><th>Amount</th></tr></thead><tbody>";
                            $.each(sanctions,function(index,san){
                            prev_sanction+=`<tr><td>${index+1}</td><td>${san.financial_year}</td><td>${san.sanction_date}</td><td>${san.san_amount}</td></tr>`;
                        });
                            prev_sanction+="</tbody></table>";
                            $('#pre-san').html(prev_sanction);
                        }
                        
                    },
                    error:function(jqXHR,testStatus,errorThrown)
                    {
                        if(jqXHR.status === 404)
                        {
                            $('#pre-san').html("<h4 class='text-center' style='border:1px solid black'>No previous sanction found for the specified parameters</h4>");
                        }
                        else
                        {
                            $('#pre-san').html("<h4 class='text-center' style='border:1px solid black'>An error occurred while retrieving data. Please try again.</h4>");
                        }
                    }
                });
            });
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

    function displayPanchayats(panchayats) {
        var panchayatList = '<label for="Gram Panchayat name" class="form-label">Select Gram Panchayat Name</label><select id="panchayat-list" class="form-control" name="gp"><option value="-1">--Select Gram Panchayat--</option>';
        $.each(panchayats, function(panchayat, constituencies) {
            panchayatList += '<option value="' + panchayat + '">' + panchayat + '</option>';
        });
        panchayatList += '</select>';
        $("#gp-block").html(panchayatList);
    }
</script>

<script src="{{asset('assets/js/validation.js')}}"></script>
@endsection