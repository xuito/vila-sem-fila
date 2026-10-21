@extends('site.layouts.default')

@section('view_title', 'Vila Sem Fila')

@section('description', '')
@section('keywords', '')

@section('css')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.maskedinput.js') }}"></script>
@endsection

@section('content')
    <div class="container pt-4 pb-5">
        <div class="row justify-content-center mb-4">
            <div class="col col-sm-8 col-md-6 col-lg-4">
                <div class="card border-0">
                    <h4 class="card-header bg-dark text-light text-center">Confira seu consumo e pague sua conta sem entrar na fila!</h4>
                    <div class="card-body p-4">
                        <form name="bill_create_form" action="{{ route('site.bill.store') }}" method="post">
                            @csrf
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

                            <div class="form-group mb-3">
                                <label for="ticket" class="lead mb-2">Pulseira com 4 dígitos:</label>
                                <input type="text" name="ticket" id="ticket" class="form-control form-control-lg fs-4 number" value="{{ old('ticket') }}" required autofocus>
                            </div>
                            <div class="form-group mb-3">
                                <label for="cpf" class="lead mb-2">Os 4 primeiros dígitos do CPF:</label>
                                <input type="password" name="cpf" id="cpf" class="form-control form-control-lg fs-4 number" required>
                            </div>
                            <div class="d-flex justify-content-evenly align-items-center mt-4 mb-2">
                                <button type="submit" class="btn btn-lg btn-dark w-100">CONSULTAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col col-sm-8 col-md-6 col-lg-4">
                <div class="card border-0">
                    <div class="card-body px-4 text-center fw-bold">
                        <i class="material-icons align-text-bottom">location_on</i> {{ mb_strtoupper(session()->get('storeCity')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(function() {
            $('.number').mask('9999', {placeholder:'', autoclear: true});
        });
    </script>
@endsection
