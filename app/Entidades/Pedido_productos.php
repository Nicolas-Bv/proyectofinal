<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{

      protected $table = 'pedidos_productos';
      public $timestamps = false;
  
      protected $fillable = [
          'idpedido_producto','cantidad','precio_unitario', 'total','fk_idproducto','fk_idpedido'
      ];
      
      
      protected $hidden = [
  
  ];
  
  
  public function obtenerTodos()
  {
      $sql = "SELECT
                cantidad,
                precio_unitario,
                total,
                fk_idproducto,
                fk_idpedido,
                idpedido_productos
              FROM pedido_productos ORDER BY idpedido DESC";
      $lstRetorno = DB::select($sql);
      return $lstRetorno;
  }
  
  public function obtenerPorId($idPedidoProducto)
      {
          $sql = "SELECT
                cantidad,
                precio_unitario,
                total,
                fk_idproducto,
                fk_idpedido,
                idpedido_productos
                  FROM pedidos_productos WHERE idpedido_prpducto = $idPedidoProducto";
          $lstRetorno = DB::select($sql);
  
          if (count($lstRetorno) > 0) {
              $this->cantidad = $lstRetorno[0]->cantidad;
              $this->precio_unitario = $lstRetorno[0]->precio_unitario;
              $this->total = $lstRetorno[0]->total;
              $this->fk_idproducto = $lstRetorno[0]->fk_idproducto;
              $this->fk_idpedido = $lstRetorno[0]->fk_idpedido;
              $this->idpedido_producto = $lstRetorno[0]->idpedido_producto;
              return $this;
          }
          return null;
      }
  
      public function guardar() {
          $sql = "UPDATE pedidos_productos SET
              cantidad=$this->cantidad,
              precio_unitario=$this->precio_unitario,
              total=$this->total,
              fk_idproducto=$this->fk_idproducto,
              fk_idpedido=$this->fk_idpedido,
              WHERE idpedido_producto=?";
          $affected = DB::update($sql, [$this->idpedido_producto]);
      }
  
      public function eliminar()
      {
          $sql = "DELETE FROM pedidos_productos WHERE
              idpedido_producto=?";
          $affected = DB::delete($sql, [$this->idpedido_producto]);
      }
  
      public function insertar()
      {
          $sql = "INSERT INTO pedidos_productos (
                cantidad,
                precio_unitario,
                total,
                fk_idproducto,
                fk_idpedido,
              ) VALUES (?, ?, ?, ?, ?);";
          $result = DB::insert($sql, [
              $this->cantidad,
              $this->precio_unitario,
              $this->total,
              $this->fk_idproducto,
              $this->fk_idpedido,
          ]);
          return $this->idpedido_producto = DB::getPdo()->lastInsertId();
      }
  
  
  
  

}
