@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <div class="container text-black">
        <div class="card">
           @if(session('success'))
            <div class="alert alert-success" id="successMessage">
                <strong>Success!</strong> Sanction Added successfully.
            </div>
            @endif
            <div class="card-header">
                <h3 class="h3 mb-3 text-gray-800">View Progress for Sanction</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="datatable">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>District Name</th>
                                <th>Block Name</th>
                                <th>Gram Panchayat</th>
                                <th>Update Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Bilaspur</td>
                                <td>Ghumarwin</td>
                                <td>GP Name</td>
                                <td><a href="">Update</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
