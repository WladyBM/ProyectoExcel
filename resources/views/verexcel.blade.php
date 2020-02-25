@extends('Excel')

@section('contenido')
<div class="card">
        <h3 class="card-header d-flex justify-content-center">Producción anual</h3>
        <div class="card-header d-flex justify-content-center">
            {{$fechas->links()}}
        </div>
    <div class="card-body-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm Produccion">
                <thead>
                    <tr class="bg-info">
                        <th scope="col" style="width: 30%"><strong>Pozos</strong></th>
                        @foreach ($fechas as $item)
                                <th scope="col">{{ date("d/m/y", strtotime($item->nombre)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pozos as $pozo)
                        <tr>
                            <td>{{ str_replace('ANA', 'AÑA', str_replace(['_','-'], ' ', $pozo->nombre)) }}</td>
                            @foreach ($fechas as $fecha)
                                @foreach ($fecha->producciones as $produccion)
                                    @if ($produccion->pozo_id == $pozo->id)
                                        <td>{{ $produccion->cantidad * 1000 }}</td>
                                        @break
                                    @endif
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{$fechas->links()}}
    </div>
</div>
@endsection