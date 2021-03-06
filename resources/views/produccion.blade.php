@extends('Excel')

@section('head')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
@endsection

@section('title', 'ENAP - Produccion')

@section('body')
<div class="card">
        <h3 class="card-header d-flex justify-content-center">Producción anual</h3>
        <div class="mt-2 mb-2 d-flex justify-content-center">
            <a class="btn btn-outline-dark" href="{{ route('ver.consumo') }}">Ver consumo</a>
            <a class="btn btn-outline-primary ml-2" data-toggle="modal" data-target="#Excel">Subir excel</a>
            <a class="btn btn-outline-primary ml-2" data-toggle="modal" data-target="#Exportar">Exportar</a>
        </div>
        <div class="mt-1 d-flex justify-content-center">
            {{$fechas->appends(['pozos' => $pozos->currentPage()])->links()}}
        </div>

        <!-- Modal subir excel -->

        <div class="modal fade" id="Excel" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AñadirLabel">Sube el archivo excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Busque el archivo excel que desea importar.</h6>
                        <form class="form-group" enctype="multipart/form-data" method="POST" action="{{Route('importar.excel')}}">
                            @csrf
                            @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Error al subir: 
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif            
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="archivo[]" lang="es" multiple>
                                    <label class="custom-file-label">Elija archivo(s)</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">Importar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal subir excel -->

        <!-- Modal exportar -->
        <div class="modal fade" id="Exportar" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AñadirLabel">Exporte producción, consumo o ambos.</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Seleccione una o varias opciones</h6>
                        <form class="form justify-content-center" method="POST" action="{{ Route('exportar.excel') }}">
                            @csrf
                            @if (count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Error al asociar: 
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif 
                            <select class="form-group custom-select" name="modulo[]" size="2" multiple required>
                                <option value="0">Producción</option>
                                <option value="1">Consumo</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Añadir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal exportar -->

    <div class="card-body">
        <div class="d-flex justify-content-around">
            <div class="dropdown mr-2">
                <button type="button" class="btn btn-outline-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Cantidad pozos
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{route('ver.produccion', $paginate=50)}}">50</a>
                  <a class="dropdown-item" href="{{route('ver.produccion', $paginate=100)}}">100</a>
                  <a class="dropdown-item" href="{{route('ver.produccion', $paginate=150)}}">150</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{route('ver.produccion', $paginate=$page)}}">Todos</a>
                </div>
            </div>
            <input class="form-control mb-2 mr-2" id="Buscador" type="text" placeholder="Buscar ...">
            {{$pozos->appends(['fechas' => $fechas->currentPage()])->links()}}
        </div>
        <div class="table-responsive">
            @if (session('mensaje'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('mensaje') }}<strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <table class="table table-striped table-bordered table-sm Produccion">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"><strong>Pozos</strong></th>
                        @foreach ($fechas as $item)
                                <th>{{ date("d/m/y", strtotime($item->nombre)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="Tabla">
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
        {{$fechas->appends(['pozos' => $pozos->currentPage()])->links()}}
    </div>
</div>
@endsection