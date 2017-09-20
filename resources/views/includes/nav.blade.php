   <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!--img src="{{ URL::asset('img/infopublicagt.png') }}" /-->
                    {{ config('app.name', 'InfoPublicaGt') }}
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="index.html"></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gráficas<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Por Sujeto Obligado</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/cumplimiento_so') }}">Nivel de Cumplimiento general</a></li>
                                    <li><a href="{{ url('/avance') }}">Avance entre las últimas dos rondas</a></li>
                                    <li><a href="{{ url('/lista_so') }}">Vista por sujeto obligado específico</a></li>
                                </ul>
                            </li>
                            <li><a tabindex="-1" href="{{ url('/sector') }}">Por Sector</a></li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Por Numeral</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/cumplimiento_num') }}">Nivel de Cumplimiento por numeral</a></li>
                                    <li><a href="{{ url('/lista_numeral') }}">Vista por numeral específico</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url('/descargas') }}">Descargas</a>
                    </li>
                    <li>
                        <a href="{{ url('/metodologia') }}">Metodología</a>
                    </li>
                    <li>
                        <a href="{{ url('/informacion_publica') }}">¿Qué es la información pública?</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>