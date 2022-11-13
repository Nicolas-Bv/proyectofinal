<?php

namespace App\Http\Controllers;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use App\Entidades\Estado;
use App\Entidades\Cliente;
use App\Entidades\Pedido_productos;
use Illuminate\Http\Request;
require app_path()."/start/constants.php";

Class Controladorpedido extends Controller
{
      public function nuevo(){

            $titulo= "Nuevo pedido";
            $pedido= new Pedido();
            $pedido->obtenerTodos();

            $sucursal= new Sucursal();
            $aSucursales= $sucursal->obtenerTodos();

            $cliente= new Cliente();
            $aClientes= $cliente->obtenerTodos();

            $pedidoProducto= new PedidoProducto();
            $aPedidoProductos= $pedidoProducto->obtenerTodos();

            $estado= new Estado();
            $aEstados= $estado->obtenerTodos();
            return view("sistema.pedido-nuevo", compact("titulo", "aSucursales", "aEstados", "aClientes", "aPedidoProductos")); //le digo que vaya a buscar el html blade

      }

    public function Index(){
        $titulo="Listado de pedidos";
        return view("sistema.pedido-listar", compact("titulo"));
}

      public function guardar(request $request) {

      try{ 
            //define la entidad del servicio

            $titulo="Modificar pedido";
            $entidad= new Pedido();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if($entidad->fecha == "" || $entidad->fk_idsucursal == "" || $entidad->fk_idestado == "" || $entidad->fk_idcliente == "" || $entidad->fk_idpedido_productos == ""|| $entidad->total ){   
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
            $_POST["id"] = $entidad->idpedido;
            return view('sistema.pedido-listar', compact('titulo', 'msg'));

           } 
      } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $id = $entidad->idpedido;
        $pedido = new Pedido();
        $pedido->obtenerPorId($id);

        return view('sistema.pedido-nuevo', compact('msg', 'pedido', 'titulo')) . '?id=' . $pedido->idpedido;

      
}


public function cargarGrilla(Request $request)
    {
        $request = $_REQUEST;

        $entidad = new Pedido();
        $aPedidos = $entidad->obtenerFiltrado();

        $data = array();
        $cont = 0;

        $inicio = $request['start'];
        $registros_por_pagina = $request['length'];


        for ($i = $inicio; $i < count($aPedidos) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = "<a href='/admin/pedido/" .$aPedidos[$i]->idpedido."'>" .$aPedidos[$i]->fecha. "</a>";
            $row[] = $aPedidos[$i]->fk_idcliente;
            $row[] = $aPedidos[$i]->fk_idestado;
            $row[] = $aPedidos[$i]->fk_idsucursal;
            $row[] = $aPedidos[$i]->total;
            $cont++;
            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => count($aPedidos), //cantidad total de registros sin paginar
            "recordsFiltered" => count($aPedidos), //cantidad total de registros en la paginacion
            "data" => $data,
        );
        return json_encode($json_data);
    }

    public function editar($idpedido){
        $titulo="Edición de pedido";
        $pedido= new Pedido();
        $pedido->obtenerPorId($idpedido);
        return view("sistema.pedido-nuevo", compact("titulo","pedido"));
    }
}
