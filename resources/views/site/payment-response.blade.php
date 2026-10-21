@extends('site.layouts.default')

@section('view_title', 'Tatu Dubem Eventos')

@section('description', '')
@section('keywords', '')

@section('css')
@endsection

@section('scripts')
@endsection

@section('content')
    <div class="container pt-3 pb-5">
        <div class="row border-bottom py-3 align-items-start">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="lead text-center mb-3">
                            {{ $message }} Acompanhe abaixo o andamento dos seus pedidos:
                        </div>
                        <div class="d-flex justify-content-evenly align-items-center mt-2">
                            <a href="{{ route('client.orders') }}" class="w-100">
                                <button class="btn btn-dark w-100">Meus Pedidos</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
@endsection
