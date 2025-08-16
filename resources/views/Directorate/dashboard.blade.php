@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Directorate Home Page</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Total Sanction Amount --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden text-white mb-4" style="background-color: #003f5c">
                    <div class="card-body">
                        Total Sanctions Amount
                        <h3>{{addCommas($totalFundReleased)}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/view-progress')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            {{-- Total Utilized Amount --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden text-white mb-4" style="background-color: #2f4b7c">
                    <div class="card-body">
                        Total Utilized Amount
                        <h3>{{addCommas($sumUtilized)}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/view-progress')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Total Number of Sanctions --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden text-white mb-4" style="background-color: #665191">
                    <div class="card-body">
                        Total Sanctions for Gram Panchayats
                        <h3>{{$sanctionCount}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/view')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            {{-- Total Number of Sanctions --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden text-white mb-4" style="background-color: #2f4b7c">
                    <div class="card-body">
                        Total Work Completed
                        <h3>{{$totalCompletedWorks}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/completed-work')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            {{-- Fund Sanctions for New GP --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden text-white mb-4" style="background-color: #003f5c">
                    <div class="card-body">
                        Total Sanctions For New Gram Panchayat
                        <h3>{{$totalNewGPs}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/new-gp-sanction/newGp')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            {{-- Total Works which are delayed for more than 100 days --}}
            <div class="col-xl-4 col-md-6">
                <div class="card cardt hidden text-white mb-4" style="background-color: #2f4b7c">
                    <div class="card-body">
                        Work not reported for more than 100 days
                        <h3>{{$totalDelayDays}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{url('dir/new-gp-sanction/delay')}}">View Details</a>
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