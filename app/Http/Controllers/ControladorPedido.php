<?php

namespace App\Http\Controllers;

Class ControladorPedido extends Controller
{
      public function nuevo(){

            $titulo= "Nuevo Pedido";
            return view("sistema.pedido-nuevo", compact("titulo")); //le digo que vaya a buscar el html blade

      }

}

?>