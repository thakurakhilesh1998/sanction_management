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
             <div class="alert alert-success">{{session('message')}}</div>
        @endif
            <form action="{{url('gp/updatestatus'.'/'.$gpGhar[0]->id)}}" method="POST" id="gp_status" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @php 
                    $hasData=$gpGhar->isNotEmpty();
                    $roomValue=$hasData ? $gpGhar[0]->rooms:'';
                    $latValue=$hasData ? $gpGhar[0]->lat:'';
                    $longValue=$hasData ? $gpGhar[0]->long:'';
                    $image_path=$hasData ? $gpGhar[0]->image_path:'';
                    $remarks=$hasData ? $gpGhar[0]->remarks:'';
                @endphp
                <div class="mb-3">
                    <label for="" class="form-label">No of Rooms in Panchayat Ghar &nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" name="rooms" id="rooms" value="{{old('rooms',$roomValue)}}">
                </div>
                <div class="mb-3">
                    <label for="Panchayt Ghar Latitue" class="form-label">Panchayat Ghar Latitude &nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="lat" name="lat" value="{{old('lat',$latValue)}}">
                    <small>Enter the Latitude in the format e.g. 12.431243</small>
                </div>
                <div class="mb-3">
                    <label for="Panchayt Ghar Longitude" class="form-label">Panchayat Ghar Longitude &nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="long" name="long" value="{{old('long',$longValue)}}">
                    <small>Enter the Longitude in the format e.g. 12.431243</small>
                </div>
                <div class="mb-3">
                    <label for="Panchayat Ghar Status report" class="form-label">Image of Panchayat Ghar &nbsp;<span style="color: red">*</span></label>
                    <input type="file" class="form-control" id="pgharupload" name="p_image" accept="image/jpeg, image/jpg, image/png">
                    @if($image_path)    
                    <div>
                        <a href="{{ url('uploads/pghar_images/' . $image_path) }}" target="_blank">View Uploaded Image</a>
                    </div>
                    @endif
                    <small>Image of size upto 400 KB is allowed.</small>
                </div>
                <div class="mb-3">
                    <label for="Remarks" class="form-label">Remarks (if any, e.g., Panchayat Ghar is on rent or located in another building etc.)</label>
                    <input type="text" id="remarks" name="remarks" class="form-control" value="{{old('remarks',$remarks)}}">

                </div>
                    <input type="submit" class="btn btn-primary" value="Update" id="uploadimg">
            </form>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/gpstatus_validation_update.js')}}"></script>    
</script>
@endsection