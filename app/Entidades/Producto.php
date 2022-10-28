<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
      protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'titulo','cantidad','precio', 'descripcion','imagen','idproducto','fk_idtipoproducto'
    ];
    
    
    protected $hidden = [

];


public function obtenerTodos()
{
    $sql = "SELECT
              titulo,
              cantidad,
              precio,
              descipcion,
              imagen,
              idproducto,
              fk_idtipoproducto
            FROM productos ORDER BY titulo ASC";
    $lstRetorno = DB::select($sql);
    return $lstRetorno;
}

public function obtenerPorId($idproducto)
    {
        $sql = "SELECT
              titulo,
              cantidad,
              precio,
              descipcion,
              imagen,
              idproducto,
              fk_idtipoproducto
                FROM productos WHERE idproducto = $idproducto";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->titulo = $lstRetorno[0]->titulo;
            $this->cantidad = $lstRetorno[0]->cantidad;
            $this->precio = $lstRetorno[0]->precio;
            $this->descripcion = $lstRetorno[0]->descripcion;
            $this->imagen = $lstRetorno[0]->imagen;
            $this->idproducto = $lstRetorno[0]->idproducto;
            $this->fk_idtipoproducto = $lstRetorno[0]->fk_idtipoproducto;
            return $this;
        }
        return null;
    }

    public function obtenerPorTipo($idTipoProducto)
{
    $sql = "SELECT
              titulo,
              cantidad,
              precio,
              descipcion,
              imagen,
              idproducto,
              fk_idtipoproducto
            FROM productos WHERE fk_idtipoproducto  = $idTipoProducto";
    $lstRetorno = DB::select($sql);
    return $lstRetorno;
}

    public function guardar() {
        $sql = "UPDATE productos SET
            titulo='$this->titulo',
            cantidad=$this->cantidad,
            precio=$this->precio,
            descripcion='$this->descripcion',
            imagen='$this->imagen',
            fk_idtipoproducto=$this->fk_idtipoproducto
            WHERE idmenu=?";
        $affected = DB::update($sql, [$this->idproducto]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM productos WHERE
            idproducto=?";
        $affected = DB::delete($sql, [$this->idproducto]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO productos (
                titulo,
                cantidad,
                precio,
                descripcion,
                imagen,
                fk_idtipoproducto
            ) VALUES (?, ?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->titulo,
            $this->cantidad,
            $this->precio,
            $this->descripcion,
            $this->imagen,
            $this->fk_idtipoproducto,
        ]);
        return $this->idproducto = DB::getPdo()->lastInsertId();
    }

}

?>