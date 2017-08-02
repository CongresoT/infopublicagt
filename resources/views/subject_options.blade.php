@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container container-img-menu">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md 4">
                <a class="item" href="{{ url('/cumplimiento_so') }}">
                    <img src="img/cumplimiento.jpg"/>
                    <span class="text-center">Nivel de cumplimiento general de los Sujetos Obligados</span>
                </a>
            </div>
            <div class="col-xs-12 col-sm-4 col-md 4">
                <a class="item" href="{{ url('/avance') }}">
                    <img src="img/avance.jpg"/>
                    <span class="text-center">Avance entre las últimas dos rondas</span>
                </a>
            </div>
            <div class="col-xs-12 col-sm-4 col-md 4">
                <a class="item" href="{{ url('/lista_so') }}">
                    <img src="img/so_especifico.jpg"/>
                    <span class="text-center">Vista por Sujeto Obligado espec&iacute;fico</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
