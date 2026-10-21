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
        <div class="w-50 m-auto d-flex justify-content-center">
            <form method="POST" action="{{ route('password.email') }}" class="text-center">
                @csrf

                <a href="{{ config('app.url') }}">
                    <img src="{{ asset('img/tatudubem.svg') }}" class="logo" alt="{{ config('app.name') }}">
                </a>
                <p class="my-3">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>

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
                <div class="form-floating my-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput" required focus>
                    <label for="floatingInput">{{ __('Email') }}</label>
                    @error('email')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-dark w-100 py-2" type="submit">
                    {{ __('Email Password Reset Link') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
@endsection
