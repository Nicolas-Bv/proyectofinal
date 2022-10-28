<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

      protected $table = 'clientes';
      public $timestamps = false;
  
      protected $fillable = [
          'idcliente','nombre','apellido', 'celular','correo','dni','clave'
      ];
      
      
      protected $hidden = [
  
  ];

  public function cargarDesdeRequest($request) {
    $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;
    $this->nombre = $request->input('txtNombre');
    $this->apellido = $request->input('txtApellido');
    $this->celular = $request->input('txtCelular');
    $this->correo = $request->input('txtCorreo');
    $this->dni = $request->input('txtDni');
    $this->clave = $request->input('txtClave');
}
  
  
  public function obtenerTodos()
  {
      $sql = "SELECT
                nombre,
                apellido,
                celular,
                correo,
                dni,
                clave,
                idcliente
              FROM clientes ORDER BY nombre ASC";
      $lstRetorno = DB::select($sql);
      return $lstRetorno;
  }
  
  public function obtenerPorId($idcliente)
      {
          $sql = "SELECT
                nombre,
                apellido,
                celular,
                correo,
                dni,
                clave,
                idcliente
                  FROM clientes WHERE idcliente = $idcliente";
          $lstRetorno = DB::select($sql);
  
          if (count($lstRetorno) > 0) {
              $this->nombre = $lstRetorno[0]->nombre;
              $this->apellido = $lstRetorno[0]->apellido;
              $this->celular = $lstRetorno[0]->celular;
              $this->correo = $lstRetorno[0]->correo;
              $this->dni = $lstRetorno[0]->dni;
              $this->clave = $lstRetorno[0]->clave;
              $this->idcliente = $lstRetorno[0]->idcliente;
              return $this;
          }
          return null;
      }
  
      public function guardar() {
          $sql = "UPDATE clientes SET
              nombre='$this->nombre',
              apellido='$this->apellido',
              celular='$this->celular',
              correo='$this->correo',
              dni='$this->dni',
              clave='$this->clave',
              WHERE idcliente=?";
          $affected = DB::update($sql, [$this->idcliente]);
      }
  
      public function eliminar()
      {
          $sql = "DELETE FROM clientes WHERE
              idcliente=?";
          $affected = DB::delete($sql, [$this->idcliente]);
      }
  
      public function insertar()
      {
          $sql = "INSERT INTO clientes (
                nombre,
                apellido,
                celular,
                correo,
                dni,
                clave
              ) VALUES (?, ?, ?, ?, ?, ?);";
          $result = DB::insert($sql, [
              $this->nombre,
              $this->apellido,
              $this->celular,
              $this->correo,
              $this->dni,
              $this->clave,
          ]);
          return $this->idcliente = DB::getPdo()->lastInsertId();
      }
  
  
  
  

}
