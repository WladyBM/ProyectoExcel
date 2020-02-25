@extends('Excel')

@section('head')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
@endsection

@section('body')
<div class="card">
        <h3 class="card-header d-flex justify-content-center">Producción anual</h3>
        <div class="justify-content-center">
            @if (session('mensaje'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('mensaje') }}<strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{ $fechas->links() }}
        </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm Produccion">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 25%"><strong>Pozos</strong></th>
                        @foreach ($fechas as $item)
                                <th scope="col">{{ date("d/m/y", strtotime($item->nombre)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tfoot class="justify-content-center">
                    <tr>
                        <th scope="col"> </th>
                        @foreach ($fechas as $item)
                            <th scope="col">
                                <form action="{{ route('eliminar.fecha', $item) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro que desea eliminar esta fecha? Fecha: {{ date("d/m/y", strtotime($item->nombre)) }} ')"><i class="fas fa-eraser"></i> Borrar</button>
                                </form> 
                            </th>
                        @endforeach
                    </tr>
                </tfoot>
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