@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container text-black">
        <h1 class="h3 mb-3 text-gray-800">Change Password</h1>
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="email" class="form-label">Enter Current Password</label>
              <input type="password" class="form-control" id="crntpass" name="crntpass">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password" name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
          </form>
    </div>
</div>
@endsection
