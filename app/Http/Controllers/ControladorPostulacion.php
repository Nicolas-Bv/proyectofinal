<?php

namespace App\Http\Controllers;

Class ControladorPostulacion extends Controller
{
      public function nuevo(){

            $titulo= "Nueva Postulación";
            return view("sistema.postulacion-nuevo", compact("titulo")); //le digo que vaya a buscar el html blade

      }

}

?>