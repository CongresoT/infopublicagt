@extends('layouts.laip')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('css/sequences.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.9.1/d3.min.js"></script>
<script type="text/javascript">
json = {
            name:"root",
            children: [
                {
                    name:"cumplimiento de 85% en adelante",
                    color:"#32CD32",
                    score:"{{ sizeof($highTr) }}",
                    children: [
                        @foreach ($highTr as $h)
                            {
                                name:"{{ $h->subject->name }}",
                                id:"{{ $h->subject->id }}",
                                size:10, 
                                score: {{ number_format($h->score,2) }},
                            },
                        @endforeach
                    ]
                },
                {
                    name:"cumplimiento entre 60% y 85%",
                    color:"#FF8C00",
                    score:"{{ sizeof($mediumTr) }}",
                    children: [
                        @foreach ($mediumTr as $m)
                            {
                                name:"{{ $m->subject->name }}",
                                id:"{{ $m->subject->id }}",
                                size:10, 
                                score: {{ number_format($m->score,2) }},
                            },
                        @endforeach
                    ]
                },
                {
                    name:"cumplimiento menor a 60%",
                    color:"#B22222",
                    score:"{{ sizeof($lowTr) }}",
                    children: [
                        @foreach ($lowTr as $l)
                            {
                                name:"{{ $l->subject->name }}",
                                id:"{{ $l->subject->id }}",
                                size:10, 
                                score: {{ number_format($l->score,2) }},
                            },
                        @endforeach
                    ]
                },
            ]
}
</script>
<div class="main-container">
    <div class="container">
        <h1>Nivel de Cumplimiento General de los Sujetos Obligados</h1>
        <h2>{{ $round->name }}</h2>
		@include('includes.roundselector')
        <div class="row">   
            <br/><p>Pasa sobre la gráfica para conocer el cumplimiento de los sujetos obligados monitoreados</p><br/>
          <div id="chart">
            <div id="explanation" style="visibility: hidden;">
              <span id="percentage"></span><br/>
              <span id="sname"></span>
            </div>
          </div>
        </div>
    </div>
    @include('includes.sunburst')
</div>@endsection
