@extends('Excel')

@section('contenido')
    <div class="card">
        <div class="card-header d-flex justify-content-center">
            <h2>Subir archivo excel.</h2>
        </div>
    </div>
    <div class="card-body d-flex justify-content-center">
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
            <div class="form-group">
                <label for="">Archivo: </label>
                <input type="file" name="archivo">
            </div>
                <button type="submit" class="btn btn-outline-primary btn-lg btn-block">Subir excel</button>
        </form>
    </div>
@endsection