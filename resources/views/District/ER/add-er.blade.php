@extends('layouts/district')

@section('main')
<div class="card mx-2 shadow">

    {{-- Header --}}
    <div class="card-header text-white d-flex align-items-center"
         style="background: linear-gradient(90deg, #0d6efd, #198754);">
        <h4 class="mb-0">
            <i class="bi bi-grid-fill me-2"></i>
            Elected Representative Dashboard
        </h4>
    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Zila Parishad --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center hover-card">
                    <div class="card-body">
                        <div class="icon-box bg-primary text-white mb-3">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5 class="fw-bold text-primary">Zila Parishad</h5>
                        <a href="{{ url('district/add-zila-parishad') }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            Proceed
                        </a>
                    </div>
                </div>
            </div>

            {{-- Panchayat Samiti --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center hover-card">
                    <div class="card-body">
                        <div class="icon-box bg-warning text-dark mb-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold text-warning">Panchayat Samiti</h5>
                        <a href="{{ url('district/add-panchayat-samiti') }}"
                           class="btn btn-outline-warning btn-sm w-100">
                            Proceed
                        </a>
                    </div>
                </div>
            </div>

            {{-- Gram Panchayat --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center hover-card">
                    <div class="card-body">
                        <div class="icon-box bg-success text-white mb-3">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                        <h5 class="fw-bold text-success">Gram Panchayat</h5>
                        <a href="{{ url('district/add-gram-panchayat') }}"
                           class="btn btn-outline-success btn-sm w-100">
                            Proceed
                        </a>
                    </div>
                </div>
            </div>

            {{-- Ward Members --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center hover-card">
                    <div class="card-body">
                        <div class="icon-box bg-danger text-white mb-3">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                        <h5 class="fw-bold text-danger">Ward Members</h5>
                        <a href="{{ url('add-ward-members') }}"
                           class="btn btn-outline-danger btn-sm w-100">
                            Proceed
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- Custom Styles --}}
<style>
.icon-box {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 24px;
}

.hover-card {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
</style>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@endsection