<?php

namespace App\Http\Controllers;
use App\Entidades\Categoria;
use App\Entidades\Pedido;
use App\Entidades\Sistema\Patente;
use App\Entidades\Sistema\Usuario;
use Illuminate\Http\Request;
require app_path() . '/start/constants.php';

Class ControladorCategoria extends Controller
{
      public function nuevo(){

            $titulo= "Nueva categoria";

            
        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("CATEGORIAALTA")) {
                $codigo = "CATEGORIAALTA";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                $categoria= new Categoria();
                return view("sistema.categoria-nuevo", compact("titulo", "categoria")); //le digo que vaya a buscar el html blade
     }
        } else {
            return redirect('admin/login');
        }
        
      }

      public function index(){
        $titulo="Listado de categorias";
        return view("sistema.categoria-listar", compact("titulo"));
      }

      public function guardar(request $request) {

      try{ 
            //define la entidad del servicio

            $titulo="Modificar categoria";
            $entidad= new Categoria();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if($entidad->nombre == ""){   
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Complete todos los datos";
            } else {
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
            $_POST["id"] = $entidad->idcategoria;
            return view('sistema.categoria-listar', compact('titulo', 'msg'));

           } 
      } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $id = $entidad->idcategoria;
        $categoria = new Categoria();
        $categoria->obtenerPorId($id);

        return view('sistema.categoria-nuevo', compact('msg', 'categoria', 'titulo')) . '?id=' . $categoria->idcategoria;

      
}

public function cargarGrilla(Request $request)
    {
        $request = $_REQUEST;

        $entidad = new Categoria();
        $aCategorias = $entidad->obtenerFiltrado();

        $data = array();
        $cont = 0;

        $inicio = $request['start'];
        $registros_por_pagina = $request['length'];


        for ($i = $inicio; $i < count($aCategorias) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = "<a href='/admin/categoria/" .$aCategorias[$i]->idcategoria."'>" .$aCategorias[$i]->nombre. "</a>";
            $cont++;
            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => count($aCategorias), //cantidad total de registros sin paginar
            "recordsFiltered" => count($aCategorias), //cantidad total de registros en la paginacion
            "data" => $data,
        );
        return json_encode($json_data);
    }

    public function editar($idcategoria){
        $titulo="Edición de categoria";
        $categoria= new Categoria();
        $categoria->obtenerPorId($idcategoria);
        return view("sistema.categoria-nuevo", compact("titulo","categoria"));
    }

    public function eliminar(Request $request)
    {

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("CATEGORIAELIMINAR")) {
                $resultado["err"] = EXIT_FAILURE;
                $resultado["mensaje"] = "No tiene permisos para la operación";
            } else {

                $idCategoria = $request->input("id");
                $pedido = new Pedido;

                //si el Categoria tiene un pedido no elimina

                if ($pedido->existePedidoCategoria($idCategoria)) {
                    $resultado["err"] = EXIT_FAILURE;
                    $resultado["mensaje"] = "Categoria con pedidos asignados.";
                } else {

                    //sino si
                    $categoria = new Categoria();
                    $categoria->idcategoria = $idCategoria;
                    $categoria->eliminar();
                    $resultado["err"] = EXIT_SUCCESS;
                    $resultado["mensaje"] = "Registro eliminado exitosamente.";
                }
                return json_encode($resultado);
            }
        } else {
            $resultado["err"] = EXIT_FAILURE;
            $resultado["mensaje"] = "No tiene permisos para la operación";
        }
    }
}
