<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    public $timestamps = false;

    protected $fillable = [
        'idsucursal','nombre','telefono', 'direccion','linkmapa','horario',
    ];
    
    
    protected $hidden = [

];


public function cargarDesdeRequest($request)
{
    $this->idsucursal = $request->input('id') != "0" ? $request->input('id') : $this->idsucursal;
    $this->nombre = $request->input('txtNombre');
    $this->direccion = $request->input('txtDireccion');
    $this->telefono = $request->input('txtTelefono');
    $this->linkmapa = $request->input('txtLinkmapa');
    $this->horario = $request->input('txtHorario');
}



public function obtenerTodos()
{
    $sql = "SELECT
              idsucursal,
              nombre,
              telefono,
              direccion,
              linkmapa,
              horario
            FROM sucursales ORDER BY nombre ASC";
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
            $this->telefono = $lstRetorno[0]->telefono;
            $this->direccion = $lstRetorno[0]->direccion;
            $this->linkmapa = $lstRetorno[0]->linkmapa;
            $this->horario = $lstRetorno[0]->horario;
            $this->idsucursal = $lstRetorno[0]->idsucursal;
            return $this;
        }
        return null;
    }

    public function guardar() {
        $sql = "UPDATE sucursales SET
            nombre='$this->nombre',
            telefono='$this->telefono',
            direccion='$this->direccion',
            linkmapa='$this->linkmapa',
            horario='$this->horario'
            WHERE idsucursal=?";
        $affected = DB::update($sql, [$this->idsucursal]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM sucursales WHERE
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
            $this->horario
        ]);
        return $this->idsucursal = DB::getPdo()->lastInsertId();
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'nombre',
            1 => 'telefono',
            2 => 'direccion',
            3 => 'linkmapa',
            4 => 'horario',
        );
        $sql = "SELECT DISTINCT
                idsucursal,
                nombre,
                telefono,
                direccion,
                linkmapa,
                horario
              FROM sucursales 
                WHERE 1=1
                ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR direccion LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR telefono LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR linkmapa LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR horario LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }

   
}

?>