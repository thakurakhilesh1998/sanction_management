@extends('layouts/xen')
@section('main')
<div class="card m-4">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    </div>
    @endif
    <div class="card-header">
        <h4>Add Work Progress</h4>
    </div>
    <div class="card-body">
        <div class="" >
            <div class="row my-2">
                <div class="col-md-4"><strong>District Name: {{$rdSanction[0]->district}}</strong></div>
                <div class="col-md-4"><strong>Block Name: {{$rdSanction[0]->block}}</strong></div>
                <div class="col-md-4"><strong>Work Name: {{$rdSanction[0]->work}}</strong></div>
                
            </div>
            <div class="row my-2">
                @php 
                  $sanAmount=0;      
                @endphp
                @foreach($rdSanction as $san)
                 @php   
                    $sanAmount+=$san->san_amount
                 @endphp
                @endforeach
                <div class="col-md-4"><strong>Total Sanction Amount: ₹{{$sanAmount}}</strong></div>
            </div>
        </div>
        <div class="">
            <form enctype="multipart/form-data" id="progress_form" method="POST" action="{{url('xen/add-progress-rd')}}">
                @csrf
                <input type="hidden" class="form-control" name="work" value="{{$rdSanction[0]->work}}">
                <input type="hidden" class="form-control" name="block" value="{{$rdSanction[0]->block}}">
                <input type="hidden" class="form-control" name="district" value="{{$rdSanction[0]->district}}">
                <div class="mb-3" id="completion_per">
                    <label for="Progress Percentage" class="form-label">Current Status of Work:</label>
                    <select name="completion_percentage" id="p_completed_per" class="form-control">
                        <option value="-1">--Select Status--</option>
                        <option value="Tender Floated">Tender Floated</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Remarks" class="form-label">Remarks</label>
                    <textarea type="text" name="remarks" id="remarks" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Progress</button>
              </form>
        </div> 
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function()
{
   
});
</script>
<script src="{{asset('assets/js/progress_validation.js')}}"></script>
@endsection

