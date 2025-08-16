@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
                @endif
                @if(session('message'))
                    <div class="alert alert-info">
                        {{ session('message') }}
                    </div>
                @endif
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="off" autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Hidden field for IV -->
                        <input type="hidden" name="iv" id="iv">

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="tag" id="tag">
                        <!-- CAPTCHA -->
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-3">
                                <div class="captcha-container p-3 d-flex align-items-center" style="background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
                                    <div class="captcha-box d-flex justify-content-center align-items-center p-2 me-3" style="background-color: #fff; border: 1px solid #ddd; border-radius: 5px; font-size: 1.2em; font-weight: bold; color: #007bff; width: 120px; text-align: center;">
                                        {{ $num1 }} + {{ $num2 }} = ?
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="text" name="captcha" id="captcha" class="form-control" placeholder="Enter CAPTCHA" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   async function encryptPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const aesKey = '0d78c5f79ece7388c918eac45a7aad89'; // Must be 32 bytes for AES-256
    const keyData = new TextEncoder().encode(aesKey);
    const key = await crypto.subtle.importKey('raw', keyData, 'AES-GCM', false, ['encrypt']);
    
    const iv = crypto.getRandomValues(new Uint8Array(12)); // 12-byte IV for AES-GCM
    const encrypted = await crypto.subtle.encrypt({ name: 'AES-GCM', iv: iv }, key, data);
    
    const encryptedArray = new Uint8Array(encrypted);
    const tag = encryptedArray.slice(-16); // Last 16 bytes are the tag
    const cipherText = encryptedArray.slice(0, -16);

    return {
        encryptedPassword: btoa(String.fromCharCode(...cipherText)),
        iv: btoa(String.fromCharCode(...iv)),
        tag: btoa(String.fromCharCode(...tag))
    };
}

$('#loginForm').on('submit', async function(event) {
    event.preventDefault();
    const passwordField = $('#password');
    const password = passwordField.val();

    const { encryptedPassword, iv, tag } = await encryptPassword(password);

    passwordField.val(encryptedPassword);
    $('#iv').val(iv); // Add hidden input for IV
    $('#tag').val(tag); // Add hidden input for Tag

    this.submit();
});
</script>
@endsection
