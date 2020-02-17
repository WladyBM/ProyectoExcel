@extends('Excel')

@section('contenido')
<div class="card">
    <div class="card-header d-flex justify-content-center">
        <h2>Hoja de Excel.</h2>
    </div>
    <div class="card-body-sm">
        <table class="table table-sm">
            <thead>
                <tr class="table-danger">
                    <th scope="col">{{ $pozo[0] }}</th>
                    <th scope="col">{{ $hora[0] }}</th>
                    <th scope="col">{{ $gas[0] }}</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i < $totalpozos - 1; $i++)
                <tr>
                    <td>{{ $pozo[$i] }}</td>
                    @if (empty($hora[$i]))
                        <td>0</td> 
                    @else
                        <td>{{ $hora[$i] }}</td>
                    @endif
                    @if (empty($gas[$i]))
                        <td>0</td>
                    @else
                        <td>{{ $gas[$i] * 1000 }}</td>
                    @endif
                </tr>
                @endfor
                <tr class="table-info">
                    <td><strong>Total de bloques: {{ $totalpozos-1 }}</strong></td>
                    <td><strong>Produccion total: </strong></td>
                    <td>{{array_sum($gas) * 1000}}</td>
                </tr>    
            </tbody>
        </table>
    </div>
</div>
@endsection