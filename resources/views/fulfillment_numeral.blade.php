@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container">
        <h1>Nivel de Cumplimiento por numeral específico</h1>
        <h2>{{ $round->name }}</h2>
		@include('includes.roundselector')
		<p>Aquí encontrarás los artículos y sus numerales en el orden que aparecen en la ley.  Presiona el nombre del numeral para ver la información específica.</p>
        <div class="row">
			@foreach($articles as $article)
				<div class="panel-group" id="art{{ $article->id }}">
					<div class="panel">
						<div class="panel-heading">
							<h4><a data-toggle="collapse" data-parent="#art{{ $article->id }}" href="#art{{ $article->id }}Detail"><span class="glyphicon glyphicon-chevron-right"></span>{{ $article->name }}</a></h4>
							<div class="progress-border">
								<div class="{{ $artColor[$article->id] }} progress-bar" style="height:24px;width:{{ $artProm[$article->id] }}%">{{ number_format($artProm[$article->id],2) }}%</div>
							</div>
						</div>
						<div id="art{{ $article->id }}Detail" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="row">
									@foreach ($article->numerals as $numeral)
										<div class="subject col-xs-12">
											<h5><a href="{{ url('/numeral', $numeral->id) }}">{{ $numeral->name }}</a></h5>
												{{ $numeral->script }}
											<div class="progress-border">
												<div class="{{ $numColor[$numeral->id] }} progress-bar" style="height:24px;width:{{ $numScore[$numeral->id] }}%">{{ $numScore[$numeral->id] }}%</div>
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
