@extends('layouts/app')
@section('content')
<div class="container">
    {{-- Introduction Section --}}
    <div class="card" style="font-family: sans-serif;">
        <div class="card-header">
    {{$sanction}}
            View Details
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="datatable">
                    <thead>
                        <th>District Name</th>
                        <th>Block Name</th>
                        <th>Gram Panchayat Name</th>
                        <th>Is New Gram Panchayat?</th>
                        <th>Financial Year</th>
                        <th>Sanction Amount</th>
                        <th>Sanction Date</th>
                        <th>Status</th>
                        <th>View Progress</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
   
</div>

@endsection