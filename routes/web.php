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
    return redirect('/docs');
});



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/reportes', function () {
        return view('reportes');
    });

    Route::post('/prestamo/store', [PrestamoController::class, 'prestamo_store'])->name('prestamo_store');
    
});
