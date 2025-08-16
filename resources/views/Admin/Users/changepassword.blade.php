@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <div class="container text-black">
        <h1 class="h3 mb-3 text-gray-800">Change Password</h1>
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{url('admin/change-password')}}" id="changePassForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="current_password" class="form-label">Enter Current Password</label>
              <input type="password" class="form-control" id="current_password" name="current_password">
            </div>
            <div class="mb-3">
                <label for="password1" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password1" name="new_password">
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="new_password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
          </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    async function encryptPassword(password) {
        const encoder = new TextEncoder();
        const data = encoder.encode(password);
        const aesKey = '0d78c5f79ece7388c918eac45a7aad89';
        const keyData = new TextEncoder().encode(aesKey);
        const key = await crypto.subtle.importKey('raw', keyData, 'AES-GCM', false, ['encrypt']);
        
        const iv = crypto.getRandomValues(new Uint8Array(12));
        const encrypted = await crypto.subtle.encrypt({ name: 'AES-GCM', iv: iv }, key, data);
        
        const encryptedArray = new Uint8Array(encrypted);
        const tag = encryptedArray.slice(-16);
        const cipherText = encryptedArray.slice(0, -16);

        return {
            encryptedPassword: btoa(String.fromCharCode(...cipherText)),
            iv: btoa(String.fromCharCode(...iv)),
            tag: btoa(String.fromCharCode(...tag))
        };
    }

    $('#changePassForm').on('submit', async function(event) {
        event.preventDefault();

        if (!validateForm()) return;

        const currentPasswordField = $('#current_password');
        const newPasswordField = $('#password1');
        const confirmPasswordField = $('#password2');

        const { encryptedPassword: encryptedCurrent, iv: ivCurrent, tag: tagCurrent } = await encryptPassword(currentPasswordField.val());
        const { encryptedPassword: encryptedNew, iv: ivNew, tag: tagNew } = await encryptPassword(newPasswordField.val());
        const { encryptedPassword: encryptedConfirm, iv: ivConfirm, tag: tagConfirm } = await encryptPassword(confirmPasswordField.val());

        currentPasswordField.val(encryptedCurrent);
        newPasswordField.val(encryptedNew);
        confirmPasswordField.val(encryptedConfirm);

        $('<input>').attr({ type: 'hidden', name: 'iv_current', value: ivCurrent }).appendTo('#changePassForm');
        $('<input>').attr({ type: 'hidden', name: 'iv_new', value: ivNew }).appendTo('#changePassForm');
        $('<input>').attr({ type: 'hidden', name: 'iv_confirm', value: ivConfirm }).appendTo('#changePassForm');

        $('<input>').attr({ type: 'hidden', name: 'tag_current', value: tagCurrent }).appendTo('#changePassForm');
        $('<input>').attr({ type: 'hidden', name: 'tag_new', value: tagNew }).appendTo('#changePassForm');
        $('<input>').attr({ type: 'hidden', name: 'tag_confirm', value: tagConfirm }).appendTo('#changePassForm');

        this.submit();
    });

    function validateForm() {
        let isValid = true;
        let currentPassword = $('#current_password').val();
        let newPassword = $('#password1').val();
        let confirmPassword = $('#password2').val();

        if (currentPassword.length < 8) {
            isValid = false;
            $('#current_password').next('.error').remove();
            $("#current_password").after("<span class='error'>Password should be at least 8 characters.</span>");
        } else {
            $('#current_password').next('.error').remove();
        }

        if (newPassword.length < 8) {
            isValid = false;
            $('#password1').next('.error').remove();
            $("#password1").after("<span class='error'>Password should be at least 8 characters.</span>");
        } else {
            $('#password1').next('.error').remove();
        }

        if (confirmPassword.length < 8) {
            isValid = false;
            $('#password2').next('.error').remove();
            $("#password2").after("<span class='error'>Password should be at least 8 characters.</span>");
        } else {
            $('#password2').next('.error').remove();
        }

        if (newPassword !== confirmPassword) {
            isValid = false;
            $('#password1').next('.error').remove();
            $("#password1").after("<span class='error'>New Password and Confirm Password should match.</span>");
        } else {
            $('#password1').next('.error').remove();
        }

        return isValid;
    }
</script>
@endsection
