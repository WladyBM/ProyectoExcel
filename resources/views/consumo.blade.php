@extends('Excel')

@section('head')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title', 'ENAP - Consumo')

@section('body')
<div class="card">
    <h3 class="card-header d-flex justify-content-center">Consumo anual</h3>
    <div class="mt-2 d-flex justify-content-center">
        <a class="btn btn-outline-dark" style="width:30%" href="{{route('ver.produccion', $paginate=50)}}">Ver producción</a>
        <button type="button" class="btn btn-outline-primary ml-2" style="width:30%" data-toggle="modal" data-target="#Añadir1">Añadir PADS</button>
    </div>
    <div class="mt-2 d-flex justify-content-center">
        <button type="button" class="btn btn-outline-primary" style="width:30%" data-toggle="modal" data-target="#Añadir2">Añadir equipos</button>
        <button type="button" class="btn btn-outline-primary ml-2" style="width:30%" data-toggle="modal" data-target="#Asociar">Asociar equipos</button>
    </div>
    <!-- Modal añadir PADS -->
    <div class="modal fade" id="Añadir1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AñadirLabel">Añade nuevo PAD</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Para añadir un nuevo PAD, debe copiar el nombre que se encuentra en la hoja "Detalle Pozo" dentro del informe consolidado.</h6>
                    <img src="{{ URL::asset('/PAD.png') }}" class="img-fluid" alt="Responsive image">
                    
                    <h6>Ejemplos:</h6>
                        <ul>
                            <li>MANANTIALES-17</li>
                            <li>PUNTA_PIEDRA_ZG-1</li>
                            <li>CHANARCILLO-36</li>
                        </ul>
                    <form class="form-inline d-flex justify-content-center" method="POST" action="{{ Route('añadir.pad') }}">
                        @csrf
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Error al añadir: 
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
                        <div class="form-group mx-sm-3">
                            <input type="text" class="form-control" name="pad" placeholder="Nombre PAD">
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar ventana</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin modal -->

    <!-- Modal añadir equipos -->
    <div class="modal fade" id="Añadir2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AñadirLabel">Añade nuevo equipo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Ingrese el nombre del equipo para ingresarlo al sistema.</h6>
                    <form class="form justify-content-center" method="POST" action="{{ Route('añadir.equipo') }}">
                        @csrf
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Error al añadir equipo: 
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
                        <div class="form-group">
                            <input type="text" class="form-control" name="equipo" value="{{ old('equipo') }}" placeholder="Nombre equipo">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="consumo" value="{{ old('consumo') }}" placeholder="Consumo">
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin modal -->

    <!-- Asociar equipos a PAD modal -->
    <div class="modal fade" id="Asociar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AñadirLabel">Añadir equipos a PAD</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Escoja el PAD que quiera añadirle equipos.</h6>
                    <form class="form justify-content-center" method="POST" action="{{ Route('asociar.equipo') }}">
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
                        <select class="form-group custom-select" name="pad" required>
                            <option value="">Seleccione PAD</option>
                            @foreach ($pads as $pad)
                                <option value="{{ $pad->id }}">{{ $pad->nombre }}</option>
                            @endforeach
                        </select>
                        <h6>Seleccione equipo(s) que quiera asociar.</h6>
                        <select class="form-group custom-select" name="equipo[]" multiple required>
                            @foreach ($equipos as $equipo)
                                <option value="{{ $equipo->id }}">{{ $equipo->nombre }}: {{ $equipo->consumo }}</option>
                            @endforeach
                        </select>                       
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin modal -->

    <div class="mt-2 d-flex justify-content-center">
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
            <table class="table table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-right border-left border-secondary" scope="col"><strong>PADS</strong></th>
                        <th class="border-right border-left border-secondary"></th>
                        @foreach ($fechas as $item)
                                <th class="border-right border-left border-secondary">{{ date("d/m/y", strtotime($item->nombre)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="Tabla">
                    @foreach ($pads as $pad)
                        <tr>
                            <td class="border border-secondary" style="width:100px">{{ str_replace('ANA', 'AÑA', str_replace(['_','-'], ' ', $pad->nombre)) }}</td>
                            <td class="border border-secondary" style="width:100px">
                                <form action="{{ route('eliminar.pad', $pad) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro que desea eliminar {{ $pad->nombre }} ?')"><i class="fas fa-eraser"></i></button>
                                </form>
                            </td>
                            @foreach ($fechas as $fecha)
                                @foreach ($fecha->horas as $hora1)
                                    @foreach ($pad->horas as $hora2)
                                        @if ($hora1->id == $hora2->id)
                                            <td class="border border-secondary">{{ $hora1->hora }}</td>
                                            @break
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tr>
                        @foreach ($pad->equipos as $equipo)
                            <tr>
                                <td class="border-right border-left">{{ $equipo->nombre }}</td>
                                <td class="border-right border-left">
                                    <form action="{{ route('eliminar.equipo', ['nombre_equipo'=>$equipo->nombre, 'nombre_pad'=>$pad->nombre]) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Está seguro que desea borrar {{ $equipo->nombre }} de {{ $pad->nombre }} ?')"><i class="fas fa-eraser">Quitar</i></button>
                                    </form>
                                </td>
                                @foreach ($fechas as $fecha)
                                    @foreach ($fecha->horas as $hora1)
                                        @foreach ($pad->horas as $hora2)
                                            @if ($hora1->id == $hora2->id)
                                                <td class="border-right border-left">{{ round($equipo->consumo /24 * $hora1->hora) }}</td>
                                                @break
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col"> </th>
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