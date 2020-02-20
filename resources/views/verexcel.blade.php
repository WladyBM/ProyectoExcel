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
                    @foreach ($fechas as $item)
                            <th scope="col">{{ $item->nombre }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($pozos as $pozo)
                    <tr>
                        <td>{{ $pozo->nombre }}</td>
                        @foreach ($pozo->producciones as $produccion)
                            @if (empty($produccion->cantidad))
                                <td>0</td>
                            @else
                                <td>{{ $produccion->cantidad }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach   
            </tbody>
        </table>
    </div>
</div>
@endsection
