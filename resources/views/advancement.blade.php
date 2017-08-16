@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container">
        <h1>Comparativo entre rondas</h1>
        <h2>{{ $round->name }} - {{ $round_previous->name }}</h2>
		<div class="subjectInfo row">
            <div class="panel-group" id="topSo">
                <div class="panel">
                    <div class="panel-heading">
                        <h4><a data-toggle="collapse" data-parent="#topSo" href="#topSoDetail"><span class="glyphicon glyphicon-plus"></span>Sujetos obligados con avance</a></h4>
                        <div class="progress-border">
                            <div class="progress-green progress-bar" style="height:24px;width:{{ $upPerc }}%">{{ $upPerc }}% de los sujetos obligados</div>
                        </div>
                    </div>
                    <div id="topSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach($subjectsUp as $subject)
									<div class="col-xs-8 subjectRow">
										<h5><a href="{{ url('/sujeto', $subject->id) }}">{{  $subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-blue progress-bar" style="height:24px;width:{{ $subject->new_score }}%"><span class="progress-text">{{ $round->name }} - {{ number_format($subject->new_score,2) }}%</span></div>
										</div>
										<div class="progress-border">
											<div class="progress-gray progress-bar" style="height:24px;width:{{ $subject->old_score }}%"><span class="progress-text">{{ $round_previous->name }} - {{ number_format($subject->old_score,2) }}%</span></div>
										</div>
									</div>
									<div class="arrow-up col-xs-1"></div>
									<div class="progress-qty-green col-xs-1">{{ $subject->advancement }}%</div>
								@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-group" id="middleSo">
                <div class="panel">
                    <div class="panel-heading">
                        <h4><a data-toggle="collapse" data-parent="#middleSo" href="#middleSoDetail"><span class="glyphicon glyphicon-plus"></span>sujetos obligados sin avance</a></h4>
                        <div class="progress-border">
                            <div class="progress-yellow progress-bar" style="height:24px;width:{{ $equalPerc }}%">{{ $equalPerc }}% de los sujetos obligados</div>
                        </div>
                    </div>
                    <div id="middleSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach($subjectsEqual as $subject)
									<div class="col-xs-8 subjectRow">
										<h5><a href="{{ url('/sujeto', $subject->id) }}">{{  $subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-blue progress-bar" style="height:24px;width:{{ $subject->new_score }}%"><span class="progress-text">{{ $round->name }} - {{ number_format($subject->new_score,2) }}%</span></div>
										</div>
										<div class="progress-border">
											<div class="progress-gray progress-bar" style="height:24px;width:{{ $subject->old_score }}%"><span class="progress-text">{{ $round_previous->name }} - {{ number_format($subject->old_score,2) }}%</span></div>
										</div>
									</div>
									<div class="progress-qty-yellow col-xs-1">=</div>
									<div class="progress-qty-yellow col-xs-1">{{ $subject->advancement }}%</div>
								@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-group" id="lowerSo">
                <div class="panel">
                    <div class="panel-heading">
                        <h4><a data-toggle="collapse" data-parent="#lowerSo" href="#lowerSoDetail"><span class="glyphicon glyphicon-plus"></span>Sujetos obligados con retroceso</a></h4>
                        <div class="progress-border">
                            <div class="progress-red progress-bar" style="height:24px;width:{{ $downPerc }}%">{{ $downPerc }}% de los sujetos obligados</div>
                        </div>
                    </div>
                    <div id="lowerSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach($subjectsDown as $subject)
									<div class="col-xs-8 subjectRow">
										<h5><a href="{{ url('/sujeto', $subject->id) }}">{{  $subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-blue progress-bar" style="height:24px;width:{{ $subject->new_score }}%"><span class="progress-text">{{ $round->name }} - {{ number_format($subject->new_score,2) }}%</span></div>
										</div>
										<div class="progress-border">
											<div class="progress-gray progress-bar" style="height:24px;width:{{ $subject->old_score }}%"><span class="progress-text">{{ $round_previous->name }} - {{ number_format($subject->old_score,2) }}%</span></div>
										</div>
									</div>
									<div class="arrow-down col-xs-1"></div>
									<div class="progress-qty-red col-xs-1">{{ $subject->advancement }}%</div>
								@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
