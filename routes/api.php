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

// prestamos -----------------------------------------------
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
    return $new;
});

Route::post('upload', function (Request $request) {
    if($request->file('documentos')) {
        $destinationPath = 'prestamos';
        $myimage = $request->documentos->getClientOriginalName();
        $destino = $request->documentos->move(public_path($destinationPath), $myimage);
        
        $prest = App\Prestamo::find($request->prestamo_id);
        $prest->documentos = $destinationPath."/".$myimage;
        $prest->save();
    }
    return true;
});



// planes-------------------------------------------------------
Route::get('plan/{id}', function ($id) {
    return App\PrestamoPlane::where("id", $id)->with("pasarelas", "user")->first();
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

Route::post('plan/update/mora', function (Request $request) {
    $new = App\PrestamoPlane::find($request->id);
    $new->pagado = 2;
    $new->fecha_pago = $request->fecha_pago;
    $new->observacion = $request->observacion;
    $new->pasarela_id = $request->pasarela_id;
    $new->user_id = $request->user_id;
    $new->mora = $request->mora;
    $new->deuda = $request->deuda;
    $new->capital = $request->capital;
    $new->cuota = $request->cuota;
    $new->interes = $request->interes;
    $new->save();

    //actualizar siguiente pago
    $minum = $request->id + 1;
    $sigui = App\PrestamoPlane::find($minum);
    $sigui->monto = $request->deuda;
    $sigui->cuota = ($sigui->cuota + $request->mora);
    if($request->tipo_id == 2){
        $sigui->interes = ($sigui->deuda * 0.05);
        $sigui->capital =  ($sigui->cuota + $request->mora) - ($sigui->deuda *0.05);
    }else if($request->tipo_id == 1){
        $sigui->capital =  ($sigui->cuota + $request->mora) - ($sigui->monto_inicial *0.03);
    }    
    $sigui->save();
    return true;
});

Route::post('plan/refin', function (Request $request) {
    DB::table('prestamo_planes')->where('pagado', 0)->where("prestamo_id", $request->prestamo_id)->delete();

    $micount =  json_decode($request->miplan);    
    for ($i=0; $i < count($micount); $i++) { 
        $minew = App\PrestamoPlane::create([
            'mes' => $micount[$i]->mes,
            'nro' => $micount[$i]->nro,
            'monto' => $micount[$i]->monto,
            'interes' => $micount[$i]->interes,
            'capital' => $micount[$i]->capital,
            'cuota' => $micount[$i]->cuota,
            'deuda' => $micount[$i]->deuda,
            'pagado' => 0,
            'prestamo_id' => $request->prestamo_id,
            'observacion' => null,
            'pasarela_id' => null,
            'fecha' => Carbon::parse($micount[$i]->fecha)->format('Y-m-d')
        ]);
        if ($i == 0) {
            $mplane = App\PrestamoPlane::find($minew->id);
            $mplane->refin = $request->new_monto;
            $mplane->save();
        }
    }
    return $micount;
});

// tipos-------------------------------------------------------
Route::get('tipo/{id}', function ($id) {
    return App\PrestamoTipo::find($id);
});

// reportes-------------------------------------------------------
Route::get('reportes/calcular/{mes}/editor/{user_id}', function ($mes, $user_id) {
    // return $user_id;
    
    $from = date("Y-m-01", strtotime($mes));
    $to = date("Y-m-t", strtotime($mes));

    $miuser = App\Models\User::find($user_id);
    if ($miuser->role_id === 1) {
        $prestamos = App\Prestamo::whereBetween('fecha_prestamos', [$from, $to])->with("user")->get();
        $pagos = App\PrestamoPlane::whereBetween('fecha_pago', [$from, $to])->with("user")->get();
        $gastos = App\Gasto::whereBetween('fecha', [$from, $to])->with("user")->get();
    }else{
        $prestamos = App\Prestamo::whereBetween('fecha_prestamos', [$from, $to])->where("user_id", $user_id)->with("user")->get();
        $pagos = App\PrestamoPlane::whereBetween('fecha_pago', [$from, $to])->where("user_id", $user_id)->with("user")->get();
        $gastos = App\Gasto::whereBetween('fecha', [$from, $to])->where("user_id", $user_id)->with("user")->get();
    }
    $midata = array([
        'prestamos' => $prestamos,
        'pagos' => $pagos,
        'gastos' => $gastos
    ]);
    return $midata;
});

// clientes -------------------------------------------------------
Route::get('cliente/prestamo/{id}', function ($id) {
    return App\Prestamo::where("cliente_id", $id)->where("estado_id", 1)->with("cliente")->first();
});
