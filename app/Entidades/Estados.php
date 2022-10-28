<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{

      protected $table = 'Estados';
      public $timestamps = false;
  
      protected $fillable = [
          'idestado','nombre'
      ];
      
      
      protected $hidden = [
  
  ];
  
  
  public function obtenerTodos()
  {
      $sql = "SELECT
                idestado,
                nombre
              FROM estados ORDER BY nombre ASC";
      $lstRetorno = DB::select($sql);
      return $lstRetorno;
  }
  
  public function obtenerPorId($idEstados)
      {
          $sql = "SELECT
                idestado,
                nombre
                  FROM carritos WHERE idtipoproducto = $idEstados";
          $lstRetorno = DB::select($sql);
  
          if (count($lstRetorno) > 0) {
              $this->idestado = $lstRetorno[0]->idestado;
              $this->nombre = $lstRetorno[0]->nombre;
              return $this;
          }
          return null;
      }
  
      public function guardar() {
          $sql = "UPDATE tipo_productos SET
              nombre='$this->nombre'
              WHERE idestado=?";
          $affected = DB::update($sql, [$this->idestado]);
      }
  
      public function eliminar()
      {
          $sql = "DELETE FROM tipo_productos WHERE
              idestado=?";
          $affected = DB::delete($sql, [$this->idestado]);
      }
  
      public function insertar()
      {
          $sql = "INSERT INTO estados (
                nombre
              ) VALUES (?);";
          $result = DB::insert($sql, [
              $this->nombre,
          ]);
          return $this->idestado = DB::getPdo()->lastInsertId();
      }
  
  
  
  

}