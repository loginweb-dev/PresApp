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
    // return $request;
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
    // return $micount;
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
Route::post('prestamo/actualizar', function (Request $request) {
    $miprestamo = App\Prestamo::find($request->id);
    $miprestamo->plazo = $request->plazo;
    $miprestamo->monto = $request->monto;
    $miprestamo->cuota = $request->cuota;
    $miprestamo->clase = $request->clase;
    $miprestamo->estado_id = $request->estado_id;
    $miprestamo->observacion = $request->detalle;
    $miprestamo->save();
    return $miprestamo;
});
Route::post('prestamo/finalizar', function (Request $request) {
    // return $request;
    $delete1=App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->count();
    $miprestamo=App\Prestamo::find($request->prestamo_id);
    $miprestamo->estado_id=4; // completado
    $miprestamo->observacion=$miprestamo->observacion."\n ".$request->detalle."\n Fecha: ".$request->fecha;    
    $miprestamo->nro=$miprestamo->nro-$delete1;
    $miprestamo->save();

    $miplan=App\PrestamoPlane::where("nro", $request->nro-1)->where("prestamo_id", $request->prestamo_id)->first();
    $miplan->observacion=$miplan->observacion."\n ".$request->detalle."\n Fecha: ".$request->fecha;
    $miplan->save();

    App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->delete();

    return $miprestamo;
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
    
    //update plan actual
    $misuma = $miplan->cuota - $request->pago_parcial;
    $miplan->pagado = 2; //cat mora
    $miplan->fecha_pago = $request->mora_fecha;
    $miplan->observacion = $request->mora_detalle;
    $miplan->pasarela_id = $request->mora_pasarela;
    $miplan->user_id = $request->user_id;
    $miplan->deuda = $request->nueva_deuda;
    $miplan->cuota = $request->pago_parcial;
    $miplan->p_final = $request->pago_parcial;
    $miplan->mora = $miplan->mora + $misuma;
    if($request->pago_parcial == $miplan->interes){
        $miplan->capital = 0;
    }elseif($request->pago_parcial < $miplan->interes){
        $miplan->interes = $miplan->interes - $request->pago_parcial;
        $miplan->capital = 0;
    }else{
        $miplan->capital = $request->pago_parcial - $miplan->interes;   
    }
    $miplan->save();

    // actaulizar todas las planes
    $miupdate = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
    $miaux=$request->nueva_deuda; $micount=0; 
    while ($miaux >= $miprestamo->cuota) {
        $miitem = App\PrestamoPlane::find($miupdate[$micount]->id);
        $miitem->monto = $miaux;
        $miitem->cuota = $miprestamo->cuota;
        if($request->clase == 'Fijo'){
            $miitem->interes = $mitipo->monto_interes * $miprestamo->monto;
            $miitem->capital = $miprestamo->cuota - $miitem->interes;
            $miaux = $miitem->monto - ($miprestamo->cuota - $miitem->interes);
            $miitem->deuda = $miaux;  
        }else if($request->clase == 'Variable'){                
            $miitem->interes = $miitem->monto * $mitipo->monto_interes;
            $miitem->capital = $miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes);
            $miaux = $miitem->monto - ($miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes));
            $miitem->deuda = $miaux;  
        }         
        
        $micount=$micount+1;   
        $miitem->save();    
        if($micount==count($miupdate)){
            $nuevomes = date("Y-m-d",strtotime($miitem->fecha."+ 1 month"));
            $nm=$miaux; $ni=0; $nc=0; $nd=0;
            if($request->clase == 'Fijo'){
                $ni=$mitipo->monto_interes * $miprestamo->monto; 
                $nc=$miprestamo->cuota - $ni;
                $nd=$nm - ($miprestamo->cuota - $ni);;
            }else if($request->clase == 'Variable'){
                $ni=$mitipo->monto_interes * $nm; 
                $nc=$miprestamo->cuota - $ni;
                $nd= $nm - ($miprestamo->cuota - $ni);
            }  
            $micuota2=$nm+$ni;
            App\PrestamoPlane::create([
                'mes' => date("F-Y", strtotime($nuevomes)),
                'nro' => ($miitem->nro+1),
                'monto' => $nm,
                'interes' => $ni,
                'capital' => ($micuota2-$ni),
                'cuota' => $micuota2,
                'deuda' => 0,
                'pagado' => 0,
                'prestamo_id' => $miprestamo->id,
                'observacion' => null,
                'pasarela_id' => null,
                'fecha' =>Carbon::parse($nuevomes)->format('Y-m-d')
            ]);
            $miprestamo->plazo = $miprestamo->plazo + 1;
            $miprestamo->save();
            $miupdate = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
        }      
    }
    return true;
});
Route::post('plan/refin', function (Request $request) {

    $miplan=App\PrestamoPlane::where("nro", $request->nro-1)->where("prestamo_id", $request->prestamo_id)->first();
    $miprestamo=App\Prestamo::find($request->prestamo_id);
    $mitipo=App\PrestamoTipo::find($miprestamo->tipo_id);

    //actualzar el plan anterior
    $miplan->observacion=$miplan->observacion."\n".$request->ref_detalle;
    $miplan->pagado=3; //cat refin
    $miplan->refin=$miplan->refin+$request->ref_nuevo_monto;
    $miplan->deuda=$request->ref_nueva_deuda;
    $miplan->save();

 // actaulizar todas las planes
    $miupdate = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
    $miaux=$request->ref_nueva_deuda; $micount=0; 
    while ($miaux >= $miprestamo->cuota) {
        $miitem = App\PrestamoPlane::find($miupdate[$micount]->id);
        $miitem->monto = $miaux;
        $miitem->cuota = $miprestamo->cuota;
        if($request->clase == 'Fijo'){
            $miitem->interes = $mitipo->monto_interes * $miprestamo->monto;
            $miitem->capital = $miprestamo->cuota - $miitem->interes;
            $miaux = $miitem->monto - ($miprestamo->cuota - $miitem->interes);
            $miitem->deuda = $miaux;  
        }else if($request->clase == 'Variable'){                
            $miitem->interes = $miitem->monto * $mitipo->monto_interes;
            $miitem->capital = $miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes);
            $miaux = $miitem->monto - ($miprestamo->cuota - ($miitem->monto * $mitipo->monto_interes));
            $miitem->deuda = $miaux;  
        }         
        
        $micount=$micount+1;   
        $miitem->save();    
        if($micount==count($miupdate)){
            $nuevomes = date("Y-m-d",strtotime($miitem->fecha."+ 1 month"));
            $nm=$miaux; $ni=0; $nc=0; $nd=0;
            if($request->clase == 'Fijo'){
                $ni=$mitipo->monto_interes * $miprestamo->monto; 
                $nc=$miprestamo->cuota - $ni;
                $nd=$nm - ($miprestamo->cuota - $ni);;
            }else if($request->clase == 'Variable'){
                $ni=$mitipo->monto_interes * $nm; 
                $nc=$miprestamo->cuota - $ni;
                $nd= $nm - ($miprestamo->cuota - $ni);
            }  
            $micuota2=$nm+$ni;
            App\PrestamoPlane::create([
                'mes' => date("F-Y", strtotime($nuevomes)),
                'nro' => ($miitem->nro+1),
                'monto' => $nm,
                'interes' => $ni,
                'capital' => ($micuota2-$ni),
                'cuota' => $micuota2,
                'deuda' => 0,
                'pagado' => 0,
                'prestamo_id' => $miprestamo->id,
                'observacion' => null,
                'pasarela_id' => null,
                'fecha' =>Carbon::parse($nuevomes)->format('Y-m-d')
            ]);
            $miprestamo->plazo = $miprestamo->plazo + 1;
            $miprestamo->save();
            $miupdate = App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
        }
  
    }

    return true;
});
Route::post('plan/amort', function (Request $request) {
  
    $miplan=App\PrestamoPlane::where("nro", $request->nro-1)->where("prestamo_id", $request->prestamo_id)->first();
    $miprestamo=App\Prestamo::find($request->prestamo_id);
    $mitipo=App\PrestamoTipo::find($miprestamo->tipo_id);

    $miplan->observacion=$miplan->observacion."\n".$request->pc_detalle;
    $miplan->pagado=4; //cat mora
    $miplan->amort=$miplan->amort+$request->pago_capital;
    $miplan->deuda=$request->nueva_deuda;
    $miplan->save();

    $miupdate=App\PrestamoPlane::where("prestamo_id", $request->prestamo_id)->where("pagado", 0)->get();
    $miaux=0;
    $milultimo=false;
    $midelete=false;
    foreach ($miupdate as $index => $item) {
        $miitem=App\PrestamoPlane::find($item->id);
        $miitem->monto=($index==0) ? $request->nueva_deuda : $miaux;

        if (!$milultimo) {
            if($request->clase == 'Fijo'){
                $miitem->interes = $mitipo->monto_interes * $miprestamo->monto;
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

        if ($midelete) {
            $miprestamo->plazo=$miprestamo->plazo - 1;
            $miprestamo->save();
            App\PrestamoPlane::find($item->id)->delete();
        }

        if ($milultimo) {
            if ($miaux < $miprestamo->cuota) {                    
                $miitem2=App\PrestamoPlane::find($item->id);
                $miitem2->monto=$miaux;
                $miitem2->capital=($miaux + $miitem->interes) - $miitem->interes;
                $miitem2->cuota=$miaux + $miitem->interes;                            
                $miitem2->deuda=0;
                $miitem2->save();
            }
            $miaux=$miprestamo->monto;              
            $midelete = true;
        }
        $milultimo=($miaux<=$miprestamo->cuota) ? true : false;
        // $milultimo=($index==$miprestamo->plazo+1) ? true : false;
    }
    return $request;
});
Route::post('plan/mora/dias', function (Request $request) {
    $mitipo = App\PrestamoTipo::find($request->tipo_id);
    $miprestamo=App\Prestamo::find($request->prestamo_id);
    $midiff = date_diff(date_create($request->fecha), date_create(date("Y-m-d")));

    $dias_mora = $midiff->format("%a");   
    $interes_mora = 0;
    $total_mora = 0;
    $DiasMes= date('t'); 
    // return $DiasMes;
        if ($dias_mora > 0) {
            if ($request->clase == "Fijo" ) {
                $interes_mora = ($miprestamo->monto * $mitipo->monto_interes)/$DiasMes;
            }else if($request->clase == "Variable"){
                $interes_mora = ($miprestamo->monto * $mitipo->monto_interes)/$DiasMes;
            }                                
            $total_mora = $interes_mora * $dias_mora;
            $miseting = setting('prestamos.redondear');
            if ($miseting == "nor") {
                $total_mora = number_format($total_mora, 2, '.', '');    
                $interes_mora = number_format($interes_mora, 2, '.', '');              
            } else if($miseting == "rmx"){               
                $total_mora = round($total_mora, 0, PHP_ROUND_HALF_UP);   
                $interes_mora = round($interes_mora, 0, PHP_ROUND_HALF_UP);    
            } else if($miseting == "rmi"){
                $total_mora = round($total_mora);  
                $interes_mora = round($interes_mora);    
            }
        }

    // $interes_mora = ceil($request->interes_mes / $DiasMes);
    $total_mora = $dias_mora * $interes_mora; 
    return response()->json(['dias_mora' => $dias_mora, 'interes_mora' => $interes_mora, 'total_mora' => $total_mora]);
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
    return menu('whatsapp');
    // return response()->json(['nombre' => setting('chatbot.nombre'), 'bienvenida' => setting('chatbot.bienvenida')]);
});
Route::get('settings', function () {
    $mititle =  setting('chatbot.nombre');
    return response()->json(['title' => $mititle]);
});

//servicios --------------------------------------------
Route::get('servicios', function () {
    return App\PrestamoTipo::all();
});

//servicios --------------------------------------------
Route::get('agentes', function () {
    return App\Models\User::where('role_id', 3)->get();
});

//leads --------------------------------------------
Route::post('leads', function (Request $request) {
    $midata = App\Lead::where("phone", $request->phone)->first();
    if (!$midata) {
        App\Lead::create([
            'phone' => $request->phone,
            'message' => $request->message,
            'categoria' => 'General'
        ]);
    }else{
        $midata->message = $request->message;
        $midata->save();
    }
    return $request;
});