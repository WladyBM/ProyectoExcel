@extends('Excel')

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-center">
        <h2>Produccion mensual 2020.</h2>
    </div>
    <div class="card-body-sm">
        <table class="table table-sm">
            <thead>
                <tr class="table-danger">
                    <th scope="col"><strong>Pozos</strong></th>
                    <th scope="col">{{ $dato[1]->fecha }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dato as $pozo)
                <tr>
                    <td>{{ $pozo->nombre }}</td>
                    <td>{{ $pozo->produccion * 1000 }}</td>
                </tr>
                @endforeach
                <tr class="table-info">
                    <td><strong>Total de bloques: </strong></td>
                    <td><strong>Produccion total: </strong></td>
                </tr>    
            </tbody>
        </table>
    </div>
</div>
@endsection