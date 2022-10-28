@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($postulacion->idpostulacion) && $postulacion->idpostulacion > 0 ? $postulacion->idpostulacion : 0; ?>';
    <?php $globalId = isset($postulacion->idpostulacion) ? $postulacion->idpostulacion : "0"; ?>
</script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/sistema/postulacion">postulacion</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/sistema/postulacion/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-floppy-o" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-trash-o" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-o-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
<script>
    function fsalir() {
        location.href = "/admin/sistema/postulacion";
    }
</script>
@endsection

@section("contenido")
<div class="panel-body">
    <div id="msg"></div>
    <?php
    if (isset($msg)) {
        echo '<script>msgShow("' . $msg["MSG"] . '", "' . $msg["ESTADO"] . '")</script>';
    }
    ?>
    <form id="form1" method="POST">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
            <div class="form-group col-lg-6">
                <label>Nombre: *</label>
                <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Apellido: *</label>
                <input type="text" id="txtApellido" name="txtApellido" class="form-control" value="" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label>Celular: *</label>
                <input type="text" id="txtDni" name="txtDni" class="form-control" value="" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Correo: *</label>
                <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" value="" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label>CV: *</label>
                <input type="text" id="txtCv" name="txtCv" class="form-control" value="" required>
            </div>
        </div>
    </form>
</div>

@endsection