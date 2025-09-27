@extends('layouts/district')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Change Sanctions</h3>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{$error}}</div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-body">
        <form action="{{url('/district/update-sanction/'.$sanction->id)}}"  id="sanction" method="POST">
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
                    <option value="{{$sanction->district}}">{{$sanction->district}}</option>
                </select>
            </div>
            {{-- Block Name --}}
            <div class="mb-3" id="blocks-block">
                <label for="Block name" class="form-label">Select Block Name</label>
                <select id="block-list" class="form-control" name="block">
                    @foreach ($blocks as $key=>$value)
                     <option value="{{$key}}"  {{$key==$sanction->block?'selected':''}}>{{$key}}</option>
                    @endforeach
                </select>
            </div>
             {{-- GramPanchayat Name --}}
             <div class="mb-3" id="gp-block">
                <label for="Gram Panchayat name" class="form-label">Select Gram Panchayat</label>
                <select id="panchayat-list" class="form-control" name="gp">
                    <option value="{{$sanction->gp}}">{{$sanction->gp}}</option>
                </select>
            </div>

            {{-- New Gram Panchayat Check --}}
            <div class="mb-3 row">
                <label for="newGP" class="form-label col-md-5">Whether this Gram Panchayat is newly created?</label>
                <div class="col-md-1 d-flex align-items-center ml-2">
                    <input type="radio" id="yes" name="newGP" value="Yes" class="form-check-input" {{$sanction->newGP=='yes'?"checked":''}}>
                    <label for="yes" class="form-check-label ml-2">Yes</label>
                </div>
                <div class="col-md-1 d-flex align-items-center ml-2">
                    <input type="radio" id="no" name="newGP" value="No" class="form-check-input" {{$sanction->newGP=='no'?"checked":''}}>
                    <label for="no" class="form-check-label ml-2">No</label>
                </div>
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
                <option value="MPLAD" {{$sanction->sanction_head=='MPLAD'?'selected':''}}>MPLAD</option>
                <option value="MLALAD" {{$sanction->sanction_head=='MLALAD'?'selected':''}}>MLALAD</option>
                <option value="SDP" {{$sanction->sanction_head=='SDP'?'selected':''}}>SDP</option>
                <option value="BASP" {{$sanction->sanction_head=='BASP'?'selected':''}}>BASP</option>
                <option value="VKVNY" {{$sanction->sanction_head=='VKVNY'?'selected':''}}>VKVNY</option>
                <option value="RGSA" {{$sanction->sanction_head=='RGSA'?'selected':''}}>RGSA</option>
                <option value="State Finance" {{$sanction->sanction_head=='State Finance'?'selected':''}}>State Finance</option>
            </select>
           </div>
           {{-- Purpose of sanction --}}
           <div class="mb-3">
            <label for="purpose of sanction" class="form-label">Purpose of Sanction</label>
            <select name="sanction_purpose" id="sanction_purpose" class="form-control" name="sanction_purpose">
                <option value="-1">Select Sanction Purpose</option>
                <option value="New Panchayat Ghar" {{$sanction->sanction_purpose=='New Panchayat Ghar'?'selected':''}}>New Panchayat Ghar</option>
                <option value="Repair and Upgradation of Panchayat Ghar" {{$sanction->sanction_purpose=='Repair and Upgradation of Panchayat Ghar'?'selected':''}}>Repair and Upgradation of Panchayat Ghar</option>
            </select>
           </div>

           {{-- Upload file of sanction --}}
           <div class="mb-3">
            <label for="Sanction File" class="form-label">Sanction File</label>
            <input type="file" id="san_sign_pdf" name="san_sign_pdf" accept="application/pdf" class="form-control">
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
                // Handle district selection
                let selectedDistrict=$('#district-list option').attr('value');
            $("#blocks-block").on("change", "#block-list", function() {
                var selectedDistrict = $('#district-list option').attr('value');
                var selectedBlock = $(this).val();
                displayPanchayats(data.data[selectedDistrict][selectedBlock]);
            });
            // Handle block selection
            $("#blocks-block").on("click", "#block-list", function() {
                var selectedDistrict = $("#district-list").val();
                var selectedBlock = $(this).val();
                displayPanchayats(data.data[selectedDistrict][selectedBlock]);
            });
            // Handle Gram Panchayat Selection
            $('#gp-block').on("change", "#panchayat-list", function() {
                let selectedDistrict = $("#district-list").val();
                let selectedBlock = $("#block-list").val();
                let selectedGramPanchayat = $(this).val();
                let selectedConstituency = data.data[selectedDistrict][selectedBlock][selectedGramPanchayat][0];
                let constituencyList = "<label for='Constituency' class='form-label'>Constituency Name:</label>";
                constituencyList += "<input type='text' id='selected-constituency' value='" + selectedConstituency + "' class='form-control' name='ac' readonly>";
                $("#constituency-block").html(constituencyList);
            });
        });
        });
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
<script src="{{asset('assets/js/validation.js')}}"></script>
@endsection