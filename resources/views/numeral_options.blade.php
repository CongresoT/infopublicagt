@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container container-img-menu">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <a class="item" href="{{ url('/cumplimiento_num') }}">
                    <img src="img/cumplimiento.jpg"/>
                    <span class="text-center">Nivel de cumplimiento por numeral específico</span>
                </a>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <a class="item" href="{{ url('/lista_numeral') }}">
                    <img src="img/numeral_especifico.jpg"/>
                    <span class="text-center">Vista por numeral espec&iacute;fico</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
