@extends('layouts/dir')
@section('main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container text-black">
        <h1 class="h3 mb-3 text-gray-800">Change Password</h1>
        @if(session('message'))
            <script>
                alert("Password Changed Successfully!")
            </script>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{url('dir/change-password')}}" id="changePassForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="email" class="form-label">Enter Current Password</label>
              <input type="password" class="form-control" id="current_password" name="current_password">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password1" name="new_password">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="new_password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
          </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function()
    {
        $('#changePassForm').submit(function(event)
        {
            if(!validateForm())
            {
                event.preventDefault();
            }
            
        });
 
        function validateForm()
        {
            let isValid=true;
            let currentPassword=$('#current_password').val();
            let newPassword=$('#password1').val();
            let confirmPassword=$('#password2').val();
            if(currentPassword.length<8)
            {
                isValid=false;
                $('#current_password').next('.error').remove();
                $("#current_password").after("<span class='error'>Password should be atleast of 8 Characters.</span>");
                return isValid;
            }
            else
            {
                $('#current_password').next('.error').remove();
            }

            if(newPassword.length<8)
            {
                isValid=false;
                $('#password1').next('.error').remove();
                $("#password1").after("<span class='error'>Password should be atleast of 8 Characters.</span>");
                return isValid;
            }
            else
            {
                $('#password1').next('.error').remove();
            }

            if(confirmPassword.length<8)
            {
                isValid=false;
                $('#password2').next('.error').remove();
                $("#password2").after("<span class='error'>Password should be atleast of 8 Characters.</span>");
                return isValid;
            }
            else
            {
                $('#password2').next('.error').remove();
            }

            if(newPassword!==confirmPassword)
            {
                isValid=false;
                $('#password1').next('.error').remove();
                $("#password1").after("<span class='error'>New Password and Confirm Password should be same.</span>");
                return isValid;
            }
            else
            {
                $('#password1').next('.error').remove();
            }

            return isValid;
        }
    });
</script>
@endsection