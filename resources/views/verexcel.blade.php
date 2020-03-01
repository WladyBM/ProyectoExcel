@extends('Excel')

@section('head')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
@endsection

@section('title', 'ENAP - Produccion')

@section('body')
<div class="card">
        <h3 class="card-header d-flex justify-content-center">Producción anual</h3>
        <div class=" d-flex justify-content-center">
            {{ $fechas->links() }}
        </div>
    <div class="card-body">
        <div class="table-responsive">
            @if (session('mensaje'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('mensaje') }}<strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <table class="table table-striped table-bordered table-sm Produccion" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"><strong>Pozos</strong></th>
                        @foreach ($fechas as $item)
                                <th>{{ date("d/m/y", strtotime($item->nombre)) }}</th>
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
                <tfoot>
                    <tr>
                        <th scope="col"> </th>
                        @foreach ($fechas as $item)
                            <th>
                                <form action="{{ route('eliminar.fecha', $item) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro que desea eliminar esta fecha? Fecha: {{ date("d/m/y", strtotime($item->nombre)) }} ')"><i class="fas fa-eraser"></i> Borrar</button>
                                </form> 
                            </th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{$fechas->links()}}
    </div>
</div>
@endsection