<?php

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

Route::get('/', function () {
    return view('contenido.contenido');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

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

//rutas de los clientes
Route::get('/clientes','ClientController@index');
Route::post('/clientes/agregar','ClientController@store');
Route::put('/clientes/actualizar','ClientController@update');

//rutas de los proveedores
Route::get('/proveedores','ProviderController@index');
Route::post('/proveedores/agregar','ProviderController@store');
Route::put('/proveedores/actualizar','ProviderController@update');
