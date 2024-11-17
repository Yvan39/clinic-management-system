@extends('nova::auth.layout')

@section('content')

@include('nova::auth.partials.header')

<form
    class="bg-white shadow rounded-lg p-8 max-w-login mx-auto"
    method="POST"
    action="{{ route('nova.login') }}"
>
    {{ csrf_field() }}

    @component('nova::auth.partials.heading')
        {{ __('Welcome Back!') }}
    @endcomponent

    @if ($errors->any())
    <p class="text-center font-semibold text-danger my-3">
        @if ($errors->has('email'))
            {{ $errors->first('email') }}
        @else
            {{ $errors->first('password') }}
        @endif
        </p>
    @endif

    <div class="mb-6 {{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="block font-bold mb-2" for="email">{{ __('Email Address') }}</label>
        <input class="form-control form-input form-input-bordered w-full" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>

    <div class="mb-6 relative {{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="block font-bold mb-2" for="password">{{ __('Password') }}</label>
        
        <input class="form-control form-input form-input-bordered w-full pr-10" id="password" type="password" name="password" required>
        
        <!-- Eye icon for toggling password visibility -->
        <span class="absolute right-3 top-10 cursor-pointer" onclick="togglePasswordVisibility()">
            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#6c757d">
                <path d="M12 4.5c-7.333 0-11 7.5-11 7.5s3.667 7.5 11 7.5 11-7.5 11-7.5-3.667-7.5-11-7.5zm0 13c-3.038 0-5.5-2.462-5.5-5.5s2.462-5.5 5.5-5.5 5.5 2.462 5.5 5.5-2.462 5.5-5.5 5.5zm0-9c-1.933 0-3.5 1.567-3.5 3.5s1.567 3.5 3.5 3.5 3.5-1.567 3.5-3.5-1.567-3.5-3.5-3.5z"/>
            </svg>
        </span>
    </div>

    <div class="flex mb-6">
        <label class="flex items-center text-xl font-bold">
            <input class="" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span class="text-base ml-2">{{ __('Remember Me') }}</span>
        </label>

        @if (\Laravel\Nova\Nova::resetsPasswords())
        <div class="ml-auto">
            <a class="text-primary dim font-bold no-underline" href="{{ route('nova.password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        </div>
        @endif
    </div>

    <button class="w-full btn btn-default btn-primary hover:bg-primary-dark" type="submit">
        {{ __('Login') }}
    </button>
</form>
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