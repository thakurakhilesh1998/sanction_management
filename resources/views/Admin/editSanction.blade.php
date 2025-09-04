@extends('layouts/admin')
@section('main')
<div class="container">
    <h3>Edit Sanction</h3>
    <form method="POST" action="{{ route('admin.update', $sanction->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Financial Year</label>
            <input type="text" name="financial_year" class="form-control" value="{{ $sanction->financial_year }}" required>
        </div>
        <div class="mb-3">
            <label>District Name</label>
            <input type="text" name="district" class="form-control" value="{{ $sanction->district }}" required>
        </div>
        <div class="mb-3">
            <label>Block Name</label>
            <input type="text" name="block" class="form-control" value="{{ $sanction->block }}" required>
        </div>
        <div class="mb-3">
            <label>Gram Panchayat</label>
            <input type="text" name="gp" class="form-control" value="{{ $sanction->gp }}" required>
        </div>
        <div class="mb-3">
            <label>New Gram Panchayat</label>
            <select name="newGP" id="newGP" class="form-control">
                <option value="-1">--Select Whether new GP or old?</option>
                <option value="Yes" {{ $sanction->newGP == 'yes' ? 'selected' : '' }}>Yes</option>
                <option value="No"  {{ $sanction->newGP == 'no'  ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Amount</label>
            <input type="text" name="san_amount" class="form-control" value="{{ $sanction->san_amount }}" required>
        </div>
        <div class="mb-3">
            <label>Sanction Date</label>
            <input type="text" name="sanction_date" class="form-control" value="{{ $sanction->sanction_date }}" required>
        </div>
        <div class="mb-3">
            <label>Sanction Head</label>
            <select name="sanction_head" id="sanction_head" class="form-control">
                    <option value="-1">--Select Head--</option>
                    <option value="State Finance" {{$sanction->sanction_head=='State Finance'?'selected':''}}>State Finance</option>
                    <option value="RGSA" {{$sanction->sanction_head=='RGSA'?'selected':''}}>RGSA</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Purpose</label>
            <select name="sanction_purpose" id="sanction_purpose" class="form-control" name="sanction_purpose">
                <option value="-1">Select Sanction Purpose</option>
                <option value="New Panchayat Ghar" {{$sanction->sanction_purpose=='New Panchayat Ghar'?'selected':''}}>New Panchayat Ghar</option>
                <option value="Repair and Upgradation of Panchayat Ghar" {{$sanction->sanction_purpose=='Repair and Upgradation of Panchayat Ghar'?'selected':''}}>Repair and Upgradation of Panchayat Ghar</option>
            </select>
        </div>
        <div class="mb-3">
                <label>Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="-1">--Select Head--</option>
                    <option value="xen" {{$sanction->status=='xen'?'selected':''}}>xen</option>
                    <option value="gp" {{$sanction->status=='gp'?'selected':''}}>gp</option>
                    <option value="bdo" {{$sanction->status=='bdo'?'selected':''}}>bdo</option>
                </select>
        </div>
        <div class="mb-3">
            <label for="Delete UC">Are you really want to delete UC?</label>
            <select name="deleteuc" id="deleteuc" class="form-control">
                    <option value="-1">--Select Head--</option>
                    <option value="yes">Yes</option>
                     <option value="no">No</option>
            </select>
        </div>
            <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
