@extends('Excel')

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-center">
        <h2>Produccion mensual diciembre</h2>
    </div>
    <div class="card-body-sm">
        <table class="table table-sm">
            <thead>
                <tr class="table-danger">
                    <th scope="col"><strong>Pozos</strong></th>
                    @foreach ($fechas as $fecha)
                        <th scope="col">{{ $fecha->nombre }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($pozos as $pozo)
                <tr>
                    <td>{{ $pozo->nombre }}</td>
                    @foreach ($pozo->fechas as $fecha)
                        <td>{{ $pozo->produccion * 1000 }}</td>  
                    @endforeach
                </tr>
                @endforeach    
            </tbody>
        </table>
    </div>
</div>
@endsection
