@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="text-center pb-5">{{ __('Login') }}</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="text-muted">{{ __('E-Mail Address') }}</label>

                    <input id="email" type="email" class="py-4 form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="E-mail" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="text-muted">{{ __('Password') }}</label>

                    <input id="password" type="password" class="py-4 form-control form-control-lg @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <div class="form-group text-center">
                    <span class="text-muted">Not a member? </span><a href="{{ route('register') }}">{{ __('Sign up') }}</a>
                </div>
            </form>
                
        </div>
    </div>
</div>
@endsection
