<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{

      protected $table = 'carritos';
      public $timestamps = false;
  
      protected $fillable = [
          'idcarrito','fk_idcliente','fk_idproducto'
      ];
      
      
      protected $hidden = [
  
  ];
  
  public function cargarDesdeRequest($request)
    {
        $this->idcarrito = $request->input('id') != "0" ? $request->input('id') : $this->idcarrito;
        $this->fk_idcliente = $request->input('fk_idcliente');
        $this->fk_idproducto = $request->input('fk_idproducto');
    
    }
  
  public function obtenerTodos()
  {
      $sql = "SELECT
                idcarrito,
                fk_idcliente,
                fk_idproducto
              FROM carritos ORDER BY idcarrito DESC";
      $lstRetorno = DB::select($sql);
      return $lstRetorno;
  }
  
  public function obtenerPorId($idCarrito)
      {
          $sql = "SELECT
                idcarrito,
                fk_idcliente,
                fk_idproducto
                                 FROM carritos WHERE idcarrito = $idCarrito";
          $lstRetorno = DB::select($sql);
  
          if (count($lstRetorno) > 0) {
              $this->idcarrito = $lstRetorno[0]->idcarrito;
              $this->fk_idciente = $lstRetorno[0]->fk_idcliente;
              $this->fk_idproducto = $lstRetorno[0]->fk_idproducto;
              return $this;
          }
          return null;
      }
  
      public function guardar() {
          $sql = "UPDATE carritos SET
              idcliente=$this->idcliente,
              idproducto=$this->idproducto
              WHERE idcarrito=?";
          $affected = DB::update($sql, [$this->idcarrito]);
      }
  
      public function eliminar()
      {
          $sql = "DELETE FROM carritos WHERE
              idcarrito=?";
          $affected = DB::delete($sql, [$this->idcarrito]);
      }
  
      public function insertar()
      {
          $sql = "INSERT INTO postulaciones (
                fk_idcliente,
                fk_idproducto
              ) VALUES (?, ?);";
          $result = DB::insert($sql, [
              $this->fk_idcliente
          ]);
          return $this->idcarrito = DB::getPdo()->lastInsertId();
      }
  
  
  
  

}