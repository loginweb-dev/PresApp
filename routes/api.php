<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
        'estado_id' => $request->estado_id,
        'fecha_prestamos' => $request->fecha_prestamos
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
            'observacion' => null,
            'pasarela_id' => null,
            'fecha' => Carbon::parse($micount[$i]->fecha)->format('Y-m-d')
        ]);
    }
    return true;
});


// planes-------------------------------------------------------
Route::get('plan/{id}', function ($id) {
    return App\PrestamoPlane::where("id", $id)->with("pasarelas")->first();
});

Route::post('plan/update', function (Request $request) {
    $new = App\PrestamoPlane::find($request->id);
    $new->pagado = 1;
    $new->fecha_pago = $request->fecha_pago;
    $new->observacion = $request->observacion;
    $new->pasarela_id = $request->pasarela_id;
    $new->user_id = $request->user_id;
    $new->save();
    return $new;
});

// tipos-------------------------------------------------------
Route::get('tipo/{id}', function ($id) {
    return App\PrestamoTipo::find($id);
});

// reportes-------------------------------------------------------
Route::get('reportes/calcular/{mes}', function ($mes) {
    $from = date('2023-06-01');
    $to = date('2023-06-30');

    $prestamos = App\Prestamo::whereBetween('created_at', [$from, $to])->get();
    $pagos = App\PrestamoPlane::whereBetween('fecha_pago', [$from, $to])->get();
    $gastos = App\Gasto::whereBetween('created_at', [$from, $to])->get();
    $midata = array([
        'prestamos' => $prestamos,
        'pagos' => $pagos,
        'gastos' => $gastos
    ]);
    return $midata;
});