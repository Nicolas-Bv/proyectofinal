<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    public $timestamps = false;

    protected $fillable = [
        'idsucursal','nombre','telefono', 'direccion','linkmapa','horario'
    ];
    
    
    protected $hidden = [

];


public function obtenerTodos()
{
    $sql = "SELECT
              idsucursal,
              nombre,
              telefono,
              direccion,
              linkmapa,
              horario
            FROM sucursales ORDER BY idsucursal ASC";
    $lstRetorno = DB::select($sql);
    return $lstRetorno;
}

public function obtenerPorId($idSucursal)
    {
        $sql = "SELECT
              nombre,
              telefono,
              direccion,
              linkmapa,
              horario,
              idsucursal
                FROM sucursales WHERE idsucursal = $idSucursal";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->nombre = $lstRetorno[0]->nombre;
            $this->telefono = $lstRetorno[0]->titulo;
            $this->direccion = $lstRetorno[0]->cantidad;
            $this->linkmapa = $lstRetorno[0]->precio;
            $this->horario = $lstRetorno[0]->descripcion;
            $this->idsucursal = $lstRetorno[0]->idsucursal;
            return $this;
        }
        return null;
    }

    public function guardar() {
        $sql = "UPDATE sucursales SET
            nombre='$this->nombre',
            telefono=$this->telefono,
            direccion=$this->direccion,
            linkmapa='$this->linkmapa',
            horario=$this->horario,
            WHERE idsucursal=?";
        $affected = DB::update($sql, [$this->idsucursal]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM sucurales WHERE
            idsucursal=?";
        $affected = DB::delete($sql, [$this->idsucursal]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO sucursales (
              nombre,
              telefono,
              direccion,
              linkmapa,
              horario
            ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->telefono,
            $this->direccion,
            $this->linkmapa,
            $this->horario,
        ]);
        return $this->idsucursal = DB::getPdo()->lastInsertId();
    }



}

?>