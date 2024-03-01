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
        <h3>View Status of Panchayat Ghar</h3>
        <h2>Update Status of Panchayat Ghar</h2>
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