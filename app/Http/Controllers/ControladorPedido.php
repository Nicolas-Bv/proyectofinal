<?php

namespace App\Http\Controllers;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use App\Entidades\Estado;
use App\Entidades\Cliente;
use App\Entidades\Producto;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
use Illuminate\Http\Request;
require app_path()."/start/constants.php";

Class Controladorpedido extends Controller
{
      public function nuevo(){

            $titulo= "Nuevo pedido";

            if (Usuario::autenticado() == true) {
                if (!Patente::autorizarOperacion("PEDIDOALTA")) {
                    $codigo = "PEDIDOALTA";
                    $mensaje = "No tiene permisos para la operación.";
                    return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                } else {
                    $pedido= new Pedido();
                    $pedido->obtenerTodos();
        
                    $sucursal= new Sucursal();
                    $aSucursales= $sucursal->obtenerTodos();
        
                    $cliente= new Cliente();
                    $aClientes= $cliente->obtenerTodos();
        
                    $estado= new Estado();
                    $aEstados= $estado->obtenerTodos();
        
                    $producto= new Producto();
                    $aProductos=$producto->obtenerTodos();
                    return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aSucursales", "aEstados", "aClientes", "aProductos")); //le digo que vaya a buscar el html blade
            }
            } else {
                return redirect('admin/login');
            }
     
      }

    public function index(){
        $titulo="Listado de pedidos";

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("PEDIDOCONSULTA")) {
                $codigo = "PEDIDOCONSULTA";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                $pedido = new Pedido();
                return view("sistema.pedido-listar", compact("titulo"));//le digo que vaya a buscar el html blade
            }
        } else {
            return redirect('admin/login');
        }
      
}

      public function guardar(request $request) {

      try{ 
            //define la entidad del servicio

            $titulo="Modificar pedido";
            $entidad= new Pedido();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if($entidad->fecha == "" || $entidad->fk_idsucursal == "" || $entidad->fk_idestado == "" || $entidad->fk_idcliente == "" || $entidad->total == ""){   
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

        $sucursal= new Sucursal();
        $aSucursales= $sucursal->obtenerTodos();

        $cliente= new Cliente();
        $aClientes= $cliente->obtenerTodos();

        $estado= new Estado();
        $aEstados= $estado->obtenerTodos();

        $producto= new Producto();
        $aProductos=$producto->obtenerTodos();

        return view('sistema.pedido-nuevo', compact('msg', 'pedido', 'titulo', "aSucursales", "aClientes", "aEstados", "aProductos")) . '?id=' . $pedido->idpedido;

      
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
            $date=date_create($aPedidos[$i]->fecha);
            $row = array();
            $row[] = "<a href='/admin/pedidos/" .$aPedidos[$i]->idpedido."'>" .date_format($date, "d/m/Y"). "</a>";
            $row[] = number_format($aPedidos[$i]->total, 2, ".", ",");
            $row[] = $aPedidos[$i]->sucursal;
            $row[] = $aPedidos[$i]->cliente;
            $row[] = $aPedidos[$i]->estado;
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

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("PEDIDOEDITAR")) {
                $codigo = "PEDIDOEDITAR";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {

                $pedido= new Pedido();
                $pedido->obtenerPorId($idpedido);
        
                $sucursal= new Sucursal();
                $aSucursales= $sucursal->obtenerTodos();
        
                $cliente= new Cliente();
                $aClientes= $cliente->obtenerTodos();
        
                $estado= new Estado();
                $aEstados= $estado->obtenerTodos();
        
                $producto= new Producto();
                $aProductos=$producto->obtenerTodos();
        
                return view("sistema.pedido-nuevo", compact("titulo","pedido", "aSucursales", "aClientes", "aEstados", "aProductos"));
           }
        } else {
            return redirect('admin/login');
        }
    }

        public function eliminar(Request $request){
           
            if (Usuario::autenticado() == true) {
                if (!Patente::autorizarOperacion("PEDIDOBAJA")) {
                    $codigo = "PEDIDOBAJA";
                    $mensaje = "No tiene permisos para la operación.";
                    return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                } else {
    
                 
            $idPedido=$request->input("id");
    
            $pedido= new Pedido;
            $pedido->idpedido= $idPedido;
            $pedido->eliminar();
            $resultado["err"]= EXIT_SUCCESS;
            $resultado["mensaje"]="Registro eliminado exitosamente.";
            
            return json_encode($resultado);}
            } else {
                return redirect('admin/login');
            }
           
       
        }
        
  

  }

