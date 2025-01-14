<!-- Autor: María Corredoira Martínez -->
@extends('plantillas.plantilla1')

@section('titulo')
{{$titulo}}
@endsection

@section('encabezado')
{{$encabezado}}
@endsection

@section('contenido')

@if(isset($mensajeCrearDatos))
    <div class="alert alert-success mt-3">
        {{$mensajeCrearDatos}}
    </div>
@endif

<div class="mb-4">
    <a href="fcrear.php" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Nuevo jugador
    </a>
</div>
<div>
    <table class="table table-striped">
        <thead>
            <tr class="text-center">
                <th scope="col">Nombre Completo</th>
                <th scope="col">Posición</th>
                <th scope="col">Dorsal</th>
                <th scope="col">Código de Barras</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jugadores as $jugador)
                        <tr class="text-center">
                            <td>{{$jugador->getApellidos() . ", " . $jugador->getNombre()}}</td>
                            @if($jugador->getPosicion() != null)
                                <td>{{$jugador->getPosicion()}}</td>
                            @else
                                <td>Posición sin asignar</td>
                            @endif
                            @if($jugador->getDorsal() != null)
                                <td>{{$jugador->getDorsal()}}</td>
                            @else
                                <td>Dorsal sin asignar</td>
                            @endif
                            <td class="text-center justify-content-center d-flex">
                                @php
                                    echo $dns1d->getBarcodeHTML($jugador->getBarcode(), 'EAN13', 2, 33, 'black');
                                @endphp
                            </td>
                        </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection