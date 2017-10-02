@extends('layouts.laip')

@section('otherIncludes')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.13/r-2.1.0/datatables.min.css"/> 
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.13/r-2.1.0/datatables.min.js"></script>
@endsection

@section('content')
<div class="main-container">
    <div class="container">
        <h1>Vista por numeral específico</h1>
        <h2>{{ $round->name }}</h2>
		@include('includes.roundselector')
		<p>Aquí encontrarás los artículos y sus numerales ordenados de mayor a menor en cuanto a su nivel de cumplimiento.  Presiona el nombre del numeral para ver la información específica.</p>
        <div class="row">
			@foreach($art as $key=>$tNumerals)
				<div class="panel-group" id="art{{ str_replace(['.',' ','á','é','í','ó','ú'],[],$key) }}">
					<div class="panel">
						<div class="panel-heading">
							<h4><a data-toggle="collapse" data-parent="#art{{ str_replace(['.',' ','á','é','í','ó','ú'],[],$key) }}" href="#art{{ str_replace(['.',' ','á','é','í','ó','ú'],[],$key) }}Detail"><span class="glyphicon glyphicon-chevron-right"></span>{{ $key }}</a></h4>
							<div class="progress-border">
								<div class="{{ $artColor[$key] }} progress-bar" style="height:24px;width:{{ $artProm[$key] }}%">{{ number_format($artProm[$key],2) }}%</div>
							</div>
						</div>
						<div id="art{{ str_replace(['.',' ','á','é','í','ó','ú'],[],$key) }}Detail" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="row">
									@foreach ($tNumerals as $tNumeral)
										<div class="subject col-xs-12">
											<h5><a href="{{ url('/numeral', $tNumeral->numeral->id) }}">{{ $tNumeral->numeral->name }}</a></h5>
												{{ $tNumeral->numeral->script }}
											<div class="progress-border">
												<div class="{{ $numColor[$tNumeral->numeral->id] }} progress-bar" style="height:24px;width:{{ $tNumeral->score }}%">{{ number_format($tNumeral->score,2) }}%</div>
											</div>
									</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			@endforeach
        </div>
    </div>
</div>
@endsection
