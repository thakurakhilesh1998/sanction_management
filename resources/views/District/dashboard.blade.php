@extends('layouts/district')
@section('main')
<div class="card mx-2">
    <div class="card-header">
        <h3>Dashboard</h3>
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Total Sanction Amount --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden bg-primary text-white mb-4">
                    <div class="card-body">
                        Total Funds Received
                        <h3>Rs.{{$totalFundRecived}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            {{-- Total Utilized Amount --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden bg-info text-white mb-4">
                    <div class="card-body">
                        Total Utilized Amount
                        <h3>Rs.{{$totalUtilized}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Total Number of Sanctions --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden bg-warning  text-white mb-4">
                    <div class="card-body">
                        Total Sanctions Count
                        <h3>{{$sanctionCount}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            {{-- Total Number of Sanctions --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden bg-info text-white mb-4">
                    <div class="card-body">
                        Freezed Sanctions
                        <h3>{{$freezedSanction}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('district/update')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Not reported Sanctions --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden bg-success text-white mb-4">
                    <div class="card-body">
                        Not Reported Sanctions
                        <h3>{{$notReported}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('/district/')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Fund Sanctions for New GP --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden bg-secondary text-white mb-4">
                    <div class="card-body">
                       Sanctions For New Gram Panchayat
                        <h3>{{$totalNewGP}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/view')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
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
            }, 500); // You can adjust the delay time (in milliseconds) as needed
        });
</script>
@endsection
