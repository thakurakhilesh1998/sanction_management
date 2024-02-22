@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container text-black">
        <h1 class="h3 mb-3 text-gray-800">Add User</h1>
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{url('admin/add-user')}}">
            @csrf
            <div class="mb-3">
              <label for="Username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" aria-describedby="username" name="username">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password" name="password_confirmation">
            </div>
            <div class="mb-3">
                <label for="User Role" class="form-label">Select UserRole</label>
                <select name="role" id="role" class="form-control">
                    <option value="0">--Select Role--</option>
                    <option value="admin">Admin</option>
                    <option value="dir">Directorate</option>
                    <option value="district">District</option>
                    <option value="block">Block</option>
                    <option value="gp">Gram Panchayat</option>
                </select>
            </div>

            <div class="mb-3" id="districts_select">
                <label for="District Name" class="form-label">Select District</label>
                <select name="district" id="" class="form-control">
                    <option value="0">--Select District--</option>
                    <option value="Bilaspur">Bilaspur</option>
                    <option value="Chamba">Chamba</option>
                    <option value="Hamirpur">Hamirpur</option>
                    <option value="Kangra">Kangra</option>
                    <option value="Kinnaur">Kinnaur</option>
                    <option value="Kullu">Kullu</option>
                    <option value="Lahul And Spiti">Lahul And Spiti</option>
                    <option value="Mandi">Mandi</option>
                    <option value="Shimla">Shimla</option>
                    <option value="Sirmaur">Sirmaur</option>
                    <option value="Solan">Solan</option>
                    <option value="Una">Una</option>
                </select>
            </div>

            <div class="mb-3" id="block_select">

            </div>
            <div class="mb-3" id="gp_select">

            </div>
            <button type="submit" class="btn btn-primary">Create Username</button>
          </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function()
    {
        $('#districts_select').hide();
        $("select#role").change(function()
        {
            let selectedRole=$(this).children("option:selected").val();
            if(selectedRole==='district')
            {
                $("#districts_select").show();
            }
            else
            {
                $("#districts_select").hide();
            }

        })
    })
</script>
@endsection
