@extends('layouts.laip')

@section('otherIncludes')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.13/r-2.1.0/datatables.min.css"/> 
@endsection

@section('content')
<div class="main-container">
    <div class="container">
	<h1>Vista por Sujeto Obligado específico</h1>
        <div class="row">
            <div class="col-xs-12">
                <table id="sujetos" class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sector</th>
                            <th>Sujeto Obligado</th>
                            <th>Monitoreado</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sector</th>
                            <th>Sujeto Obligado</th>
                            <th>Monitoreado</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($sectors as $sector)
                            @foreach ($sector->subjects as $subject)
                                <tr>
                                    <td>{{ $sector->name }}</td>
                                    @if ($subject->enabled)
                                        <td><a href="{{ url('/sujeto',$subject->id) }}">{{ $subject->name }}</a></td>
                                        <td>Sí</td>
                                    @else
                                        <td>{{ $subject->name }}</td>
                                        <td>No</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.13/r-2.1.0/datatables.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery('#sujetos').DataTable({
                "order": [[ 0, "asc" ],[ 2, "desc" ],[ 1, "asc" ] ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json"
                }
            });
        });
    </script>

@endsection
