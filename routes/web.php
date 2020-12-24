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
    // return view('welcome');
    return view('login-register');
});

Auth::routes();
            //url  -      controlador       -     vista
Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/pdf', 'Livewire\PdfController@PDF')->name('descargarPDF');
Route::get('/pdfFacturas', 'PdfController@PDFFacturas')->name('pdfFacturas');
Route::get('/pdfFactDel/{id}', 'PdfController@PDFFactDel')->name('pdfFactDel');

Route::view('rubros', 'rubros')->middleware('permission:Rubros_index');
Route::view('productos', 'productos')->middleware('permission:Productos_index');
Route::view('clientes', 'clientes')->middleware('permission:Clientes_index');
Route::view('empleados', 'empleados')->middleware('permission:Empleados_index');
Route::view('facturas', 'facturas')->middleware('permission:Facturas_index');
// Route::view('tipogastos', 'tipogastos');
Route::view('gastos', 'gastos')->middleware('permission:Gastos_index');
Route::view('movimientos', 'movimientos')->middleware('permission:MovimientosDiarios_index');
Route::view('cortedecaja', 'cortedecaja')->middleware('permission:CorteDeCaja_index');
Route::view('cajarepartidor', 'cajarepartidor')->middleware('permission:CajaRepartidor_index');
Route::view('ventasdiarias', 'ventasdiarias')->middleware('permission:VentasDiarias_index');
Route::view('ventasporfechas', 'ventasporfechas')->middleware('permission:VentasPorFechas_index');
Route::view('usuarios', 'usuarios')->middleware('permission:Usuarios_index');

Route::view('permisos', 'permisos')->middleware('permission:Usuarios_index');
Route::view('empresa', 'empresa')->middleware('permission:Empresa_index');


//rutas de impresion
Route::get('print/visita/{id}', 'PrinterController@ticketVisita');
Route::get('print/pension/{id}', 'PrinterController@ticketPension');