<?php

namespace App\Http\Controllers;
use App\Entidades\Producto;
use Illuminate\Http\Request;
require app_path()."/start/constants.php";

Class ControladorProducto extends Controller
{
      public function nuevo(){

            $titulo= "Nuevo producto";
            return view("sistema.producto-nuevo", compact("titulo")); //le digo que vaya a buscar el html blade

      }

      public function guardar(request $request) {

      try{ 
            //define la entidad del servicio

            $titulo="Modificar producto";
            $entidad= new Producto();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if($entidad->nombre == "" || $entidad->categoria == "" || $entidad->cantidad == "" || $entidad->precio == "" || $entidad->imagen == "")
            {   
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Complete todos los datos";
            }
           else 
           {
            if ($_POST["id"] > 0) {
                //Es actualizacion
                $entidad->guardar();

                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;
            } else {
                //Es nuevo
                $entidad->insertar();

                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;
            }
            $_POST["id"] = $entidad->idproducto;
            return view('sistema.producto-listar', compact('titulo', 'msg'));

           } 
      } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $id = $entidad->idproducto;
        $producto = new producto();
        $producto->obtenerPorId($id);

        return view('sistema.producto-nuevo', compact('msg', 'producto', 'titulo')) . '?id=' . $producto->idproducto;

      
}
}
