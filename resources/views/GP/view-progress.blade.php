@extends('layouts/gp')
@section('main')
<div class="card m-4">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
        @endforeach
    </div>
    @endif
    <div class="card-header">
        <h4>View Sanction Progress</h4>
    </div>
    <div class="card-body bg-light">
        <div class="container">
            <div class="row my-1">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">District Name:</h5>
                    <p class="text-muted">{{$data->district}}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Block Name:</h5>
                    <p class="text-muted">{{$data->block}}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Gram Panchayat Name:</h5>
                    <p class="text-muted">{{$data->gp}}</p>
                </div>
            </div>
            <div class="row my-1">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Sanction Amount:</h5>
                    <p class="text-muted">₹{{$data->san_amount}}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Financial Year:</h5>
                    <p class="text-muted">{{$data->financial_year}}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Sanction Head:</h5>
                    <p class="text-muted">{{$data->sanction_head}}</p>
                </div>
            </div>
            <div class="row my-1">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">View UC:</h5>
                    <a href="{{url('uploads/ucs/'.$data->progress[0]->p_uc)}}" target="_blank" class="text-primary">View UC</a>
                </div>
            </div>
            <div class="row my-1">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">View Images:</h5>
                    <div class="d-flex flex-row">
                        @foreach($data->progress[0]->image as $img) 
                            <a href="{{url('uploads/images/'.$img->image_path)}}" target="_blank" class="mr-3"><img src="{{url('uploads/images/'.$img->image_path)}}" style="height: 120px;width:120px; border-radius: 8px;" alt="images"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


