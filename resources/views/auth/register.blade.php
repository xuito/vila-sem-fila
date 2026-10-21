@extends('auth.layouts.default')

@section('view_title', 'Tatu Dubem Eventos')

@section('description', 'Login Page')
@section('keywords', 'login')

@section('css')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.maskedinput.js') }}"></script>

    <!-- Ajax -->
    <script>
        $(function ()
        {
            $('.ajax_on_blur').blur(function() //Busca qualquer formulario com a classe
            {
                $.ajax({
                    url: "{{ route('zipcode') }}",
                    type: "post",
                    data: $('#form_register').serialize(),
                    dataType: "json",
                    success: function(response)
                    {
                        if(response.success === true)
                        {
                            //console.log(response);
                            var streetAddress = response.logradouro;
                            var district = response.bairro;
                            var city = response.localidade;
                            var uf = response.uf;
                            //var ddd = response.ddd;

                            $("#floatingStreetAddress").val(streetAddress);
                            $("#floatingDistrict").val(district);
                            $("#floatingCity").val(city);
                            $("#floatingUF").val(uf);
                            //$("#floatingTel").val(ddd);
                        }
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <div class="container py-4">
        <div class="w-75 m-auto d-flex justify-content-center">
            <form method="POST" name="form_register" id="form_register" action="{{ route('register') }}" class="text-center">
                @csrf
                <a href="{{ config('app.url') }}">
                    <img src="{{ asset('img/tatudubem.svg') }}" class="logo" alt="{{ config('app.name') }}">
                </a>
                <p class="my-3">Preencha os campos abaixo para se registrar na plataforma</p>

                @if(session()->has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('message') }}
                    </div>
                @endif

                <div class="form-floating my-2">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="floatingName" value="{{ old('name') }}" required focus autocomplete="name">
                    <label for="floatingName">{{ __('Name') }}</label>
                    @error('name')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating my-2">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="floatingEmail" value="{{ old('email') }}" required autocomplete="username">
                    <label for="floatingEmail">{{ __('Email') }}</label>
                    @error('email')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating my-2">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" required autocomplete="new-password">
                    <label for="floatingPassword">{{ __('Password') }}</label>
                    @error('password')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating my-2">
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="floatingPasswordConfirmation" required autocomplete="new-password">
                    <label for="floatingPasswordConfirmation">{{ __('Confirm Password') }}</label>
                    @error('password_confirmation')
                        <div class="invalid-feedback text-end">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mt-4 mb-2">
                    <input type="text" name="organization" class="form-control" id="floatingCompanyName" value="{{ old('organization') }}" required autocomplete="organization">
                    <label for="floatingCompanyName">Razão Social</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="trade" class="form-control" id="floatingTrade" value="{{ old('trade') }}">
                    <label for="floatingTrade">Nome Fantasia (opcional)</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="stand" class="form-control" id="floatingStand" value="{{ old('Stand') }}">
                    <label for="floatingStand">Nome/Número do Stand (opcional)</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="cnpj" class="form-control cnpj" id="floatingCNPJ" value="{{ old('cnpj') }}" required>
                    <label for="floatingCNPJ">CNPJ</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="ie" class="form-control" id="floatingIE" value="{{ old('ie') }}">
                    <label for="floatingIE">Inscrição Estadual</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="zip_code" class="form-control cep ajax_on_blur" id="floatingZipCode" value="{{ old('zip_code') }}">
                    <label for="floatingZipCode">CEP</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="street_address" class="form-control" id="floatingStreetAddress" value="{{ old('street_address') }}" required autocomplete="street-address">
                    <label for="floatingCompanyName">Endereço Empresarial</label>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input type="text" name="address_number" class="form-control" id="floatingAddressNumber" value="{{ old('address_number') }}" required>
                            <label for="floatingAddressNumber">Número</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating mt-2 mt-sm-0">
                            <input type="text" name="complement" class="form-control" id="floatingComplement" value="{{ old('complement') }}">
                            <label for="floatingComplement">Complemento (opcional)</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="district" class="form-control" id="floatingDistrict" value="{{ old('district') }}" required>
                    <label for="floatingDistrict">Bairro</label>
                </div>
                <div class="row">
                    <div class="col-9">
                        <div class="form-floating">
                            <input type="text" name="city" class="form-control" id="floatingCity" value="{{ old('city') }}" required>
                            <label for="floatingCity">Cidade</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating">
                            <input type="text" name="uf" class="form-control" id="floatingUF" value="{{ old('uf') }}" maxlength="2" required>
                            <label for="floatingUF">UF</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="tel" class="form-control celular" id="floatingTel" value="{{ old('tel') }}" required>
                    <label for="floatingTel">Telefone</label>
                </div>
                <div class="d-flex justify-content-evenly align-items-center my-3">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                    <button class="btn btn-dark" type="submit">{{ __('Register') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $('.cnpj').mask('99.999.999/9999-99', {autoclear: false});
        $('.celular').mask('(99) 99999-9999', {autoclear: false});
        $('.cep').mask('99999-999', {autoclear: false});
    </script>
@endsection
