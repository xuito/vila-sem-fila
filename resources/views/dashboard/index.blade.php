@extends('dashboard.layouts.default')

@section('view_title', 'Grupo Vila Dionísio')

@section('description', '')
@section('keywords', '')

@section('css')
    <style>
        .material-icons { font-size: 48px; }
    </style>
@endsection

@section('scripts')
@endsection

@section('content')
    <div class="container py-4">
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

        <div class="row row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-4 px-2 pb-5">
            @foreach($sectorsLinks as $sectorLinks => $links)
                @can($links[0]->sector_permission)
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <i class="material-icons">{{ $links[0]->sector_icon }}</i>
                                </div>
                                <div class="fs-2">
                                    {{ mb_strtoupper($links[0]->sector_description) }}
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @foreach($links as $link)
                                        @can($link->sector_link_permission)
                                            <li class="list-group-item"><a href="{{ route($link->sector_link) }}">{{ $link->link_description }}</a></li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endcan
            @endforeach
        </div>
    </div>
@endsection

@section('footer_scripts')
@endsection
