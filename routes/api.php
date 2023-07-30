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
        'codigo' => $request->codigo,
        'clase' => $request->clase        
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
Route::get('prestamo/{id}', function ($codigo) {
    return App\Prestamo::where('codigo', $codigo)->with('cliente', 'tipo', 'user')->first();
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
Route::post('plan/mora', function (Request $request) {
    // return $request;
    $miplan = App\PrestamoPlane::find($request->plan_id);
    $miprestamo = App\Prestamo::find($request->prestamo_id);
    $mitipo = App\PrestamoTipo::find($miprestamo->tipo_id);
    
    $misuma = $miplan->cuota - $request->pago_parcial;
    $miplan->pagado = 2; //cat mora
    $miplan->fecha_pago = $request->mora_fecha;
    $miplan->observacion = $request->mora_detalle;
    $miplan->pasarela_id = $request->mora_pasarela;
    $miplan->user_id = $request->user_id;
    $miplan->deuda = $request->nueva_deuda;
    $miplan->cuota = $request->pago_parcial;
    $miplan->p_final = $request->pago_parcial;
    $miplan->mora = $misuma;
    if($request->pago_parcial == $miplan->interes){
        $miplan->capital = 0;
    }elseif($request->pago_parcial < $miplan->interes){
        $miplan->interes = $miplan->interes - $request->pago_parcial;
        $miplan->capital = 0;
    }else{
        $miplan->capital = $miplan->capital - ($request->pago_parcial - $miplan->interes);   
    }
    $miplan->save();

        //add row
        $miupdate = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
        $miaux = 0;
        foreach ($miupdate as $index => $item) {
            $miitem = App\PrestamoPlane::find($item->id);
            $miitem->monto = ($index==0) ? $request->nueva_deuda : $miaux;
            if($request->clase == 'Fijo'){
                // $miitem->interes | interes no se modifica
                $miitem->capital = $miprestamo->cuota - $miitem->interes;
                $miaux = $miitem->monto - ($miprestamo->cuota - $miitem->interes);
                $miitem->deuda = $miaux;  
            }else if($request->clase == 'Variable'){                
                $miitem->interes = $miitem->monto * $mitipo->monto_interes;
                $miitem->capital = $miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes);
                $miaux = $miitem->monto - ($miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes));
                $miitem->deuda = $miaux;  
            }         
            $miitem->save();        
        }

        $ultimoplan = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("nro", $request->plazo)->first();
        $nuevomes = date("Y-m-d",strtotime($ultimoplan->fecha."+ 1 month"));
        $ni=0; $nc=0; $ncu=0;
        if($request->clase == 'Fijo'){
            $ni=$mitipo->monto_interes * $miprestamo->monto; 
            $nc=$miaux-$ni;
            $ncu=$nc+$ni;
        }else if($request->clase == 'Variable'){
            $ni=$miaux*$mitipo->monto_interes; 
            $nc=$miaux-$ni;
            $ncu =$nc+$ni;
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

       $miprestamo->plazo = $request->plazo + 1;
       $miprestamo->save();
    return true;
});
Route::post('plan/refin', function (Request $request) {
    // return $request;
    $miplan=App\PrestamoPlane::find($request->plan_id-1);
    $miprestamo=App\Prestamo::find($request->prestamo_id);
    $mitipo=App\PrestamoTipo::find($miprestamo->tipo_id);

    //actualzar el plan anterior
    $miplan->observacion=$miplan->observacion."\n".$request->ref_detalle;
    $miplan->pagado=3; //cat refin
    $miplan->refin=$request->ref_nuevo_monto;
    $miplan->deuda=$request->ref_nueva_deuda;
    $miplan->save();

    $miupdate=App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
    $miaux=0;
    $milultimo=false;
    $micount=count($miupdate);
    for($i=0; $i<$micount; $i++) {
        $miitem=App\PrestamoPlane::find($miupdate[$i]->id);
        $miitem->monto=($i==0) ? $request->ref_nueva_deuda : $miaux;
        if($request->clase == 'Fijo'){
            // $miitem->intere | interes no se modifica
            $miitem->capital=$miprestamo->cuota - $miitem->interes;
            $miaux=$miitem->monto - ($miprestamo->cuota - $miitem->interes);
            $miitem->deuda=$miaux; 
        }else if($request->clase == 'Variable'){   
            $miitem->interes=$miitem->monto * $mitipo->monto_interes;
            $miitem->capital=$miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes);
            $miaux=$miitem->monto - ($miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes));
            $miitem->deuda=$miaux;  
        }         
        $miitem->save();       
    }

    //agregando nuevos meses------------------------------------------------------------
    
    $new_count=ceil($miaux / $miprestamo->cuota);
    $ultimoplan=App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("nro", $request->plazo)->first();
    $miplazo=$request->plazo;
    $nuevomes=$ultimoplan->fecha;
    $milultimo=false;
    for($i=0; $i<$new_count; $i++) {
        

        if ($milultimo) {
            $nuevomes=date("Y-m-d",strtotime($nuevomes."+ 1 month"));
            $ni=0; $nc=0; $n_deuda=0;
            if($request->clase == 'Fijo'){
                $ni=$mitipo->monto_interes * $miprestamo->monto; 
                $nc=$miaux - ($mitipo->monto_interes * $miprestamo->monto);
            }else if($request->clase == 'Variable'){
                $ni=$mitipo->monto_interes * $miaux;
                $nc=$miaux - ($miaux * $mitipo->monto_interes); 
            }
            App\PrestamoPlane::create([
                'mes' => date("F-Y", strtotime($nuevomes)),
                'nro' => $miplazo+1,
                'monto' => $miaux,
                'interes' => $ni,
                'capital' => $nc,
                'cuota' => $miaux,
                'deuda' => 0,
                'pagado' => 0,
                'prestamo_id' => $request->prestamo_id,
                'observacion' => null,
                'pasarela_id' => null,
                'fecha' =>Carbon::parse($nuevomes)->format('Y-m-d')
            ]);
            $miplazo=$miplazo+1;
            $miprestamo->plazo=$miplazo;
            $miprestamo->save();
            break;
        }
        
        //registro secuencial
        $nuevomes=date("Y-m-d",strtotime($nuevomes."+ 1 month"));
        $ni=0; $nc=0; $n_deuda=0; $mimonto=$miaux;
        if($request->clase == 'Fijo'){
            $ni=$mitipo->monto_interes * $miprestamo->monto; 
            $nc=$miprestamo->cuota - ($mitipo->monto_interes * $miprestamo->monto);
            $miaux=$mimonto - ($miprestamo->cuota - ($mimonto * $mitipo->monto_interes));
            $n_deuda=$miaux;
        }else if($request->clase == 'Variable'){
            $ni=$mitipo->monto_interes * $mimonto;
            $nc=$miprestamo->cuota - ($mimonto * $mitipo->monto_interes);
            $miaux=$mimonto - ($miprestamo->cuota - ($mimonto * $mitipo->monto_interes));
            $n_deuda=$miaux;  
        }
        App\PrestamoPlane::create([
            'mes' => date("F-Y", strtotime($nuevomes)),
            'nro' => $miplazo+1,
            'monto' => $mimonto,
            'interes' => $ni,
            'capital' => $nc,
            'cuota' => $miprestamo->cuota,
            'deuda' => $n_deuda,
            'pagado' => 0,
            'prestamo_id' => $request->prestamo_id,
            'observacion' => null,
            'pasarela_id' => null,
            'fecha' =>Carbon::parse($nuevomes)->format('Y-m-d')
        ]);
        $miplazo=$miplazo + 1;
        $miprestamo->plazo=$miplazo;
        $miprestamo->save();
        
        //controlar el ultimo
        $milultimo = ($miaux<=$miprestamo->cuota) ? true : false;
    }
    return $request;
});
Route::post('plan/amort', function (Request $request) {
    $miplan=App\PrestamoPlane::find($request->plan_id-1);
    $miprestamo=App\Prestamo::find($request->prestamo_id);
    $mitipo=App\PrestamoTipo::find($miprestamo->tipo_id);

    $miplan->observacion=$miplan->observacion."\n".$request->pc_detalle;
    $miplan->pagado=4; //cat mora
    $miplan->amort=$request->pago_capital;
    $miplan->deuda=$request->nueva_deuda;
    $miplan->save();

    $miupdate=App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
    $miaux=0;
    $milultimo=false;
    $midelete=false;
    foreach ($miupdate as $index => $item) {
        $miitem=App\PrestamoPlane::find($item->id);
        $miitem->monto=($index==0) ? $request->nueva_deuda : $miaux;
        if($request->clase == 'Fijo'){
            // $miitem->intere | interes no se modifica
            $miitem->capital=$miprestamo->cuota - $miitem->interes;
            $miaux=$miitem->monto - ($miprestamo->cuota - $miitem->interes);
            $miitem->deuda=$miaux;  
        }else if($request->clase == 'Variable'){
            $miitem->interes=$miitem->monto * $mitipo->monto_interes;
            $miitem->capital=$miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes);
            $miaux=$miitem->monto - ($miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes));
            $miitem->deuda=$miaux;  
        }         
        $miitem->save();

        if ($midelete) {
            $miprestamo->plazo=$miprestamo->plazo - 1;
            $miprestamo->save();
            App\PrestamoPlane::find($item->id)->delete();
        }
        if ($milultimo) {
            if($request->clase == 'Fijo'){
                $miitem->capital=$miitem->monto - $miitem->interes;
            }else if($request->clase == 'Variable'){
                $miitem->interes=$miitem->monto * $mitipo->monto_interes;
                $miitem->capital=$miitem->monto - ($miitem->monto * $mitipo->monto_interes);
            }
            $miitem->cuota=$miitem->monto;
            $miitem->deuda=0;
            $miitem->save();
            $miaux=$miprestamo->monto;
            $midelete = true;
        }
        $milultimo=($miaux<=$item->cuota) ? true : false;
    }
    return $request;
});
// Route::post('plan/mora/dias', function (Request $request) {
//     // return $request;
//     $midiff = date_diff(date_create($request->fecha), date_create(date("Y-m-d")));
//     $dias_mora = $midiff->format("%a");

//     $interes_mora = 0;
//     $total_mora = $request->cuota;
//     $DiasMes= date('t'); 
//     // return $DiasMes;
//     if ($dias_mora > 0) {
//         if ($request->tipo_id == 1 ) {
//             $interes_mora = ($request->monto * 0.03)/$DiasMes;
//         }else if($request->tipo_id == 2){
//             $interes_mora = ($request->monto * 0.05)/$DiasMes;
//         }                                
//         $total_mora = ($interes_mora*$dias_mora) + $request->cuota;
//         $miseting = setting('prestamos.redondear');
//         if ($miseting == "nor") {
//             $total_mora = number_format($total_mora, 2, '.', '');    
//             $interes_mora = number_format($interes_mora, 2, '.', '');              
//         } else if($miseting == "rmx"){
//             $total_mora = ceil($total_mora);  
//             $interes_mora = ceil($interes_mora);  
//         } else if($miseting == "rmi"){
                
//         }
//     }
//     return response()->json(['dias_mora' => $dias_mora, 'interes_mora' => ($interes_mora*$dias_mora), 'total_mora' => $total_mora]);
// });


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


// clientes -------------------------------------------------------
Route::get('cliente/{id}', function ($id) {
    return App\Cliente::find($id);
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

//Settings --------------------------------------------
Route::get('settings', function () {
    return response()->json(['nombre' => setting('chatbot.nombre'), 'bienvenida' => setting('chatbot.bienvenida')]);
});
Route::get('settings', function () {
    $mititle =  setting('chatbot.nombre');
    return response()->json(['title' => $mititle]);
});

//servicios --------------------------------------------
Route::get('servicios', function () {
    return App\PrestamoTipo::all();
});