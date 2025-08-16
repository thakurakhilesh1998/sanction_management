@extends('layouts/gp')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Gram Panchayat Home Page</h3>
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
                        Total Sanctions Received
                        <h3>{{$sanctionCount}}</h3>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="">View Details</a>
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