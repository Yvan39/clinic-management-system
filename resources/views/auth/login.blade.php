@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Field -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Field with Eye Icon -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6 position-relative">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                
                                <!-- Eye Icon using SVG -->
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility()" style="cursor: pointer;">
                                    <svg id="eye-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4.5C7.305 4.5 3.23 7.43 1.5 12c1.73 4.57 5.805 7.5 10.5 7.5 4.695 0 8.77-2.93 10.5-7.5-1.73-4.57-5.805-7.5-10.5-7.5zm0 13c-3.038 0-5.5-2.462-5.5-5.5S8.962 6.5 12 6.5 17.5 8.962 17.5 12s-2.462 5.5-5.5 5.5zm0-9a3.5 3.5 0 100 7 3.5 3.5 0 000-7z" fill="#6c757d"/>
                                    </svg>
                                </span>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me and Submit Button -->
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button and Forgot Password Link -->
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePasswordVisibility() {
        const password = document.getElementById("password");
        const eyeIcon = document.getElementById("eye-icon");
        if (password.type === "password") {
            password.type = "text";
            eyeIcon.style.fill = "#007bff"; // Change icon color when password is visible
        } else {
            password.type = "password";
            eyeIcon.style.fill = "#6c757d"; // Revert icon color
        }
    }
</script>
@endsection