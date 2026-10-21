@extends('auth.layouts.default')

@section('view_title', 'Tatu Dubem Eventos')

@section('description', 'Login Page')
@section('keywords', 'login')

@section('css')
@endsection

@section('scripts')
@endsection

@section('content')
    <div class="container py-4">
        <div class="w-100 m-auto d-flex justify-content-center">
            <form method="POST" action="{{ route('login') }}" class="text-center">
                @csrf

                <a href="{{ config('app.url') }}">
                    <img src="{{ asset('img/tatudubem.svg') }}" class="logo" alt="{{ config('app.name') }}">
                </a>
                <p class="my-3">Entre com seus dados para fazer o login</p>

                @if($errors->all())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('message') }}
                    </div>
                @endif

                <div class="form-floating my-2">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput" required focus autocomplete="username">
                    <label for="floatingInput">{{ __('Email') }}</label>
                    @error('email')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating my-2">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" required  autocomplete="current-password">
                    <label for="floatingPassword">{{__('Password') }}</label>
                    @error('password')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                  </div>
                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ __('Remember me') }}
                    </label>
                </div>

                @if(Route::has('password.request'))
                    <div class="form-floating text-end my-3">
                        <a class="underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif

                <button class="btn btn-dark w-100 py-2" type="submit">{{ __('Log in') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
@endsection
