@extends('layouts/admin')
@section('main')
<div class="card m-4">
    <div class="card-body">
    <h3>Dashboard</h3>
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    </div>
    @endif
    <div class="row">
        {{-- Total Sanction Amount --}}
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    Total User
                    <h3>{{$user}}</h3>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{url('admin/user/view')}}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
