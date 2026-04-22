@extends('layouts/district')

@section('main')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card mx-2 shadow">

    {{-- Header --}}
    <div class="card-header text-white"
         style="background: linear-gradient(90deg, #0d6efd, #198754);">
        <h4 class="mb-0">
            <i class="bi bi-building me-2"></i>
            Add Gram Panchayat Elected Representatives
        </h4>
    </div>

    <div class="card-body">

        {{-- Error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- 🔷 Form --}}
        <form action="{{ route('storeGP') }}" method="POST">
            @csrf

            <div class="row">

                {{-- Ward No --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gram Panchayat Name</label>
                    <input type="text" name="gp_name" class="form-control" required>
                </div>

                {{-- Ward Name --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Panchayat Samiti Name</label>
                    <input type="text" name="ps_name" class="form-control" required>
                </div>

                {{-- Designation --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Designation</label>
                    <select name="designation" class="form-control" required>
                        <option value="">--Select--</option>
                        <option value="Pradhan">Pradhan</option>
                        <option value="Up-Pradhan">Up-Pradhan</option>
                    </select>
                </div>

                {{-- Name --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                {{-- Mobile --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" maxlength="10" required>
                </div>

                {{-- Address --}}
                <div class="col-md-8 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" required></textarea>
                </div>

                {{-- PIN Code --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">PIN Code</label>
                    <input type="number" name="pincode" class="form-control" required>
                </div>
                {{-- Reservation Status --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Reservation Status</label>
                    <select name="reservation_status" class="form-control" required>
                        <option value="">--Select--</option>
                        <option value="Unreserved">Unreserved</option>
                        <option value="SC">SC</option>
                        <option value="SC Woman">SC Woman</option>
                        <option value="ST">ST</option>
                        <option value="ST Woman">ST Woman</option>
                        <option value="OBC">OBC</option>
                        <option value="OBC Woman">OBC Woman</option>
                    </select>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn text-white"
                        style="background:#198754;">
                    Save Details
                </button>
            </div>

        </form>

        <hr class="my-4">

        {{-- 🔷 Excel Upload --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                Upload Data via Excel
            </div>

            <div class="card-body">

            <form action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row align-items-center">

            <div class="col-md-8">
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    Upload Excel
                </button>
        </div>
    </div>
    </form>
            </div>
        </div>

        <hr class="my-4">

<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        Added Zila Parishad Records
    </div>

    <div class="card-body">

        @if(isset($entries) && count($entries) > 0)

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">

                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Ward No</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Reservation</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($entries as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->ward_no }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->designation }}</td>
                            <td class="fw-bold text-primary">{{ $item->mobile }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $item->reservation_status }}
                                </span>
                            </td>
                            <td>{{ $item->address }}</td>
                            <td>
                                <form action="{{ route('gp.delete', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure to delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        @else
            <div class="alert alert-info text-center">
                No records found
            </div>
        @endif

    </div>
</div>
    </div>
</div>

{{-- Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection