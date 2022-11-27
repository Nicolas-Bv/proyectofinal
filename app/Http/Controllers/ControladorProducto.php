<?php

namespace App\Http\Controllers;

use App\Entidades\Producto;
use App\Entidades\Pedido;
use App\Entidades\Categoria;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
use Illuminate\Http\Request;

require app_path() . "/start/constants.php";

class ControladorProducto extends Controller
{
    public function nuevo()
    {

        $titulo = "Nuevo producto";

        
        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("PRODUCTOSALTA")) {
                $codigo = "PRODUCTOSALTA";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                $producto = new Producto();
                $producto->obtenerTodos();
        
                $categoria = new Categoria();
                $aCategorias = $categoria->obtenerTodos();
                return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias")); //le digo que vaya a buscar el html blade
         }
        } else {
            return redirect('admin/login');
        }
     
    }

    public function Index()
    {
        $titulo = "Listado de productos";

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("PRODUCTOCONSULTA")) {
                $codigo = "PRODUCTOCONSULTA";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                return view("sistema.producto-listar", compact("titulo"));
            }
        } else {
            return redirect('admin/login');
        }

    }

    public function guardar(request $request)
    {

        try {
            //define la entidad del servicio

            $titulo = "Modificar producto";
            $entidad = new Producto();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if ($entidad->titulo == "" || $entidad->cantidad == "" || $entidad->precio == "" || $entidad->fk_idcategoria == "") {
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
                $_POST["id"] = $entidad->idproducto;
                return view('sistema.producto-listar', compact('titulo', 'msg'));
            }
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $id = $entidad->idproducto;
        $producto = new Producto();
        $producto->obtenerPorId($id);


        $categoria = new Categoria();
        $aCategorias = $categoria->obtenerTodos();

        return view('sistema.producto-nuevo', compact('msg', 'producto', 'titulo', 'aCategorias')) . '?id=' . $producto->idproducto;
    }


    public function cargarGrilla(Request $request)
    {
        $request = $_REQUEST;

        $entidad = new Producto();
        $aProductos = $entidad->obtenerFiltrado();

        $data = array();
        $cont = 0;

        $inicio = $request['start'];
        $registros_por_pagina = $request['length'];


        for ($i = $inicio; $i < count($aProductos) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = "<a href='/admin/producto/" . $aProductos[$i]->idproducto . "'>" . $aProductos[$i]->titulo . "</a>";
            $row[] = $aProductos[$i]->cantidad;
            $row[] = $aProductos[$i]->precio;
            $cont++;
            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => count($aProductos), //cantidad total de registros sin paginar
            "recordsFiltered" => count($aProductos), //cantidad total de registros en la paginacion
            "data" => $data,
        );
        return json_encode($json_data);
    }

    public function editar($idproducto)
    {
        $titulo = "Edición de producto";

        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("PRODUCTOEDITAR")) {
                $codigo = "PRODUCTOEDITAR";
                $mensaje = "No tiene permisos para la operación.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                $producto = new producto();
                $producto->obtenerPorId($idproducto);
        
                $categoria = new Categoria();
                $aCategorias = $categoria->obtenerTodos();
        
                return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
            
            }
        } else {
            return redirect('admin/login');
        }
       }

    public function eliminar(Request $request)
    {
        if (Usuario::autenticado() == true) {
            if (!Patente::autorizarOperacion("PRODUCTOELIMINAR")) {
                $resultado["err"] = EXIT_FAILURE;
                $resultado["mensaje"] = "No tiene permisos para la operación";
            } else {

                $idProducto = $request->input("id");
                $pedido = new Pedido;

                //si el producto tiene un pedido no elimina

                if ($pedido->existePedidoPorProducto($idProducto)) {
                    $resultado["err"] = EXIT_FAILURE;
                    $resultado["mensaje"] = "Producto con pedido asignado.";
                } else {

                    //sino si
                    $producto = new Producto;
                    $producto->idproducto = $idProducto;
                    $producto->eliminar();
                    $resultado["err"] = EXIT_SUCCESS;
                    $resultado["mensaje"] = "Registro eliminado exitosamente.";
                }
            }
        }else {
                $resultado["err"] = EXIT_FAILURE;
                $resultado["mensaje"] = "No tiene permisos para la operación";
        }
                return json_encode($resultado);
            }
        }