@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($producto->idproducto) && $producto->idproducto > 0 ? $producto->idproducto : 0; ?>';
    <?php $globalId = isset($producto->idproducto) ? $producto->idproducto : "0"; ?>
</script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/sistema/producto">Producto</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/sistema/producto/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-floppy-o" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-trash-o" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-o-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
<script>
    function fsalir() {
        location.href = "/admin/sistema/producto";
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
    <form id="form1" method="POST" enctype="multipart/form-data">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
            <div class="form-group col-lg-6">
                <label>Nombre: *</label>
                <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Categoría: *</label>
                <select name="lstCategoría" id="lstCategoría" class="form-control">
                    <option value="Seleccionar" select>Seleccionar</option>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                </select>
         </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label>Cantidad: *</label>
                <input type="number" id="txtCantidad" name="txtCantidad" class="form-control" value="" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Precio: *</label>
                <input type="number" id="txtTotal" name="txtTotal" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label>Imagen: *</label><br>
                 <input type="file" name="image">
            </div>
        </div>
    </form>
</div>

@endsection