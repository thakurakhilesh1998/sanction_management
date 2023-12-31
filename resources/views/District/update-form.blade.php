@extends('layouts/district')
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
        <h4>Update Sanction Progress</h4>
    </div>
    <div class="card-body">
        <div class="" >
            <div class="row my-2">
                <div class="col-md-4"><strong>District Name: {{$sanction->district}}</strong></div>
                <div class="col-md-4"><strong>Block Name: {{$sanction->block}}</strong></div>
                <div class="col-md-4"><strong>Gram Panchayat Name: {{$sanction->gp}}</strong></div>
            </div>
            <div class="row my-2">
                <div class="col-md-4"><strong>Sanction Amount: ₹{{$sanction->san_amount}}</strong></div>
                <div class="col-md-4"><strong>Financial Year: {{$sanction->financial_year}}</strong></div>
                <div class="col-md-4"><strong>Sanction Head: {{$sanction->sanction_head}}</strong></div>
            </div>
        </div>
        <div class="">
            <form enctype="multipart/form-data" id="progress_form" method="POST" action="{{url('district/update-progress/'.$progress[0]->id)}}">
                @csrf
                @method('PUT')
                <input type="hidden" class="form-control" name="sanction_id" value="{{$sanction->id}}">
                <div class="mb-3">
                    <label for="Work Completed" class="form-label">Is Sanction amount fully utilized?</label>
                    <select name="p_isComplete" id="isCompleted" class="form-control">
                        <option value="-1">--Select Option--</option>
                        <option value="yes" {{$progress[0]->p_isComplete=='yes'?'selected':''}}>Yes</option>
                        <option value="no" {{$progress[0]->p_isComplete=='no'?'selected':''}}>No</option>
                    </select>
                </div>
                <div class="mb-3" id="uc">
                    <label for="UC file" class="form-label">Upload UC file(only pdf file allowed and PDf file size should be less than 2MB)</label>
                    <input type="file" class="form-control" id="uc_file" name="p_uc" accept="application/pdf" value="{{$progress[0]->p_uc}}">
                    @if($progress[0]->p_uc)
                    <a href="{{ url('uploads/ucs/' . $progress[0]->p_uc) }}" target="_blank">View UC</a>
                @endif
                </div>
                <div class="mb-3" id="completion_per">
                    <label for="Progress Percentage" class="form-label">Percentage of Work Completed</label>
                    <input type="text" class="form-control" id="p_completed_per" name="completion_percentage">
                  </div>
                <div class="mb-3">
                    <label for="Progress Image" class="form-label">Update Progress Image</label>
                    <input type="file" id="imageInput" name="p_image[]" accept="image/jpeg, image/jpg, image/png" multiple>
                    @if(sizeof($images)>0)
                    <!-- Display existing image previews here -->
                        @foreach($images as $img)
                            <img src="{{ url('uploads/images/'.$img->image_path) }}" alt="Progress Image" class="img-fluid" style="height:200px;width:200px">
                        @endforeach
                    @endif
                    <br>
                    <small>Allowed formats: jpg, jpeg, png and maximum 3 images are allowed.</small>
                </div>
                <div class="mb-3">
                    <label for="Remarks" class="form-label">Remarks</label>
                    <textarea type="text" name="remarks" id="remarks" class="form-control">{{$progress[0]->remarks}}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Progress</button>
              </form>
        </div>
        
    </div>
    
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function()
{
    $('#uc').hide();
    $('#completion_per').hide();

     if($("select#isCompleted").children('option:selected').val()==='yes')
     {
        $('#completion_per').hide();
        $('#uc').show();
     }
     else if($("select#isCompleted").children('option:selected').val()==='no')
     {
        $('#completion_per').show();
        $('#uc').hide();
     }
     else
     {
        $('#uc').hide();
        $('#completion_per').hide();
     }
    $("select#isCompleted").change(function()
    {
        let selectedOption=$(this).children('option:selected').val();
        if(selectedOption=='yes')
        {
            $('#uc').show();
            $('#completion_per').hide();
        }
        else if(selectedOption=='no')
        {
            $('#completion_per').show();
            $('#uc').hide();
        }
        else
        {   
            $('#completion_per').hide();
            $('#uc').hide();
        }
    })
});
</script>
<script src="{{asset('assets/js/progress_update_validation.js')}}"></script>
@endsection

