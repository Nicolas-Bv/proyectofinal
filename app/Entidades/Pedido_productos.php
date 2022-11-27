<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{

    protected $table = 'pedido_productos';
    public $timestamps = false;

    protected $fillable = [
        'idpedidoproductos', 'fk_idproducto', 'fk_idpedido', 'precio_unitario', 'cantidad', 'total'
    ];


    protected $hidden = [];


    public function obtenerTodos()
    {
        $sql = "SELECT
                  fk_idproducto,
                  fk_idpedido,
                  idpedidoproducto,
                  cantidad,
                  precio_unitario,
                  total
              FROM pedido_productos ORDER BY idpedidoproducto ASC";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    public function obtenerPorId($idPedidoProducto)
    {
        $sql = "SELECT
                  fk_idproducto,
                  fk_idpedido,
                  idpedidoproducto,
                  cantidad,
                  precio_unitario,
                  total
                  FROM pedido_productos WHERE idpedidoproducto = $idPedidoProducto";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->fk_idpedido = $lstRetorno[0]->fk_idpedido;
            $this->fk_idproducto = $lstRetorno[0]->fk_idproducto;
            $this->idpedidoproducto = $lstRetorno[0]->idpedidoproducto;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE pedido_productos SET
              fk_idpedido=$this->fk_idpedidos,
              fk_idproducto=$this->fk_idproducto,
              precio_unitario=$this->precio_unitario,
              total=$this->total,
              cantidad=$this->cantidad
              WHERE idcliente=?";
        $affected = DB::update($sql, [$this->idcliente]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM pedido_productos WHERE
              idpedidoproducto=?";
        $affected = DB::delete($sql, [$this->idpedidoproducto]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO pedido_productos (
                fk_idpedido,
                fk_idproducto,
                cantidad,
                precio_unitario,
                total
              ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->fk_idpedido,
            $this->idpedidoproducto,
            $this->cantidad,
            $this->precio_unitario,
            $this->total
        ]);
        return $this->idpedidoproducto = DB::getPdo()->lastInsertId();
    }


    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'cantidad',
            1 => 'precio_unitario',
            2 => 'total',
        
        );
        $sql = "SELECT DISTINCT
                idpedidoproducto,
                cantidad,
                precio_unitario,
                total
              FROM pedido_productos
                WHERE 1=1
                ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( cantidad LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR precio_unitario LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR total LIKE '%" . $request['search']['value'] . "%' ";        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }

}
