@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container text-black">
        <h1 class="h3 mb-3 text-gray-800">Edit User</h1>
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{url('admin/user-edit/'.$user->id)}}" id="edituser">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="Username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" aria-describedby="username" name="username" value="{{$user->username}}" autocomplete="off">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                <small>Password must be 8 of character, must have 1 Uppercase,1 Lowercase, one Digit and 1 Special character.</small>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control password_confirmation" id="password" name="password_confirmation" autocomplete="off">
                <small>Password must be 8 of character, must have 1 Uppercase,1 Lowercase, one Digit and 1 Special character.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
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

    $('form').on('submit',async function(event){
        event.preventDefault();
        const password=$('#password').val();
        const passwordConfirm=$('.password_confirmation').val();
        const {encryptedPassword:encryptedP,iv:ivp,tag:tagp}=await encryptPassword(password);
        const {encryptedPassword:encryptedC,iv:ivc,tag:tagc}=await encryptPassword(passwordConfirm);
        $('#password').val(encryptedP);
        $('.password_confirmation').val(encryptedC);

        $('<input>').attr({ type: 'hidden', name: 'iv_p', value: ivp }).appendTo('#edituser');
        $('<input>').attr({ type: 'hidden', name: 'iv_cnf', value: ivc }).appendTo('#edituser');

        $('<input>').attr({ type: 'hidden', name: 'tag_p', value: tagp }).appendTo('#edituser');
        $('<input>').attr({ type: 'hidden', name: 'tag_cnf', value: tagc }).appendTo('#edituser');

        this.submit();
    });
</script>
<script>
    $(document).ready(function()
    {
        $('#districts_select').hide();
        $("select#role").change(function()
        {
            let selectedRole=$(this).children("option:selected").val();
            if(selectedRole==='district')
            {
               $("#districts_select").show();
            }
            else
            {
                $("#districts_select").hide();
            }

        })
    })
</script>
@endsection
