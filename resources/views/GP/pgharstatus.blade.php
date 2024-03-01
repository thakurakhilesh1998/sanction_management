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
        <h3>Update Current Status of Panchayat Ghar</h3>
            <form action="{{url('gp/uploadimg')}}" method="POST" id="gp_status" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="Panchayat Ghar Status report" class="form-label">Image(s) of Panchayat Ghar</label>
                    <input type="file" class="form-control" id="pgharupload" name="p_image[]" accept="image/jpeg, image/jpg, image/png" multiple>
                    <small>Maximum 3 images can be uploaded and maximum allowed image size is of 400kb</small>
                </div>
                    <input type="submit" class="btn btn-primary" value="Upload" id="uploadimg">
            </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
   
   $(document).ready(function () {
            $('#gp_status').submit(function(event)
            {
                if(!validateForm())  
                {
                    event.preventDefault();
                }
            });

            function validateForm()
            {
                let isValid=true;
                let images=$('#pgharupload')[0];
                let files=images.files;
                if(files.length>3)
                {
                    isValid=false;
                    $("#pgharupload").next(".error").remove();
                    $("#pgharupload").after("<span class='error'>You can upload only 3 images.</span>");
                    return isValid;
                }
                for(let i=0;i<files.length;i++)
                {
                    let file=files[i];
                    if(file.size>400*1024)
                    {
                        isValid=false;
                        $("#pgharupload").next(".error").remove();
                        $("#pgharupload").after("<span class='error'>Image size should be less than 400Kb.</span>");
                        return isValid;
                    }
                    let allowedTypes=['image/jpeg', 'image/jpg', 'image/png'];

                    if(allowedTypes.indexOf(file.type)===-1)
                    {
                        isValid=false;
                        $("#pgharupload").next(".error").remove();
                        $("#pgharupload").after("<span class='error'>Images of only jpeg, jpg and png are allowed.</span>");
                        return isValid;
                    }
                }
            $("#pgharupload").next(".error").remove();
            return isValid;
            }
        });
</script>
@endsection