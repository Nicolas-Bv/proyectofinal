<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'idcliente', 'nombre', 'direccion', 'celular', 'correo', 'dni', 'clave'
    ];


    protected $hidden = [];

    public function cargarDesdeRequest($request)
    {
        $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;
        $this->nombre = $request->input('txtNombre');
        $this->direccion = $request->input('txtDireccion');
        $this->celular = $request->input('txtCelular');
        $this->correo = $request->input('txtCorreo');
        $this->dni = $request->input('txtDni');
        $this->clave = $request->input('txtClave');
    }


    public function obtenerTodos()
    {
        $sql = "SELECT
                idcliente,
                nombre,
                direccion,
                celular,
                correo,
                dni,
                clave
              FROM clientes ORDER BY nombre ASC";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    

    public function obtenerPorId($idCliente)
    {
        $sql = "SELECT
                idcliente,
                nombre,
                direccion,
                celular,
                correo,
                dni,
                clave
                FROM clientes WHERE idcliente = $idCliente";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->nombre = $lstRetorno[0]->nombre;
            $this->direccion = $lstRetorno[0]->direccion;
            $this->celular = $lstRetorno[0]->celular;
            $this->correo = $lstRetorno[0]->correo;
            $this->dni = $lstRetorno[0]->dni;
            $this->clave = $lstRetorno[0]->clave;
            $this->idcliente = $lstRetorno[0]->idcliente;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE clientes SET
              nombre='$this->nombre',
              direccion='$this->direccion',
              celular='$this->celular',
              correo='$this->correo',
              dni='$this->dni',
              clave='$this->clave'
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
                direccion,
                celular,
                correo,
                dni,
                clave
              ) VALUES (?, ?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->direccion,
            $this->celular,
            $this->correo,
            $this->dni,
            $this->clave
        ]);
        return $this->idcliente = DB::getPdo()->lastInsertId();
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'nombre',
            1 => 'dni',
            2 => 'direccion',
            3 => 'correo',
            4 => 'celular',
        );
        $sql = "SELECT DISTINCT
                idcliente,
                nombre,
                direccion,
                celular,
                correo,
                dni
              FROM clientes 
                WHERE 1=1
                ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR direccion LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR celular LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR correo LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR dni LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }

    public function existePedidoProducto($idCliente)
    {

        $sql = "SELECT
        fecha,
        total,
        fk_idsucursal,
        fk_idestado,
        fk_idcliente,
        idpedido
          FROM pedidos WHERE fk_idcliente = $idCliente";
        $lstRetorno = DB::select($sql);

        return (count($lstRetorno) > 0);
       
      }

  
}

?>
