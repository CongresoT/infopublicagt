@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container">
        <div class="row" style="text-align:justify;">
            <div class="col-xs-12">
				<h2>Descargas</h2>
                <h3>Bases de datos de monitoreos</h3>
                <ul>
                @foreach($rounds as $round)
                    <li><a href="{{ url('/descargas', $round->id) }}">Datos para {{ $round->name }}</a></li>
                @endforeach
                </ul>
                <h3>Información de sujetos obligados</h3>
                <ul>
                    <li><a href="{{ url('/descargas/so') }}">Información de Sujetos Obligados</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
