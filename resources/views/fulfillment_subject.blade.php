@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container">
        <h1>Nivel de Cumplimiento General de los Sujetos Obligados</h1>
        <h2>{{ $round->name }}</h2>
        <div class="row">
            <div class="panel-group" id="topSo">
                <div class="panel">
                    <div class="panel-heading">
                        <h4><a data-toggle="collapse" data-parent="#topSo" href="#topSoDetail"><span class="glyphicon glyphicon-plus"></span>Sujetos Obligados con cumplimiento de 85% en adelante ({{ sizeof($highTr) }})</a></h4>
                        <div class="progress-border">
                            <div class="progress-green progress-bar" style="height:24px;width:{{ $proms[0] }}%">{{ number_format($proms[0],2) }}%</div>
                        </div>
                    </div>
                    <div id="topSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach ($highTr as $h)
									<div class="subject col-xs-12">
										<h5><a href="{{ url('/sujeto', $h->subject->id) }}">{{ $h->subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-green progress-bar" style="height:24px;width:{{ $h->score }}%">{{ $h->score }}%</div>
										</div>
									</div>
								@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-group" id="middleSo">
                <div class="panel">
                    <div class="panel-heading">
                        <h4><a data-toggle="collapse" data-parent="#middleSo" href="#middleSoDetail"><span class="glyphicon glyphicon-plus"></span>Sujetos Obligados con cumplimiento entre 60% y 84.9% ({{ sizeof($mediumTr) }})</a></h4>
                        <div class="progress-border">
                            <div class="progress-yellow progress-bar" style="height:24px;width:{{ $proms[1] }}%">{{ number_format($proms[1],2) }}%</div>
                        </div>
                    </div>
                    <div id="middleSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach ($mediumTr as $m)
									<div class="subject col-xs-12">
										<h5><a href="{{ url('/sujeto', $m->subject->id) }}">{{ $m->subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-yellow progress-bar" style="height:24px;width:{{ $m->score }}%">{{ $m->score }}%</div>
										</div>
									</div>
								@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-group" id="lowerSo">
                <div class="panel">
                    <div class="panel-heading">
                        <h4><a data-toggle="collapse" data-parent="#lowerSo" href="#lowerSoDetail"><span class="glyphicon glyphicon-plus"></span>Sujetos Obligados con cumplimiento menor a 60% ({{ sizeof($lowTr) }})</a></h4>
                        <div class="progress-border">
                            <div class="progress-red progress-bar" style="height:24px;width:{{ $proms[2] }}%">{{ number_format($proms[2],2) }}%</div>
                        </div>
                    </div>
                    <div id="lowerSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach ($lowTr as $l)
									<div class="subject col-xs-12">
										<h5><a href="{{ url('/sujeto', $l->subject->id) }}">{{ $l->subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-red progress-bar" style="height:24px;width:{{ $l->score }}%">{{ $l->score }}%</div>
										</div>
									</div>
								@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
