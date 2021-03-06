﻿@extends('layouts.laip')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.9.1/d3.min.js"></script>
<script>
	var data = [
		@foreach($tracksNumeral as $tn)
			{date:'{{ date("m-Y", strtotime($tn->round->year_num.str_pad($tn->round->month_num,2,"0",STR_PAD_LEFT)."01")) }}', close: {{ number_format($tn->score,2) }}}, 
		@endforeach
    ];

	//localization
	var localeDefinition = d3.timeFormatDefaultLocale({
	  "decimal": ".",
	  "thousands": ",",
	  "grouping": [3],
	  "currency": ["Q", ""],
	  "dateTime": "%a %b %e %X %Y",
	  "date": "%m/%d/%Y",
	  "time": "%H:%M:%S",
	  "periods": ["AM", "PM"],
	  "days": ["Domingo", "Lunes", "Marates", "Miercoles", "Jueves", "Viernes", "Sabado"],
	  "shortDays": ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
	  "months": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
	  "shortMonths": ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
	});	
	d3.formatDefaultLocale(localeDefinition);

    // Set the dimensions of the canvas / graph
    var margin = {top: 30, right: 20, bottom: 30, left: 50},
        width = 600 - margin.left - margin.right,
        height = 270 - margin.top - margin.bottom;

    // Set the ranges
    var x = d3.scaleTime().range([0, width]);
    var y = d3.scaleLinear().range([height, 0]);

    // Define the axes
    var xAxis = d3.axisBottom(x).ticks({{ sizeof($tracksNumeral) }});

    var yAxis = d3.axisLeft(y).ticks(10);
        
    // Define the line
    var valueline = d3.line()
        .x(function(d) { return x(d.date); })
        .y(function(d) { return y(d.close); });
    
    var c;
    
	// Draw the data
	function draw(data){
		//only display scatterplot viz if there is more than one round to show
		@if (sizeof($tracksNumeral)>1)
			// Adds the svg canvas
			var svg = d3.select("#scatterplot")
				.append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
				.append("g")
					.attr("transform", 
						  "translate(" + margin.left + "," + margin.top + ")");

			// parse the date / time
			var parseTime = d3.timeParse("%m-%Y");
			
			data.forEach(function(d) {
				d.date = parseTime(d.date);
				d.close = +d.close;
			});

			// Scale the range of the data
			x.domain(d3.extent(data, function(d) { return d.date; }));
			y.domain([0, 100]);

			// Add the valueline path.
			svg.append("path")
				.attr("class", "line")
				.attr("d", valueline(data));

			// Add the tooltip div
			var tt = d3.select("body").append("div")	
				.attr("class", "tooltipd3")				
				.style("opacity", 0);
				
			// Add the scatterplot
			svg.selectAll("dot")
				.data(data)
			  .enter().append("circle")
				.attr("r", 3.5)
				.attr("cx", function(d) { return x(d.date); })
				.attr("cy", function(d) { return y(d.close); })
				.on('mouseover', function(d,i){
					tt.transition()
						.duration(200)
						.style('opacity',0.9);
					tt .html(d.close+"%")
						.style("left",(d3.event.pageX+10)+"px")
						.style("top",(d3.event.pageY-10)+"px");
				})
				.on('mouseout', function(d,i){
					tt.transition()
						.duration(500)
						.style('opacity',0);
				});
				
			// Add the X Axis
			svg.append("g")
				.attr("class", "x axis")
				.attr("transform", "translate(0," + height + ")")
				.call(xAxis);

			// Add the Y Axis
			svg.append("g")
				.attr("class", "y axis")
				.call(yAxis);
		@endif
		
        //piechart
        chartDiv = d3.select("#piechart");
                
        arc = d3.arc()
            .outerRadius(45)
            .innerRadius(35);

        pie = d3.pie()
            .sort(null)
            .value(function(info) {
                return info;
            });

        pieSvg = chartDiv.append("svg")
            .attr("width", 100)
            .attr("height", 100);

        g = pieSvg.selectAll(".pie")
            .data(pie([{{ $score }},{{ 100 - $score }}]))
            .enter().append("g");

        c = -1;
        g.append("path")
            .attr("d",arc)
            .attr("transform", "translate(50, 50)")
            .style("fill", function() {
                    c++;
                    if (c == 0) {
						if ({{ $score }} >= {{ $higher }})
							return "#32CD32";
						else if({{ $score }} >= {{ $medium }})
							return "#FF8C00";
						else
							return "#B22222";
					}
                    else {
                        return "#F1F1F1";
					}
                });

        g.append("text")
            .attr("class", "overall")
            .attr("text-anchor", "middle")
            .attr("transform", "translate(50, 30)")
            .attr('font-size', '1.25em')
            .attr('y', 25)
            .text("{{ number_format($score,2) }}%");
    }
	
    jQuery(document).ready(function() {
        draw(data);
        jQuery('[data-toggle="tooltip"]').tooltip({html:true, delay: {show: 100, hide: 1500}, trigger:'hover focus click'});   
    });
</script>

<div class="main-container">
    <div class="container">
        <h1>{{ $numeral->article->name }} - {{ $numeral->name }}</h1>
        <div class="compInfo">{{ $numeral->script }}</div>
		<h2>{{ $rounds[0]->name }}</h2>
		@include('includes.roundselector')
        <div class="row">
            <div class="col-xs-4">
                <div id="certificate" class="center-block">
                    <span class="certificate center-block">{{ str_pad($ranking,2,'0',STR_PAD_LEFT) }}</span>
                    <img style="vertical-align:middle" src="{{ url('img/medalla.png') }}">
                </div>
            </div>
            <div class="col-xs-4">
                <div id="piechart" class="center-block"></div>
				<h6 class="center-block">Cumplimiento</h6>
            </div>
            <div class="col-xs-4">
                <div id="advancement" class="center-block">
                    <span @if($advancement > 0) class="arrow-up" @elseif($advancement < 0) class="arrow-down" @else class="center-block" @endif></span>
                    <div @if($advancement > 0) class="progress-qty-green" @elseif($advancement < 0) class="progress-qty-red" @else  class="progress-qty-yellow" @endif>@if(isset($advancement)){{ number_format(abs($advancement),1) }}@else = %@endif</div>
                </div>
				<h6 class="center-block">Respecto al monitoreo anterior</h6>
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
                        <h4><a data-toggle="collapse" data-parent="#topSo" href="#topSoDetail"><span class="glyphicon glyphicon-chevron-right"></span>Sujetos Obligados con cumplimiento de 85% en adelante ({{ sizeof($topSo) }})</a></h4>
                        <div class="progress-border">
                            <div class="progress-green progress-bar" style="height:24px;width:{{ $promTop }}%">{{ number_format($promTop,2) }}%</div>
                        </div>
                    </div>
                    <div id="topSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach($topSo as $top)
									<div class="subject col-xs-12">
										<h5><a href="{{ url('/sujeto', $top->track->subject->id) }}">{{ $top->track->subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-green progress-bar" style="height:24px;width:{{ $top->score }}%">{{ number_format($top->score,2) }}%</div>
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
                        <h4><a data-toggle="collapse" data-parent="#middleSo" href="#middleSoDetail"><span class="glyphicon glyphicon-chevron-right"></span>Sujetos Obligados con cumplimiento entre 60% y 84.9% ({{ sizeof($midSo) }})</a></h4>
                        <div class="progress-border">
                            <div  class="progress-yellow progress-bar" style="height:24px;width:{{ $promMid }}%">{{ number_format($promMid,2) }}%</div>
                        </div>
                    </div>
                    <div id="middleSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach($midSo as $mid)
									<div class="subject col-xs-12">
										<h5><a href="{{ url('/sujeto', $mid->track->subject->id) }}">{{ $mid->track->subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-yellow progress-bar" style="height:24px;width:{{ $mid->score }}%">{{ number_format($mid->score,2) }}%</div>
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
                        <h4><a data-toggle="collapse" data-parent="#lowerSo" href="#lowerSoDetail"><span class="glyphicon glyphicon-chevron-right"></span>Sujetos Obligados con cumplimiento menor a 60% ({{ sizeof($lowSo) }})</a></h4>
                        <div class="progress-border">
                            <div class="progress-red progress-bar" style="height:24px;width:{{ $promLow }}%">{{ number_format($promLow,2) }}%</div>
                        </div>
                    </div>
                    <div id="lowerSoDetail" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
								@foreach($lowSo as $low)
									<div class="subject col-xs-12">
										<h5><a href="{{ url('/sujeto', $low->track->subject->id) }}">{{ $low->track->subject->name }}</a></h5>
										<div class="progress-border">
											<div class="progress-red progress-bar" style="height:24px;width:{{ $low->score }}%">{{ number_format($low->score,2) }}%</div>
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
