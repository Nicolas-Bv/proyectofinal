<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{

    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'idpedido', 'fecha', 'total', 'fk_idsucursal', 'fk_idestado', 'fk_idcliente'
    ];


    protected $hidden = [];

    public function cargarDesdeRequest($request)
    {
        $this->idpedido = $request->input('id') != "0" ? $request->input('id') : $this->idpedido;
        $this->fecha = $request->input('txtFecha');
        $this->total = $request->input('txtTotal');
        $this->fk_idsucursal = $request->input('lstSucursal');
        $this->fk_idestado = $request->input('lstEstado');
        $this->fk_idcliente = $request->input('lstCliente');
    }



    public function obtenerTodos()
    {
        $sql = "SELECT
                idpedido,
                fecha,
                total,
                fk_idsucursal,
                fk_idestado,
                fk_idcliente
              FROM pedidos ORDER BY idpedido DESC";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    public function obtenerPorId($idPedido)
    {
        $sql = "SELECT
                idpedido,
                fecha,
                total,
                fk_idsucursal,
                fk_idestado,
                fk_idcliente
                  FROM pedidos WHERE idpedido = $idPedido";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->idpedido = $lstRetorno[0]->idpedido;
            $this->fecha = $lstRetorno[0]->fecha;
            $this->total = $lstRetorno[0]->total;
            $this->fk_idsucursal = $lstRetorno[0]->fk_idsucursal;
            $this->fk_idcliente = $lstRetorno[0]->fk_idcliente;
            $this->fk_idestado = $lstRetorno[0]->fk_idestado;

            return $this;
        }
    
        
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE pedidos SET
              fecha='$this->fecha',
              total=$this->total,
              fk_idsucursal=$this->fk_idsucursal,
              fk_idcliente=$this->fk_idcliente,
              fk_idestado=$this->fk_idestado
              WHERE idpedido=?";
        $affected = DB::update($sql, [$this->idpedido]);
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'fecha',
            1 => 'total',
            2 => 'fk_idsucursal',
            3 => 'fk_idcliente',
            4 => 'fk_idestado',
        );
        $sql = "SELECT DISTINCT
                A.idpedido,
                A.fecha,
                A.total,
                A.fk_idsucursal,
                A.fk_idcliente,
                A.fk_idestado,
                C.nombre AS cliente,
                D.nombre AS estado,
                B.nombre AS sucursal
              FROM pedidos A
				INNER JOIN sucursales B ON A.fk_idsucursal = B.idsucursal 
				INNER JOIN clientes C ON A.fk_idcliente = C.idcliente
			    INNER JOIN estados D ON A.fk_idestado = D.idestado 
                WHERE 1=1
                ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( fecha LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR total LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR fk_idsucursal LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR fk_idcliente LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR fk_idestado LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR idpedido LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }


    public function eliminar()
    {
        $sql = "DELETE FROM pedidos WHERE
              idpedido=?";
        $affected = DB::delete($sql, [$this->idpedido]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO pedidos (
                fecha,
                total,
                fk_idsucursal,
                fk_idestado,
                fk_idcliente
              ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->fecha,
            $this->total,
            $this->fk_idsucursal,
            $this->fk_idcliente,
            $this->fk_idestado
        ]);
        return $this->idpedido = DB::getPdo()->lastInsertId();
    }

    public function existePedidoCliente($idCliente)
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
