<?php 
namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
      protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'titulo','cantidad','precio', 'descripcion','imagen','idproducto','fk_idcategoria'
    ];
    
    
    protected $hidden = [

];

public function cargarDesdeRequest($request)
    {
        $this->idproducto = $request->input('id') != "0" ? $request->input('id') : $this->idproducto;
        $this->titulo = $request->input('txtTitulo');
        $this->cantidad = $request->input('txtCantidad');
        $this->precio = $request->input('txtPrecio');
        $this->fk_idcategoria = $request->input('lstCategoria');
        $this->descripcion = $request->input('txtDescripcion');
    }



public function obtenerTodos()
{
    $sql = "SELECT
              titulo,
              cantidad,
              precio,
              descripcion,
              imagen,
              idproducto,
              fk_idcategoria
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
              descripcion,
              imagen,
              idproducto,
              fk_idcategoria
                FROM productos WHERE idproducto = $idproducto";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->titulo = $lstRetorno[0]->titulo;
            $this->cantidad = $lstRetorno[0]->cantidad;
            $this->precio = $lstRetorno[0]->precio;
            $this->descripcion = $lstRetorno[0]->descripcion;
            $this->imagen = $lstRetorno[0]->imagen;
            $this->idproducto = $lstRetorno[0]->idproducto;
            $this->fk_idcategoria = $lstRetorno[0]->fk_idcategoria;
            return $this;
        }
        return null;
    }

    public function obtenerPorTipo($idCategoria)
{
    $sql = "SELECT
              titulo,
              cantidad,
              precio,
              descripcion,
              imagen,
              idproducto,
              fk_idcategoria
            FROM productos WHERE fk_idcategoria  = $idCategoria";
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
            fk_idcategoria=$this->fk_idcategoria
            WHERE idproducto=?";
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
                fk_idcategoria
            ) VALUES (?, ?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->titulo,
            $this->cantidad,
            $this->precio,
            $this->descripcion,
            $this->imagen,
            $this->fk_idcategoria
        ]);
        return $this->idproducto = DB::getPdo()->lastInsertId();
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'titulo',
            1 => 'cantidad',
            2 => 'precio',
        );
        $sql = "SELECT DISTINCT
                idproducto,
                titulo,
                cantidad,
                precio
              FROM productos
                WHERE 1=1
                ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( titulo LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cantidad LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR precio LIKE '%" . $request['search']['value'] . "%' ";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }
    
    public function existeProductoPorCategoria($idCategoria)
    {

      $sql = "SELECT
              titulo,
              cantidad,
              precio,
              descripcion,
              idproducto,
              fk_idcategoria
        FROM productos WHERE fk_idcategoria = $idCategoria";
      $lstRetorno = DB::select($sql);

        return (count($lstRetorno) > 0);
       
      }
  

}

?>