<?php
 //use Carbon\Carbon; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/*Route::get('/time' , function(){$date =new Carbon;echo $date ; } );*/


Route::group(array('domain' => '127.0.0.1'), function () {

    Route::get('/', 'ControladorWebHome@index');
 

    Route::get('/admin', 'ControladorHome@index');
    Route::post('/admin/patente/nuevo', 'ControladorPatente@guardar');

/* --------------------------------------------- */
/* CONTROLADOR LOGIN                           */
/* --------------------------------------------- */
    Route::get('/admin/login', 'ControladorLogin@index');
    Route::get('/admin/logout', 'ControladorLogin@logout');
    Route::post('/admin/logout', 'ControladorLogin@entrar');
    Route::post('/admin/login', 'ControladorLogin@entrar');

/* --------------------------------------------- */
/* CONTROLADOR RECUPERO CLAVE                    */
/* --------------------------------------------- */
    Route::get('/admin/recupero-clave', 'ControladorRecuperoClave@index');
    Route::post('/admin/recupero-clave', 'ControladorRecuperoClave@recuperar');

/* --------------------------------------------- */
/* CONTROLADOR PERMISO                           */
/* --------------------------------------------- */
    Route::get('/admin/usuarios/cargarGrillaFamiliaDisponibles', 'ControladorPermiso@cargarGrillaFamiliaDisponibles')->name('usuarios.cargarGrillaFamiliaDisponibles');
    Route::get('/admin/usuarios/cargarGrillaFamiliasDelUsuario', 'ControladorPermiso@cargarGrillaFamiliasDelUsuario')->name('usuarios.cargarGrillaFamiliasDelUsuario');
    Route::get('/admin/permisos', 'ControladorPermiso@index');
    Route::get('/admin/permisos/cargarGrilla', 'ControladorPermiso@cargarGrilla')->name('permiso.cargarGrilla');
    Route::get('/admin/permiso/nuevo', 'ControladorPermiso@nuevo');
    Route::get('/admin/permiso/cargarGrillaPatentesPorFamilia', 'ControladorPermiso@cargarGrillaPatentesPorFamilia')->name('permiso.cargarGrillaPatentesPorFamilia');
    Route::get('/admin/permiso/cargarGrillaPatentesDisponibles', 'ControladorPermiso@cargarGrillaPatentesDisponibles')->name('permiso.cargarGrillaPatentesDisponibles');
    Route::get('/admin/permiso/{idpermiso}', 'ControladorPermiso@editar');
    Route::post('/admin/permiso/{idpermiso}', 'ControladorPermiso@guardar');

/* --------------------------------------------- */
/* CONTROLADOR GRUPO                             */
/* --------------------------------------------- */
    Route::get('/admin/grupos', 'ControladorGrupo@index');
    Route::get('/admin/usuarios/cargarGrillaGruposDelUsuario', 'ControladorGrupo@cargarGrillaGruposDelUsuario')->name('usuarios.cargarGrillaGruposDelUsuario'); //otra cosa
    Route::get('/admin/usuarios/cargarGrillaGruposDisponibles', 'ControladorGrupo@cargarGrillaGruposDisponibles')->name('usuarios.cargarGrillaGruposDisponibles'); //otra cosa
    Route::get('/admin/grupos/cargarGrilla', 'ControladorGrupo@cargarGrilla')->name('grupo.cargarGrilla');
    Route::get('/admin/grupo/nuevo', 'ControladorGrupo@nuevo');
    Route::get('/admin/grupo/setearGrupo', 'ControladorGrupo@setearGrupo');
    Route::post('/admin/grupo/nuevo', 'ControladorGrupo@guardar');
    Route::get('/admin/grupo/{idgrupo}', 'ControladorGrupo@editar');
    Route::post('/admin/grupo/{idgrupo}', 'ControladorGrupo@guardar');

/* --------------------------------------------- */
/* CONTROLADOR USUARIO                           */
/* --------------------------------------------- */
    Route::get('/admin/usuarios', 'ControladorUsuario@index');
    Route::get('/admin/usuarios/nuevo', 'ControladorUsuario@nuevo');
    Route::post('/admin/usuarios/nuevo', 'ControladorUsuario@guardar');
    Route::post('/admin/usuarios/{usuario}', 'ControladorUsuario@guardar');
    Route::get('/admin/usuarios/cargarGrilla', 'ControladorUsuario@cargarGrilla')->name('usuarios.cargarGrilla');
    Route::get('/admin/usuarios/buscarUsuario', 'ControladorUsuario@buscarUsuario');
    Route::get('/admin/usuarios/{usuario}', 'ControladorUsuario@editar');

/* --------------------------------------------- */
/* CONTROLADOR MENU                             */
/* --------------------------------------------- */
    Route::get('/admin/sistema/menu', 'ControladorMenu@index');
    Route::get('/admin/sistema/menu/nuevo', 'ControladorMenu@nuevo');
    Route::post('/admin/sistema/menu/nuevo', 'ControladorMenu@guardar');
    Route::get('/admin/sistema/menu/cargarGrilla', 'ControladorMenu@cargarGrilla')->name('menu.cargarGrilla');
    Route::get('/admin/sistema/menu/eliminar', 'ControladorMenu@eliminar');
    Route::get('/admin/sistema/menu/{id}', 'ControladorMenu@editar');
    Route::post('/admin/sistema/menu/{id}', 'ControladorMenu@guardar');

});

/* --------------------------------------------- */
/* CONTROLADOR PATENTES                          */
/* --------------------------------------------- */
Route::get('/admin/patentes', 'ControladorPatente@index');
Route::get('/admin/patente/nuevo', 'ControladorPatente@nuevo');
Route::post('/admin/patente/nuevo', 'ControladorPatente@guardar');
Route::get('/admin/patente/cargarGrilla', 'ControladorPatente@cargarGrilla')->name('patente.cargarGrilla');
Route::get('/admin/patente/eliminar', 'ControladorPatente@eliminar');
Route::get('/admin/patente/nuevo/{id}', 'ControladorPatente@editar');
Route::post('/admin/patente/nuevo/{id}', 'ControladorPatente@guardar');

/* --------------------------------------------- */
/* CONTROLADOR CLIENTES                       */
/* --------------------------------------------- */

Route::get('/admin/cliente/nuevo','ControladorCliente@nuevo');
Route::post('/admin/cliente/nuevo','ControladorCliente@guardar');
Route::get('/admin/clientes', 'ControladorCliente@index');
Route::get('/admin/cliente/eliminar','ControladorCliente@eliminar');
Route::get('/admin/cliente/cargarGrilla', 'ControladorCliente@cargarGrilla')->name('cliente.cargarGrilla');
Route::get('/admin/cliente/{idcliente}', 'ControladorCliente@editar');
Route::post('/admin/cliente/{idcliente}', 'ControladorCliente@guardar');

/* --------------------------------------------- */
/* CONTROLADOR PRODUCTOS                     */
/* --------------------------------------------- */

Route::get('/admin/producto/nuevo','ControladorProducto@nuevo');
Route::post('/admin/producto/nuevo','ControladorProducto@guardar');
Route::get('/admin/productos', 'ControladorProducto@index');
Route::get('/admin/producto/eliminar','ControladorProducto@eliminar');
Route::get('/admin/producto/cargarGrilla', 'ControladorProducto@cargarGrilla')->name('producto.cargarGrilla');
Route::get('/admin/producto/{idproducto}', 'ControladorProducto@editar');
Route::post('/admin/producto/{idproducto}', 'ControladorProducto@guardar');
/* --------------------------------------------- */
/* CONTROLADOR PEDIDOS                       */
/* --------------------------------------------- */

Route::get('/admin/pedidos/nuevo','ControladorPedido@nuevo');
Route::post('/admin/pedidos/nuevo','ControladorPedido@guardar');
Route::get('/admin/pedidos', 'ControladorPedido@index');
Route::get('/admin/pedidos/eliminar','ControladorPedido@eliminar');
Route::get('/admin/pedido/cargarGrilla', 'ControladorPedido@cargarGrilla')->name('pedido.cargarGrilla');
Route::get('/admin/pedidos/{idpedido}', 'ControladorPedido@editar');
Route::post('/admin/pedidos/{idpedido}', 'ControladorPedido@guardar');
/* --------------------------------------------- */
/* CONTROLADOR POSTULACIONES                      */
/* --------------------------------------------- */

Route::get('/admin/postulacion/nuevo','ControladorPostulacion@nuevo');
Route::post('/admin/postulacion/nuevo','ControladorPostulacion@guardar');
Route::get('/admin/postulaciones','ControladorPostulacion@index');
Route::get('/admin/postulacion/eliminar','ControladorPostulacion@eliminar');
Route::get('/admin/postulacion/cargarGrilla', 'ControladorPostulacion@cargarGrilla')->name('postulacion.cargarGrilla');
Route::get('/admin/postulacion/{idpostulacion}','ControladorPostulacion@editar');
Route::post('/admin/postulacion/{idpostulacion}','ControladorPostulacion@guardar');
/* --------------------------------------------- */
/* CONTROLADOR SUCURSALES                       */
/* --------------------------------------------- */

Route::get('/admin/sucursal/nuevo','ControladorSucursal@nuevo');
Route::post('/admin/sucursal/nuevo','ControladorSucursal@guardar');
Route::get('/admin/sucursales','ControladorSucursal@index');
Route::get('/admin/sucursal/eliminar','ControladorSucursal@eliminar');
Route::get('/admin/sucursal/cargarGrilla', 'ControladorSucursal@cargarGrilla')->name('sucursal.cargarGrilla');
Route::get('/admin/sucursal/{idsucursal}','ControladorSucursal@editar');
Route::post('/admin/sucursal/{idsucursal}','ControladorSucursal@guardar');

/* --------------------------------------------- */
/* CONTROLADOR CATEGORIAS                     */
/* --------------------------------------------- */

Route::get('/admin/categoria/nuevo','ControladorCategoria@nuevo');
Route::post('/admin/categoria/nuevo','ControladorCategoria@guardar');
Route::get('/admin/categoria/eliminar','ControladorCategoria@eliminar');
Route::get('/admin/categorias','ControladorCategoria@index');
Route::get('/admin/categoria/cargarGrilla', 'ControladorCategoria@cargarGrilla')->name('categoria.cargarGrilla');
Route::get('/admin/categoria/{idcategoria}','ControladorCategoria@editar');
Route::post('/admin/categoria/{idcategoria}','ControladorCategoria@guardar');




