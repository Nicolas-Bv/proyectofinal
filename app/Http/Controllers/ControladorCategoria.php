<?php

namespace App\Http\Controllers;

Class ControladorCategoria extends Controller
{
      public function nuevo(){

            $titulo= "Nueva Categoría";
            return view("sistema.categoria-nuevo", compact("titulo")); //le digo que vaya a buscar el html blade

      }

}

?>