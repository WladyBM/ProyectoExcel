@extends('Excel')

@section('contenido')
<div class="card">
        <h3 class="card-header d-flex justify-content-center">Produccion anual</h3>
        <div class="card-header d-flex justify-content-center">
            {{$pozos->links()}}
        </div>
    <div class="card-body-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
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
                            @foreach ($pozo->producciones as $produccion)
                                    <td>{{ $produccion->cantidad *1000 }}</td>
                            @endforeach
                        </tr>
                    @endforeach   
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{$pozos->links()}}
    </div>
</div>
@endsection
