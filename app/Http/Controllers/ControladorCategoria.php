<?php

namespace App\Http\Controllers;
use App\Entidades\Categoria;
use Illuminate\Http\Request;
require app_path() . '/start/constants.php';

Class ControladorCategoria extends Controller
{
      public function nuevo(){

            $titulo= "Nueva categoria";
            $categoria= new Categoria();
            return view("sistema.categoria-nuevo", compact("titulo", "categoria")); //le digo que vaya a buscar el html blade

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
}
