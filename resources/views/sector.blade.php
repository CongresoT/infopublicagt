@extends('layouts.laip')

@section('content')
<div class="main-container">
    <div class="container">
        <h1>Cumplimiento general por sector</h1>
        <h2>{{ $round->name }}</h2>
		@include('includes.roundselector')
        <div class="row">
			@foreach ($sectorProm as $key => $sector)
				<div class="panel-group" id="sec{{ $sectorsIds[$key] }}">
					<div class="panel">
						<div class="panel-heading">
							<h4><a data-toggle="collapse" data-parent="#sec{{ $sectorsIds[$key] }}" href="#sec{{ $sectorsIds[$key] }}Detail"><span class="glyphicon glyphicon-plus"></span>{{ $key }}</a></h4>
							<div class="progress-border">
								<div class="{{ $sectorColor[$key] }} progress-bar" style="height:24px;width:{{ $sectorProm[$key] }}%">{{ number_format($sectorProm[$key],2) }}%</div>
							</div>
						</div>
						<div id="sec{{ $sectorsIds[$key] }}Detail" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="row">
									@if (sizeof($sectors[$key]) == 0)
											<div class="subject col-xs-12">
												<h6>Ningun Sujeto Obligado de este sector forma parte del monitoreo.</h6>
											</div>
									@else
										@foreach ($sectors[$key] as $track)
											<div class="subject col-xs-12">
												<h5><a href="{{ url('/sujeto', $track->subject->id) }}">{{ $track->subject->name }}</a></h5>
												<div class="progress-border">
													<div class="{{ $subjectColor[$track->subject->id] }} progress-bar" style="height:24px;width:{{ $track->score }}%">{{ $track->score }}%</div>
												</div>
											</div>
										@endforeach
									@endif
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
