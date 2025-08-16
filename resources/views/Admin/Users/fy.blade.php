@extends('layouts.admin')
@section('main')
<div class="container-fluid text-black">

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    @endif
    @if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
    @endif

    <div class="m-3">
        <h3>Configure Financial Year here</h3>
        <form action="{{url('admin/add-fy')}}" method="POST">
            @csrf
            <div class="mb-3" style="width: 50%">
                <label for="Username" class="form-label"><strong>Enter Current Financial Year</strong><small>&nbsp;(Only enter data in format 202X-XX e.g. 2025-26)</small></label>
                <input type="text" class="form-control" id="financial_year" aria-describedby="username" name="financial_year" autocomplete="off" required value="{{$current}}">
            </div>
            <button type="submit" class="btn btn-primary">Update Financial Year</button>
        </form>
    </div>
</div>
@endsection