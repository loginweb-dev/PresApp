<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('prestamos/store', function (Request $request) {
    
    $new = App\Prestamo::create([
        'cliente_id' => $request->cliente_id,
        'tipo_id' => $request->tipo_id,
        'user_id' => $request->user_id,
        'plazo' => $request->plazo,
        'monto' => $request->monto,
        'interes' => $request->interes,
        'cuota' => $request->cuota,
        'observacion' => $request->observacion,
        'mes_inicio' => $request->mes_inicio,
        'estado_id' => $request->estado_id
    ]);

    $micount =  json_decode($request->miplan);
    for ($i=0; $i < count($micount); $i++) { 
        App\PrestamoPlane::create([
            'mes' => $micount[$i]->mes,
            'nro' => $micount[$i]->nro,
            'monto' => $micount[$i]->monto,
            'interes' => $micount[$i]->interes,
            'capital' => $micount[$i]->capital,
            'cuota' => $micount[$i]->cuota,
            'deuda' => $micount[$i]->deuda,
            'pagado' => 0,
            'prestamo_id' => $new->id,
            'observacion' => null
        ]);
    }
    return $new;
});
