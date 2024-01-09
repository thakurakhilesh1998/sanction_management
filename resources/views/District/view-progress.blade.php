@extends('layouts/district')
@section('main')
<div class="card m-4">
   @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
   @endif
        @if($errors->has('error'))
            <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="card-header">
        <h4>View Progress</h4>
    </div>
    <div class="card-body">
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function()
{
    $('#uc').hide();
    $("select#isCompleted").change(function()
    {
        let selectedOption=$(this).children('option:selected').val();
        if(selectedOption=='yes')
        {
            $('#uc').show();
        }
        else
        {
            $('#uc').hide();
        }
    })
});
</script>
<script src="{{asset('assets/js/progress_validation.js')}}"></script>
@endsection

