<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();
            //url  -      controlador       -     vista
Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/pdf', 'Livewire\PdfController@PDF')->name('descargarPDF');
Route::get('/pdfFacturas', 'PdfController@PDFFacturas')->name('pdfFacturas');
Route::get('/pdfFactDel/{id}', 'PdfController@PDFFactDel')->name('pdfFactDel');

Route::view('rubros', 'rubros');
Route::view('productos', 'productos');
Route::view('clientes', 'clientes');
Route::view('empleados', 'empleados');
Route::view('facturas', 'facturas');
Route::view('tipogastos', 'tipogastos');
Route::view('gastos', 'gastos');
Route::view('cajas', 'cajas');
Route::view('cajarepartidor', 'cajarepartidor');
Route::view('empresas', 'empresas');


//rutas de impresion
Route::get('print/visita/{id}', 'PrinterController@ticketVisita');
Route::get('print/pension/{id}', 'PrinterController@ticketPension');