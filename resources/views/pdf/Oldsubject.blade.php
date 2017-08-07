@extends('layouts.laippdf')

@section('content')

		<div class="main-container">
			<div class="container">
				<h1>{{ $subject->name }}</h1>
				<div class="compInfo"><a href="{{ $subject->url }}" target="_blank">{{ $subject->url }}</a></div>
				<div class="compInfo">Unidad de acceso: {{ $subject->uaip_person }}</div>
				<div class="compInfo">Telefono: {{ $subject->phone }}</div>
				<div class="compInfo">Correo electr&oacute;nico: {{ $subject->email }}</div>
				<div class="row">
					<a href="#prueba">PRUEBA</a>
					<div class="col-xs-4">
						<div id="certificate" class="center-block">
							<span class="certificate center-block">{{ str_pad($ranking,2,'0',STR_PAD_LEFT) }}</span>
							<img style="vertical-align:middle" src="{{ url('img/medalla.png') }}">
						</div>
					</div>
					<div class="col-xs-4">
						<div id="piechart" class="center-block"></div>
					</div>
					<div class="col-xs-4">
						<div id="advancement" class="center-block">
							<div class="arrow-up-temp col-xs-1"></div>
							<div class="progress-qty-green col-xs-1" style="margin-left:10px;"></div>
						</div>
					</div>
					<div class="col-xs-12 center-block">
						<div id="scatterplot" class="center-block">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="panel-group" id="topSo">
						<div class="panel">
							<div class="panel-heading">
								<h4><a data-toggle="collapse" data-parent="#topSo" href="#topSoDetail"><span class="glyphicon glyphicon-plus"></span>Numerales con cumplimiento de 85% en adelante ({{ sizeof($topSo) }})</a></h4>
								<div class="progress-border">
									<div class="progress-green progress-bar" style="height:24px;width:{{ $promTop }}%">{{ number_format($promTop,2) }}%</div>
								</div>
							</div>
							<div id="topSoDetail" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="row">
										@foreach($topSo as $top)
											<div class="subject col-xs-12">
												<h5><a href="{{ url('/numeral', $top->numeral->id) }}">{{ $top->numeral->article->name }} - {{ $top->numeral->name }}</a></h5>
													{{ $top->numeral->script }}
												<div class="progress-border">
													<div @if($top->links <> '' ) data-toggle="tooltip" title="Informaci&oacute;n encontrada en {{ $top->links }}" @endif class="progress-green progress-bar" style="height:24px;width:{{ $top->score }}%">{{ $top->score }}%</div>
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
								<h4><a data-toggle="collapse" data-parent="#middleSo" href="#middleSoDetail"><span class="glyphicon glyphicon-plus"></span>Numerales con cumplimiento entre 60% y 84.9% ({{ sizeof($midSo) }})</a></h4>
								<div class="progress-border">
									<div  class="progress-yellow progress-bar" style="height:24px;width:{{ $promMid }}%">{{ number_format($promMid,2) }}%</div>
								</div>
							</div>
							<div id="middleSoDetail" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="row">
										@foreach($midSo as $mid)
											<div class="subject col-xs-12">
												<h5><a href="{{ url('/numeral', $mid->numeral->id) }}">{{ $mid->numeral->article->name }} - {{ $mid->numeral->name }}</a></h5>
													{{ $mid->numeral->script }}
												<div class="progress-border">
													<div @if($mid->links <> '' ) data-toggle="tooltip" title="Informaci&oacute;n encontrada en {{ $mid->links }}" @endif class="progress-yellow progress-bar" style="height:24px;width:{{ $mid->score }}%">{{ $mid->score }}%</div>
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
								<h4><a data-toggle="collapse" data-parent="#lowerSo" href="#lowerSoDetail"><span class="glyphicon glyphicon-plus"></span>Numerales con cumplimiento menor a 60% ({{ sizeof($lowSo) }})</a></h4>
								<div class="progress-border">
									<div class="progress-red progress-bar" style="height:24px;width:{{ $promLow }}%">{{ number_format($promLow,2) }}%</div>
								</div>
							</div>
							<div id="lowerSoDetail" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="row">
										@foreach($lowSo as $low)
											<div class="subject col-xs-12">
												<h5><a href="{{ url('/numeral', $low->numeral->id) }}">{{ $low->numeral->article->name }} - {{ $low->numeral->name }}</a></h5>
												{{ $low->numeral->script }}
												<div class="progress-border">
													<div @if($low->links <> '' ) data-toggle="tooltip" title="Informaci&oacute;n encontrada en {{ $low->links }}" @endif class="progress-red progress-bar" style="height:24px;width:{{ $low->score }}%">{{ $low->score }}%</div>
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
		</div>
@endsection


