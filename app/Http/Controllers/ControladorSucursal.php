<?php

namespace App\Http\Controllers;
use App\Entidades\Sucursal;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
use Illuminate\Http\Request;
require app_path() . '/start/constants.php';

Class ControladorSucursal extends Controller
{
      public function nuevo(){

            $titulo= "Nueva Sucursal";

            if (Usuario::autenticado() == true) {
                if (!Patente::autorizarOperacion("SUCURSALALTA")) {
                    $codigo = "SUCURSALALTA";
                    $mensaje = "No tiene permisos para la operación.";
                    return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                } else {
         
                    $sucursal= new Sucursal();
                    return view("sistema.sucursal-nuevo", compact("titulo", "sucursal")); //le digo que vaya a buscar el html blade
         }
            } else {
                return redirect('admin/login');
            }

      }

      public function index(){
        $titulo="Listado de sucursales";

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("SUCURSALCONSULTA")) {
                $codigo = "SUCURSALCONSULTA";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                $sucursal = new Sucursal();
                return view("sistema.sucursal-listar", compact("titulo")); }
        } else {
            return redirect('admin/login');
        }

      }

      public function guardar(request $request) {

      try{ 
            //define la entidad del servicio

            $titulo="Modificar sucursal";
            $entidad= new Sucursal();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if( $entidad->nombre == "" || $entidad->telefono == "" || $entidad->direccion == "" || $entidad->linkmapa == "" || $entidad->horario == "" ){   
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
            $_POST["id"] = $entidad->idsucursal;
            return view('sistema.sucursal-listar', compact('titulo', 'msg'));

           } 
      } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $id = $entidad->idsucursal;
        $sucursal = new Sucursal();
        $sucursal->obtenerPorId($id);

        return view('sistema.sucursal-nuevo', compact('msg', 'sucursal', 'titulo')) . '?id=' . $sucursal->idsucursal;

      
}

public function cargarGrilla(Request $request)
    {
        $request = $_REQUEST;

        $entidad = new Sucursal();
        $aSucursales = $entidad->obtenerFiltrado();

        $data = array();
        $cont = 0;

        $inicio = $request['start'];
        $registros_por_pagina = $request['length'];


        for ($i = $inicio; $i < count($aSucursales) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = "<a href='/admin/sucursal/" .$aSucursales[$i]->idsucursal."'>" .$aSucursales[$i]->nombre. "</a>";
            $row[] = $aSucursales[$i]->telefono;
            $row[] = $aSucursales[$i]->direccion;
            $row[] = "<a href=''>  Ir al mapa </a>";
            $row[] = $aSucursales[$i]->horario;
            $cont++;
            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => count($aSucursales), //cantidad total de registros sin paginar
            "recordsFiltered" => count($aSucursales), //cantidad total de registros en la paginacion
            "data" => $data,
        );
        return json_encode($json_data);
    }

    public function editar($idsucursal){
        $titulo="Edición de sucursal";

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("SUCURSALEDITAR")) {
                $codigo = "SUCURSALEDITAR";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {

                $sucursal= new Sucursal();
                $sucursal->obtenerPorId($idsucursal);
                return view("sistema.sucursal-nuevo", compact("titulo","sucursal")); }
        } else {
            return redirect('admin/login');
        }

    }

    public function eliminar(Request $request){
       
        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("SUCURSALBAJA")) {
                $resultado["err"] = EXIT_FAILURE;
                $resultado["mensaje"] = "No tiene permisos para la operación";
            } else {

                $idSucursal = $request->input("id");
                $sucursal = new Sucursal;

                //si la Sucursal tiene un sucursal no elimina

                if ($sucursal->existePedidoSucursal($idSucursal)) {
                    $resultado["err"] = EXIT_FAILURE;
                    $resultado["mensaje"] = "Sucursal con pedidos asignados.";
                } else {

                    //sino si
                    $sucursal = new Sucursal;
                    $sucursal->idsucursal = $idSucursal;
                    $sucursal->eliminar();
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

?>
