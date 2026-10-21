@extends('responses.layouts.default')

@section('view_title', $data->title)

@section('view_content')
    <div style="padding: 20px; text-align: center;">
        A requisição não pode ser concluída em {{ formatDate(date('Y-m-d H:i:s'), "dd/MM/yyyy 'às' HH:mm:ss") }}.
    </div>
@endsection
