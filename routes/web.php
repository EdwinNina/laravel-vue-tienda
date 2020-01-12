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

