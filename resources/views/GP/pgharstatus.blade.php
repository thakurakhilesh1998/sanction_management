@extends('layouts/gp')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Gram Panchayat Home Page</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <h3>Current Status of Panchayat Ghar</h3>
        @if(session('message'))
            <h3>{{session('message')}}</h3>
        @endif
            <form action="{{url('gp/uploadimg')}}" method="POST" id="gp_status" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="" class="form-label">No of Rooms in Panchayat Ghar &nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" name="rooms" id="rooms">
                </div>
                <div class="mb-3">
                    <label for="Panchayt Ghar Latitue" class="form-label">Panchayat Ghar Latitude &nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="lat" name="lat">
                    <small>Enter the Latitude in the format e.g. 12.431243</small>
                </div>
                <div class="mb-3">
                    <label for="Panchayt Ghar Longitude" class="form-label">Panchayat Ghar Longitude &nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="long" name="long">
                    <small>Enter the Longitude in the format e.g. 12.431243</small>
                </div>
                <div class="mb-3">
                    <label for="Panchayat Ghar Status report" class="form-label">Image of Panchayat Ghar &nbsp;<span style="color: red">*</span></label>
                    <input type="file" class="form-control" id="pgharupload" name="p_image" accept="image/jpeg, image/jpg, image/png">
                    <small>Image of size upto 400 KB is allowed.</small>
                </div>
                <div class="mb-3">
                    <label for="Remarks" class="form-label">Remarks (if any, e.g., Panchayat Ghar is on rent or located in another building etc.)</label>
                    <input type="text" id="remarks" name="remarks" class="form-control">
                    </label>
                </div>
                    <input type="submit" class="btn btn-primary" value="Save" id="uploadimg">
            </form>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/gpstatus_validation.js')}}"></script>    
</script>
@endsection