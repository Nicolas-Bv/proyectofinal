<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{

    protected $table = 'pedido_productos';
    public $timestamps = false;

    protected $fillable = [
        'idpedidoproductos', 'fk_idproducto', 'fk_idpedido'
    ];


    protected $hidden = [];


    public function obtenerTodos()
    {
        $sql = "SELECT
                  fk_idproducto,
                  fk_idpedido,
                  idpedidoproducto
              FROM pedido_productos ORDER BY idpedidoproducto ASC";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    public function obtenerPorId($idPedidoProducto)
    {
        $sql = "SELECT
                  fk_idproducto,
                  fk_idpedido,
                  idpedidoproducto
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
              fk_idpedido=$this->fk_idpedido,
              fk_idproducto=$this->fk_idproducto,
              idpedidoproducto=$this->idpedidoproducto
              WHERE idpedidoproducto=?";
        $affected = DB::update($sql, [$this->idpedidoproducto]);
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
                idpedidoproducto
              ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->fk_idpedido,
            $this->idpedidoproducto,
            $this->fk_idproducto
        ]);
        return $this->idpedidoproducto = DB::getPdo()->lastInsertId();
    }

}