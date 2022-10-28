<?php

namespace App\Http\Controllers;

Class ControladorSucursal extends Controller
{
      public function nuevo(){

            $titulo= "Nueva Sucursal";
            return view("sistema.sucursal-nuevo", compact("titulo")); //le digo que vaya a buscar el html blade

      }

}

?>