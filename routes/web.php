<?php

Route::group(['middleware' => ['guest']], function () {
    //rutas login
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido.contenido');
    })->name('main');

    Route::get('/dashboard','DashboardController');
    Route::post('/notification/get','NotificationController@get');
    
    Route::group(['middleware' => ['Almacenero']], function () {

        //rutas de la categoria
        Route::get('/categoria','CategoryController@index');
        Route::post('/categoria/agregar','CategoryController@store');
        Route::put('/categoria/actualizar','CategoryController@update');
        Route::put('/categoria/activar','CategoryController@activar');
        Route::put('/categoria/desactivar','CategoryController@desactivar');
        Route::get('/categorias','CategoryController@seleccionarCategoria');

        //rutas de productos
        Route::get('/producto','ProductController@index');
        Route::post('/producto/agregar','ProductController@store');
        Route::put('/producto/actualizar','ProductController@update');
        Route::put('/producto/activar','ProductController@activar');
        Route::put('/producto/desactivar','ProductController@desactivar');
        Route::get('/producto/buscarProducto','ProductController@buscarProducto');
        Route::get('/producto/listarProductos','ProductController@listarProductos');
        
        //rutas de los proveedores
        Route::get('/proveedores','ProviderController@index');
        Route::get('/proveedores/seleccionarProveedores','ProviderController@seleccionarProveedores');
        Route::post('/proveedores/agregar','ProviderController@store');
        Route::put('/proveedores/actualizar','ProviderController@update');

        //rutas de la ingresos
        Route::get('/ingresos','IngresoController@index');
        Route::post('/ingreso/agregar','IngresoController@store');
        Route::put('/ingreso/desactivar','IngresoController@desactivar');
        Route::get('/ingreso/obtenerIngreso','IngresoController@obtenerIngreso');
        Route::get('/ingreso/obtenerDetalleIngreso','IngresoController@obtenerDetalleIngreso');
    });

    Route::group(['middleware' => ['Vendedor']], function () {

        //rutas de ventas
        Route::get('/ventas','VentaController@index');
        Route::post('/venta/agregar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerVenta','VentaController@obtenerVenta');
        Route::get('/venta/obtenerDetalleVenta','VentaController@obtenerDetalleVenta');
        
        //rutas de los clientes
        Route::get('/clientes','ClientController@index');
        Route::post('/clientes/agregar','ClientController@store');
        Route::put('/clientes/actualizar','ClientController@update');
        Route::get('/clientes/seleccionarCliente','ClientController@seleccionarCliente');

    });

    Route::group(['middleware' => ['Administrador']], function () {

        //rutas de la categoria
        Route::get('/categoria','CategoryController@index');
        Route::post('/categoria/agregar','CategoryController@store');
        Route::put('/categoria/actualizar','CategoryController@update');
        Route::put('/categoria/activar','CategoryController@activar');
        Route::put('/categoria/desactivar','CategoryController@desactivar');
        Route::get('/categorias','CategoryController@seleccionarCategoria');

        //rutas de productos
        Route::get('/producto','ProductController@index');
        Route::post('/producto/agregar','ProductController@store');
        Route::put('/producto/actualizar','ProductController@update');
        Route::put('/producto/activar','ProductController@activar');
        Route::put('/producto/desactivar','ProductController@desactivar');
        Route::get('/producto/buscarProducto','ProductController@buscarProducto');
        Route::get('/producto/listarProductos','ProductController@listarProductos');
        Route::get('/producto/buscarProductoVenta','ProductController@buscarProductoVenta');
        Route::get('/producto/listarProductosVenta','ProductController@listarProductosVenta');
        Route::get('/producto/listarPDF','ProductController@listarPDF')->name('productos_pdf');
        
        //rutas de los proveedores
        Route::get('/proveedores','ProviderController@index');
        Route::get('/proveedores/seleccionarProveedores','ProviderController@seleccionarProveedores');
        Route::post('/proveedores/agregar','ProviderController@store');
        Route::put('/proveedores/actualizar','ProviderController@update');

        //rutas de la ingresos
        Route::get('/ingresos','IngresoController@index');
        Route::post('/ingreso/agregar','IngresoController@store');
        Route::put('/ingreso/desactivar','IngresoController@desactivar');
        Route::get('/ingreso/obtenerIngreso','IngresoController@obtenerIngreso');
        Route::get('/ingreso/obtenerDetalleIngreso','IngresoController@obtenerDetalleIngreso');

        //rutas de roles
        Route::get('/roles','RoleController@index');
        Route::get('/obtenerRoles','RoleController@obtenerRoles');

        //rutas de usuarios
        Route::get('/usuario','UserController@index');
        Route::post('/usuario/agregar','UserController@store');
        Route::put('/usuario/actualizar','UserController@update');
        Route::put('/usuario/activar','UserController@activar');
        Route::put('/usuario/desactivar','UserController@desactivar');

        //rutas de ventas
        Route::get('/ventas','VentaController@index');
        Route::post('/venta/agregar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerVenta','VentaController@obtenerVenta');
        Route::get('/venta/obtenerDetalleVenta','VentaController@obtenerDetalleVenta');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        
        //rutas de los clientes
        Route::get('/clientes','ClientController@index');
        Route::post('/clientes/agregar','ClientController@store');
        Route::put('/clientes/actualizar','ClientController@update');
        Route::get('/clientes/seleccionarCliente','ClientController@seleccionarCliente');
        
    });
});
