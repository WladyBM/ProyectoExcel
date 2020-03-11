@extends('Excel')

@section('title', 'ENAP - Subir excel')

@section('body')
    <div class="card">
        <h2 class="card-header d-flex justify-content-center">Subir archivo excel.</h2>
        <div class="card-body">
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
                        <input type="file" class="custom-file-input" name="archivo[]" multiple>
                        <label class="custom-file-label">Elija archivo(s)</label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">Subir excel</button>
                    </div>
                </div>
            </form>
        </div>
        @if (session('aviso'))
            <h5 class="d-flex justify-content-center">{{ session('aviso') }}</h5>
            <div class="mt-2 d-flex justify-content-center">
                <a class="btn btn-outline-dark mb-2" href="{{ route('ver.produccion') }}">Ver produccion</a>
            </div>
        @endif
    </div>
@endsection