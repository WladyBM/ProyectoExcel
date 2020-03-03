@extends('Excel')

@section('head')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
@endsection

@section('title', 'ENAP - Consumo')

@section('body')
<div class="card">
    <h3 class="card-header d-flex justify-content-center">Consumo anual</h3>
    <div class="mt-2 d-flex justify-content-center">
        <a class="btn btn-outline-dark" href="{{ route('ver.excel') }}">Ver produccion</a>
        <button type="button" class="btn btn-outline-primary ml-2" data-toggle="modal" data-target="#Añadir">Añadir PAD</button>
    </div>
    <!-- Integrar modal -->
    <div class="modal fade" id="Añadir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <img src="{{URL::asset('/PAD.png')}}" class="img-fluid" alt="Responsive image">
                    
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
                            <input type="text" class="form-control" name="pad" placeholder="Nombre del PAD">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar ventana</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fin modal -->

    <div class="mt-1 d-flex justify-content-center">    
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
            
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        
    </div>
</div>
@endsection