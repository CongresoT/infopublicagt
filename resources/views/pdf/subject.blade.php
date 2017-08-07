<!doctype html>
<html prefix="og: http://ogp.me/ns#">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="{{ URL::asset('css/pdf.css') }}">
		<style>
			@font-face {
				font-family: "Montserrat";
				src: url({{ URL::asset('fonts/Montserrat-Regular.ttf') }}) format("truetype");
			}
			@font-face {
				font-family: "Lato";
				src: url({{ URL::asset('fonts/Lato-Regular.ttf') }}) format("truetype");
			}
		</style>
	</head>
    <body id="page-top" class="index">
		<div style="background:#2C3E50; color:#fff;  font-size:2em; padding:10px; font-family:Montserrat; font-weight:700; text-transform: uppercase;">
			InfoPublicaGT
		</div>
		<div class="main-container">
			<div class="container">
				<h1>{{ $subject->name }}</h1>
				<div class="compInfo"><a href="{{ $subject->url }}" target="_blank">{{ $subject->url }}</a></div>
				<div class="compInfo">Unidad de acceso: {{ $subject->uaip_person }}</div>
				<div class="compInfo">Telefono: {{ $subject->phone }}</div>
				<div class="compInfo">Correo electrónico: {{ $subject->email }}</div>
				<div class="row">
					<div id="generalScore" @if($score >= $higher) class="progress-qty-green" @elseif($score >= $medium) class="progress-qty-yellow" @else class="progress-qty-red" @endif style="" >Cumplimiento General {{ $score }}%</div>
				</div>
				<div class="row">
					<div class="panel-group" id="topSo">
						<div class="panel">
							<div id="topSoDetail">
								<div class="panel-body">
									<div class="row">
										@foreach($track as $tr)
											{{-- exclude "buenas practicas" --}}
											@if ($tr->numeral->id != 56)
												<div class="subject col-xs-12">
													<table class="numeralTable">
														<tr @if($tr->score >= $higher) class="progress-qty-green" @elseif($tr->score >= $medium) class="progress-qty-yellow" @else class="progress-qty-red" @endif>
															<td style="width: 90%;" >
																{{ $tr->numeral->article->name }} - {{ $tr->numeral->name }}
															</td>
															<td style="width: 10%;">
																{{ $tr->score }}%
															</td>
														</tr>
													</table>
													{{ $tr->numeral->script }}
													<br/><br/>
													<div>
														<table class="detailsTable">
															<thead>
																<tr>
																	<td style="width:47%;">Pregunta</td>
																	<td style="width:47%;">Comentario</td>
																	<td style="width:6%;">Respuesta</td>
																</tr>
															</thead>
															<tbody>
																@foreach ($tr->numeral->indicators as $ind)
																	@foreach ($ind->questions as $quest)
																		@if ($quest->track_id == $tr->track_id)
																			@if ($quest->answer != 'NA')
																				<tr class="aRow">
																					<td @if ($quest->q_type == 'c') class="qChildText" @endif>
																						{{ $ind->question }}
																					</td>			
																					<td>
																						@if ($quest->q_type == 'p')
																							Esta pregunta no se toma en cuenta para calcular el nivel de cumplimiento.
																						@else
																							{{ $quest->specialist_comments }}
																						@endif
																					</td>
																					@if ($quest->answer == 'Y')
																						<td @if(($quest->q_type == 'c')&&($quest->answer == 'Y')) class="qTrue" @elseif(($quest->q_type == 'c')&&($quest->answer == 'N')) class="qFalse" @else class="qParent" @endif> Sí</td>
																					@else
																						<td @if(($quest->q_type == 'c')&&($quest->answer == 'Y')) class="qTrue" @elseif(($quest->q_type == 'c')&&($quest->answer == 'N')) class="qFalse" @else class="qParent" @endif> No</td>
																					@endif
																				</tr>
																			@endif
																		@endif
																	@endforeach
																@endforeach
															</tbody>
														<table>
													</div>
												</div>
											@endif
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	
    </body>
</html>
