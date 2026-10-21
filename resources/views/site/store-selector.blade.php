@extends('site.layouts.default')

@section('view_title', 'Vila Sem Fila')

@section('description', '')
@section('keywords', '')

@section('css')
@endsection

@section('scripts')
@endsection

@section('content')
<div class="container pt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col col-sm-8 col-md-6 col-lg-4">
            <div class="card border-0">
                <h4 class="card-header bg-dark text-light text-center">Pague sua conta sem pegar fila! Escolha a unidade:</h4>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-evenly align-items-center my-2 w-100">
                        <a href="{{ route('site.store.redirect', ['slug' => 'rio-preto']) }}" class="w-100"><button class="btn btn-lg btn-dark w-100"><i class="material-icons align-text-bottom">location_on</i> RIO PRETO</button></a>
                    </div>
                    <div class="d-flex justify-content-evenly align-items-center my-3 w-100">
                        <a href="{{ route('site.store.redirect', ['slug' => 'ribeirao']) }}" class="w-100"><button class="btn btn-lg btn-dark w-100"><i class="material-icons align-text-bottom">location_on</i> RIBEIRÃO</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
@endsection
