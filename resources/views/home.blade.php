@extends('layouts.laip')

@section('content')
    <!-- Header -->    
    <header>
        <div class="container container-img-menu">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md 4">
                    <a class="item" href="{{ url('/sujeto_obligado') }}">
                        <img src="{{ URL::asset('img/so.jpg') }}"/>
                        <span class="text-center">Sujeto Obligado</span>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md 4">
                    <a class="item" href="{{ url('/sector') }}">
                        <img src="{{ URL::asset('img/sector.jpg') }}"/>
                        <span class="text-center">Sector</span>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md 4">
                    <a class="item" href="{{ url('/numeral') }}">
                        <img src="{{ URL::asset('img/numeral.jpg') }}"/>
                        <span class="text-center">Numeral</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <div class="main-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-text">
                        <span class="name">Guia Ciudadana sobre Acceso a la Información</span>
                    </div>
					<div data-configid="29954868/51120561" style="width:100%; height:406px;" class="issuuembed"></div>
					<script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>
                </div>
            </div>
        </div>    
    </div>
@endsection
