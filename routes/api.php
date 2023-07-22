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
        'fecha_prestamos' => $request->fecha_prestamos,
        'codigo' => $request->codigo
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
Route::get('prestamo/{id}', function ($id) {
    return App\Prestamo::where('id', $id)->with('cliente', 'tipo', 'user')->first();
});

// planes-------------------------------------------------------
Route::get('plan/{id}', function ($id) {
    return App\PrestamoPlane::where("id", $id)->with("pasarelas", "user")->first();
});
Route::post('plan/update', function (Request $request) {
    // return $request;
    $new = App\PrestamoPlane::find($request->id);
    $new->pagado = 1;
    $new->fecha_pago = $request->fecha_pago;
    $new->observacion = $request->observacion;
    $new->pasarela_id = $request->pasarela_id;
    $new->user_id = $request->user_id;
    $new->p_final = $request->p_final;
    $new->save();
    return $new;
});
Route::post('plan/update/mora', function (Request $request) {
    $new = App\PrestamoPlane::find($request->plan_id);

    // acutuliar el pago
    $misuma = $new->cuota - $request->pago_parcial;
    $new->pagado = 2; //cat mora
    $new->fecha_pago = $request->mora_fecha;
    $new->observacion = $request->mora_detalle;
    $new->pasarela_id = $request->mora_pasarela;
    $new->user_id = $request->user_id;
    $new->deuda = $request->nueva_deuda;
    $new->cuota = $request->pago_parcial;
    $new->mora = $misuma;
    if($request->pago_parcial == $new->interes){
        $new->capital = 0;
    }elseif($request->pago_parcial < $new->interes){
        $new->interes = $new->interes - $request->pago_parcial;
        $new->capital = 0;
    }else{
        $new->capital = $request->pago_parcial - $new->interes;   
    }
    $new->save();


    //actualizar todos los pagos
    $miupdate = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
    $miaux = 0;
    foreach ($miupdate as $index => $item) {
        $miitem = App\PrestamoPlane::find($item->id);
        $miitem->monto = ($index==0) ? $request->nueva_deuda : $miaux;
        if($request->tipo_id == 1){
            // $miitem->intere | interes no se modifica
            $miitem->capital = $miitem->cuota - $miitem->interes;
            $miaux = $miitem->monto - ($miitem->cuota - $miitem->interes);
            $miitem->deuda = $miaux;  
        }else if($request->tipo_id == 2){
            $miitem->interes = $miitem->monto * 0.05;
            $miitem->capital = $miitem->cuota - ($miitem->monto * 0.05);
            $miaux = $miitem->monto - ($miitem->cuota - ($miitem->monto * 0.05));
            $miitem->deuda = $miaux;  
        }         
        $miitem->save();        
    }

    //add row
    $ultimoplan = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("nro", $request->plazo)->first();
    $nuevomes = date("Y-m-d",strtotime($ultimoplan->fecha."+ 1 month"));
    $ni=0;
    $nc=0;
    $ncu=0;
    if($request->tipo_id == 1){
        $ni=0.03*$miaux; 
        $nc=$miaux-$ni;
        $ncu = $nc+$ni;
    }else if($request->tipo_id == 2){
        $ni=0.05*$miaux; 
        $nc=$miaux-$ni;
        $ncu = $nc+$ni;
    }
    $nuevoplan = App\PrestamoPlane::create([
        'mes' => date("F-Y", strtotime($nuevomes)),
        'nro' => $request->plazo + 1,
        'monto' => $miaux,
        'interes' => $ni,
        'capital' => $nc,
        'cuota' => $ncu,
        'deuda' => 0,
        'pagado' => 0,
        'prestamo_id' => $request->prestamo_id,
        'observacion' => null,
        'pasarela_id' => null,
        'fecha' =>Carbon::parse($nuevomes)->format('Y-m-d')
    ]);

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
Route::post('plan/mora/dias', function (Request $request) {
    // return $request;
    $midiff = date_diff(date_create($request->fecha), date_create(date("Y-m-d")));
    $dias_mora = $midiff->format("%a");

    $interes_mora = 0;
    $total_mora = $request->cuota;
    $DiasMes= date('t'); 
    // return $DiasMes;
    if ($dias_mora > 0) {
        if ($request->tipo_id == 1 ) {
            $interes_mora = ($request->monto * 0.03)/$DiasMes;
        }else if($request->tipo_id == 2){
            $interes_mora = ($request->monto * 0.05)/$DiasMes;
        }                                
        $total_mora = ($interes_mora*$dias_mora) + $request->cuota;
        $miseting = setting('prestamos.redondear');
        if ($miseting == "nor") {
            $total_mora = number_format($total_mora, 2, '.', '');    
            $interes_mora = number_format($interes_mora, 2, '.', '');              
        } else if($miseting == "rmx"){
            $total_mora = ceil($total_mora);  
            $interes_mora = ceil($interes_mora);  
        } else if($miseting == "rmi"){
                
        }
    }
    return response()->json(['dias_mora' => $dias_mora, 'interes_mora' => ($interes_mora*$dias_mora), 'total_mora' => $total_mora]);
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
Route::get('cliente/{id}', function ($id) {
    return App\Cliente::find($id);
});
Route::get('clientes', function () {
    return App\Cliente::get();
});

Route::get('cliente/prestamo/{id}', function ($id) {
    return App\Prestamo::where("cliente_id", $id)->where("estado_id", 1)->with("cliente")->first();
});

//Bonos--------------------------------------------
Route::post('bonos/calular', function (Request $request) {
    
    $midiff = date_diff(date_create($request->f_bono), date_create($request->f_prestamo));
    $dias = $midiff->format("%a");
    $meses = $midiff->format("%m");
    $interes =  ($request->m_bono * 0.03) * $meses;
    $m_prestamo = $request->m_bono - $interes; 
    return response()->json(['dias' => $dias, 'meses' => $meses, 'interes' => $interes, 'm_prestamo' => $m_prestamo]);
});


