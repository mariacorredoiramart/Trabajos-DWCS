<!-- Autor: María Corredoira Martínez -->
@extends('plantillas.plantilla1')

@section('titulo')
{{$titulo}}
@endsection

@section('encabezado')
{{$encabezado}}
@endsection


@section('contenido')
<div class="text-center">
    <a href="crearDatos.php" class="btn btn-success"><i class="fa-solid fa-database me-2"></i>Instalar datos de
        prueba</a>
</div>

@endsection