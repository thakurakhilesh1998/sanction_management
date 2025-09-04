@extends('layouts/app')
@section('content')
<div class="container">
    {{-- KPI Card layout --}}
    <div class="container my-2">
        <h3 class="py-2"><b>Key Performance Indicators</b></h3>
        <div class="card-body">
            <div class="row">
                {{-- Total Sanction Amount --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card cardt hidden text-white mb-4" style="background-color: #003f5c">
                        <div class="card-body">
                            Total Sanctions Amount
                            <h3>{{addCommas($totalSanction)}} </h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            {{-- <a class="small text-white stretched-link" href="{{url('/details/sanction')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div> --}}
                        </div>
                    </div>
                </div>
                {{-- Total Utilized Amount --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card cardt hidden text-white mb-4" style="background-color: #2f4b7c">
                        <div class="card-body">
                            Total Utilized Amount
                            <h3>{{addCommas($utilizedSan)}}</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            {{-- <a class="small text-white stretched-link" href="{{url('/details/utilized')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div> --}}
                        </div>
                    </div>
                </div>

                {{-- Total Number of Sanctions --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card cardt hidden text-white mb-4" style="background-color: #665191">
                        <div class="card-body">
                            Total Sanctions Count
                            <h3>{{$sanctionCount}}</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            {{-- <a class="small text-white stretched-link" href="{{url('/details/')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div> --}}
                        </div>
                    </div>
                </div>
                {{-- Total Number of Sanctions --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card cardt hidden text-white mb-4" style="background-color: #665191">
                        <div class="card-body">
                            Total Utilized Sanctions
                            <h3>{{$utilizedSan}}</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            {{-- <a class="small text-white stretched-link" href="{{url('/details/utilized')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div> --}}
                        </div>
                    </div>
                </div>
                {{-- Fund Sanctions for New GP --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card cardt hidden text-white mb-4" style="background-color: #003f5c">
                        <div class="card-body">
                            Total Sanctions For New Gram Panchayat
                            <h3>{{$newGP}}</h3>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            {{-- <a class="small text-white stretched-link" href="{{url('/details/newGp')}}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Introduction Section --}}
    <div class="card" style="font-family: sans-serif;">
        <div class="card-header">
            <h3 class="text-center">Sanction Management System</h3>
        </div>  
        <div class="row card-body">
            <div class="col md-4 m-1 p-1">
                <p class="text-center">
                    Introducing the Sanction Management System, a cutting-edge initiative by the Department of Panchayati Raj, Government of Himachal Pradesh. Crafted with precision, this software is designed to streamline the allocation of funds sanctioned by the department to districts under the State Finance Commission.
                </p>
            </div>
            <div class="col-md-4 m-1 p-1">
                <p class="text-center">
                    Our sophisticated system not only simplifies fund management for both the Directorate office and District offices but also introduces a new level of transparency and efficiency to governance. Districts now have the capability to seamlessly upload Utilization Certificates (UC) and images associated with the sanctioned funds, offering a comprehensive view of financial utilization.
                </p>
            </div>
            <div class="col-md-4 m-1 p-1">
                <p class="text-center">
                    What sets us apart is our commitment to public accessibility. The software opens up a wealth of information, making crucial parameters available to the public domain. Citizens can effortlessly access details about their District, Block, and Gram Panchayat, fostering a governance model that is transparent, efficient, and citizen-friendly. Empowering communities with information, we believe in transforming governance for the betterment of all."
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
   $(document).ready(function () {
            // Add a class to each card to hide them initially
            $('.cardt').addClass('hidden');

            // Use a setTimeout to delay the appearance of the cards
            setTimeout(function () {
                // Remove the 'hidden' class to show the cards with a fade-in effect
                $('.cardt').removeClass('hidden');
            }, 400); // You can adjust the delay time (in milliseconds) as needed
        });
</script>
@endsection