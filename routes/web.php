<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrestamoController;
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
    return redirect('/admin');
});



Route::get('/reset-db', function () {
    App\Prestamo::truncate();
    App\PrestamoPlane::truncate();
    App\Cliente::truncate();
    App\ClientePodere::truncate();
    App\PrestamoBono::truncate();
    App\Gasto::truncate();
    App\Reporte::truncate();
    return redirect('/admin');
})->name('reset-db');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    // Route::get('/reportes', function () {
    //     return view('reportes');
    // });

    Route::get('bot-whatsapp', function () {
        return view('bot-whatsapp');
        // return redirect('/admin');
    });

    Route::post('/prestamo/store', [PrestamoController::class, 'prestamo_store'])->name('prestamo_store');
    Route::get('/pdf/prestamo/{id}', [PrestamoController::class, 'pdf_prestamo'])->name('pdf_prestamo');
});
