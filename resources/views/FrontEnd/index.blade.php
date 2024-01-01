@extends('layouts/app')
@section('content')
<div class="container">
    {{-- Introduction Section --}}
    <div class="card" style="font-family: sans-serif;">
        <div class="card-header">
            <h3>Sanction Management System</h3>
        </div>  
        <div class="row card-body">
            <div class="col md-4 card m-1 p-1">
                <p>
                    Introducing the Sanction Management System, a cutting-edge initiative by the Department of Panchayati Raj, Government of Himachal Pradesh. Crafted with precision, this software is designed to streamline the allocation of funds sanctioned by the department to districts under the State Finance Commission.
                </p>
            </div>
            <div class="col-md-4 card m-1 p-1">
                <p>
                    Our sophisticated system not only simplifies fund management for both the Directorate office and District offices but also introduces a new level of transparency and efficiency to governance. Districts now have the capability to seamlessly upload Utilization Certificates (UC) and images associated with the sanctioned funds, offering a comprehensive view of financial utilization.
                </p>
            </div>
            <div class="col-md-4 card m-1 p-1">
                <p>
                    What sets us apart is our commitment to public accessibility. The software opens up a wealth of information, making crucial parameters available to the public domain. Citizens can effortlessly access details about their District, Block, and Gram Panchayat, fostering a governance model that is transparent, efficient, and citizen-friendly. Empowering communities with information, we believe in transforming governance for the betterment of all."
                </p>
            </div>
        </div>
    </div>
    {{-- Key Parameters Section --}}
    
    {{-- KPI Card layout --}}
    <div class="container my-2">
        <h3 class="py-2"><b>Key Performance Indicators</b></h3>
        <div class="card-body">
            <div class="row">
                {{-- Total Sanction Amount --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            Total Sanctions Amount
                            <h3>Rs. </h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{url('/details/utilized')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                {{-- Total Utilized Amount --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            Total Utilized Amount
                            <h3>Rs. </h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{url('dir/view-progress')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Total Number of Sanctions --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-warning  text-white mb-4">
                        <div class="card-body">
                            Total Sanctions Count
                            <h3>10</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{url('dir/view')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                {{-- Total Number of Sanctions --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            Total Utilized Sanctions
                            <h3>12</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{url('dir/view/freeze')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                {{-- Fund Sanctions for New GP --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            Total Sanctions For New Gram Panchayat
                            <h3>0</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{url('dir/view/newgp')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection