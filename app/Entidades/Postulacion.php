<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{

      protected $table = 'postulaciones';
      public $timestamps = false;
  
      protected $fillable = [
          'idsucursal','nombre','apellido', 'celular','correo','curriculum'
      ];
      
      
      protected $hidden = [
  
  ];
  
  
  public function obtenerTodos()
  {
      $sql = "SELECT
                nombre,
                apellido,
                celular,
                correo,
                curriculum,
                idpostulacion
              FROM postulaciones ORDER BY nombre ASC";
      $lstRetorno = DB::select($sql);
      return $lstRetorno;
  }
  
  public function obtenerPorId($idPostulacion)
      {
          $sql = "SELECT
                nombre,
                apellido,
                celular,
                correo,
                curriculum,
                idpostulacion
                  FROM postulaciones WHERE idpostulacion = $idPostulacion";
          $lstRetorno = DB::select($sql);
  
          if (count($lstRetorno) > 0) {
              $this->nombre = $lstRetorno[0]->nombre;
              $this->apellido = $lstRetorno[0]->apellido;
              $this->celular = $lstRetorno[0]->celular;
              $this->correo = $lstRetorno[0]->correo;
              $this->curriculum = $lstRetorno[0]->curriculum;
              $this->idpostulacion = $lstRetorno[0]->idpostulacion;
              return $this;
          }
          return null;
      }
  
      public function guardar() {
          $sql = "UPDATE postulaciones SET
              nombre='$this->nombre',
              apellido='$this->apellido',
              celular='$this->celular',
              correo='$this->correo',
              curriculum='$this->curriculum',
              WHERE idpostulacion=?";
          $affected = DB::update($sql, [$this->idpostulacion]);
      }
  
      public function eliminar()
      {
          $sql = "DELETE FROM postulaciones WHERE
              idpostulacion=?";
          $affected = DB::delete($sql, [$this->idpostulacion]);
      }
  
      public function insertar()
      {
          $sql = "INSERT INTO postulaciones (
                nombre,
                apellido,
                celular,
                correo,
                curriculum
              ) VALUES (?, ?, ?, ?, ?);";
          $result = DB::insert($sql, [
              $this->nombre,
              $this->apellido,
              $this->celular,
              $this->correo,
              $this->curriculum,
          ]);
          return $this->idpostulaciones = DB::getPdo()->lastInsertId();
      }
  
  
  
  

}
