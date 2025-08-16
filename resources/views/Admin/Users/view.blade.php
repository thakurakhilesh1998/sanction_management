@extends('layouts/admin')
@section(section: 'main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container text-black">
        @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3 class="h3 mb-3 text-gray-800">View User
                    <a href="{{url('admin/user')}}" class="btn btn-primary btn-sm float-right">Add User</a>
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="datatable">
                        <thead>
                            <th>Id</th>
                            <th>Usernmae</th>
                            <th>Email Id</th>
                            <th>Role</th>
                            <th>District</th>
                            <th>Edit</th>
                        </thead>
                        <tbody>
                            @foreach ($user as $u)
                                <tr>
                                    <td>{{$u->id}}</td>
                                    <td>{{$u->username}}</td>
                                    <td>{{$u->email}}</td>
                                    <td>{{$u->role}}</td>
                                    <td>{{$u->district}}</td>
                                    <td><a href="{{url('admin/user-edit').'/'.$u->id}}" class="btn btn-primary">Edit</a></td> 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
