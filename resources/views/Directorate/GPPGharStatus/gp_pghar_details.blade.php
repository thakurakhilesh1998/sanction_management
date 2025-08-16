@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Panchayat Ghar Detail</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card-custom">
                    <img src="{{asset('assets/img/rooms.png')}}" alt="rooms">
                    {{-- <img src="https://via.placeholder.com/150" alt="Image" class="img-fluid rounded-circle"> --}}
                    <h5>Total Rooms</h5>
                    <p>{{$gpDetails->pghar_image[0]->rooms}}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom">
                    <img src="{{asset('assets/img/location.png')}}" alt="rooms">
                    <h5>Geo-Location</h5>
                    <p>{{$gpDetails->pghar_image[0]->lat}}&nbsp;,{{$gpDetails->pghar_image[0]->long}}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom">
                    <img src="{{asset('assets/img/home.png')}}" alt="rooms">
                    <h5>Image</h5>
                    <p><a href="{{asset('uploads/pghar_images'.'/'.$gpDetails->pghar_image[0]->image_path)}}" target="_blank">View Image</a></p>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-md-4">
                <div class="card-custom">
                    <h5>Remarks</h5>
                    <p>
                        @if($gpDetails->pghar_image[0]->remarks == null)
                            No Remarks
                        @else
                            {{$gpDetails->pghar_image[0]->remarks}}
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection