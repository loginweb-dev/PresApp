@extends('voyager::master')

@php
    $miplan = App\PrestamoPlane::where("prestamo_id", $dataTypeContent->getKey())->with("pasarelas")->get();
    //prestamo
    $miplan2 = App\Prestamo::where("id", $dataTypeContent->getKey())->with("tipo")->first();
    $pasarelas = App\Pasarela::all();
    $dias_mora = 0;
    $mimora = [];
    //pago actual
    $miplan3 = App\PrestamoPlane::where("prestamo_id", $dataTypeContent->getKey())->where("pagado", 0)->first();
    //cliente
    $micliente = App\Cliente::find($miplan2->cliente_id);
    $miestados = App\PrestamoEstado::all();
@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="">
        <i class="{{ $dataType->icon }}"></i> 
        Kardex del prestamo
        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan
        {{-- @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan --}}
        @can('browse', $dataTypeContent)
            <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#modal_mora" id="btn_mora">
                <i class="icon voyager-refresh"></i> <span class="hidden-xs hidden-sm">Pago con mora</span>
            </a>
            <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#modal_refinanciar" id="btn_amort">
                <i class="icon voyager-heart"></i> <span class="hidden-xs hidden-sm">Refinanciar</span>
            </a>
            <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#modal_amortizacion" id="btn_refin">
                <i class="icon voyager-lightbulb"></i> <span class="hidden-xs hidden-sm">Amortizacion</span>
            </a>


        @endcan
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-sm-3">
                @foreach($dataType->readRows as $row)
                    @php
                    if ($dataTypeContent->{$row->field.'_read'}) {
                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                    }
                    @endphp
                    {{-- <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ $row->getTranslatedAttribute('display_name') }}</h3>
                    </div> --}}

                    <small class="">{{ $row->getTranslatedAttribute('display_name') }}: </small>
                    <div class="panel-body" style="padding-top:0; font-weight: bold;">
                        @if (isset($row->details->view_read))
                            @include($row->details->view_read, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'read', 'options' => $row->details])
                        @elseif (isset($row->details->view))
                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details])
                        @elseif($row->type == "image")
                            <img class="img-responsive"
                                    src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}">
                        @elseif($row->type == 'multiple_images')
                            @if(json_decode($dataTypeContent->{$row->field}))
                                @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                    <img class="img-responsive"
                                            src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file) }}">
                                @endforeach
                            @else
                                <img class="img-responsive"
                                        src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}">
                            @endif
                        @elseif($row->type == 'relationship')
                                @include('voyager::formfields.relationship', ['view' => 'read', 'options' => $row->details])
                        @elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options') &&
                                !empty($row->details->options->{$dataTypeContent->{$row->field}})
                        )
                            <?php echo $row->details->options->{$dataTypeContent->{$row->field}};?>
                        @elseif($row->type == 'select_multiple')
                            @if(property_exists($row->details, 'relationship'))

                                @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                    {{ $item->{$row->field}  }}
                                @endforeach

                            @elseif(property_exists($row->details, 'options'))
                                @if (!empty(json_decode($dataTypeContent->{$row->field})))
                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                        @if (@$row->details->options->{$item})
                                            {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('voyager::generic.none') }}
                                @endif
                            @endif
                        @elseif($row->type == 'date' || $row->type == 'timestamp')
                            @if ( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
                                {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }}
                            @else
                                {{ $dataTypeContent->{$row->field} }}
                            @endif
                        @elseif($row->type == 'checkbox')
                            @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                @if($dataTypeContent->{$row->field})
                                <span class="label label-info">{{ $row->details->on }}</span>
                                @else
                                <span class="label label-primary">{{ $row->details->off }}</span>
                                @endif
                            @else
                            {{ $dataTypeContent->{$row->field} }}
                            @endif
                        @elseif($row->type == 'color')
                            <span class="badge badge-lg" style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
                        @elseif($row->type == 'coordinates')
                            @include('voyager::partials.coordinates')
                        @elseif($row->type == 'rich_text_box')
                            @include('voyager::multilingual.input-hidden-bread-read')
                            {!! $dataTypeContent->{$row->field} !!}
                        @elseif($row->type == 'file')
                            @if(json_decode($dataTypeContent->{$row->field}))
                                @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                    <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}">
                                        {{ $file->original_name ?: '' }}
                                    </a>
                                    <br/>
                                @endforeach
                            @elseif($dataTypeContent->{$row->field})
                                    {{-- {{ $dataTypeContent->{$row->field} }} --}}
                                {{-- <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($row->field) ?: '' }}">
                                    Ver archivo
                                </a> --}}
                                <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($dataTypeContent->{$row->field}) ?: '' }}">
                                    Ver archivo
                                </a>
                            @endif
                        @else
                            @include('voyager::multilingual.input-hidden-bread-read')
                            <p>{{ $dataTypeContent->{$row->field} }}</p>
                        @endif
                    </div><!-- panel-body -->
                    @if(!$loop->last)
                        <hr style="margin:0;">
                    @endif
                @endforeach
                <a href="#" class="btn btn-dark btn-block" data-toggle="modal" data-target="#modal_actualizar" id="btn_actua" onclick="btn_actualizar()">
                    <i class="icon voyager-pen"></i> Actualizar
                </a>
            </div>

            <div class="col-sm-9">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>NO/ID</th>
                                <th>FECHA</th>
                                <th></th>            
                                <th>ESTADO</th>                             
                                <th>MONTO</th>
                                <th>INTERES</th>
                                <th>CAPITAL</th>
                                <th>CUOTA</th>
                                <th>DEUDA</th>
                                <th><i class="icon voyager-refresh"></i></th>
                                <th><i class="icon voyager-heart"></th>
                                <th><i class="icon voyager-lightbulb"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($miplan as $item)
                                <tr>
                                    <td class="text-center">                                                
                                        @if (date("Y-m-d") > $item->fecha && $item->pagado==0)
                                            @php
                                                $midiff = date_diff(date_create($item->fecha), date_create(date("Y-m-d")));
                                            $dias_mora = $midiff->format("%a")+1;                        
                                            @endphp
                                            <span class="badge badge-pill badge-primary">
                                                {{ $item->id }} en mora
                                                <br>
                                                {{ $dias_mora }}
                                            </span>
                                        @else
                                            NR:{{ $item->nro }} <br>                                               
                                            ID:{{ $item->id }}                                                    
                                        @endif                                                             
                                    </td>
                                    <td class="text-center">
                                        {{ $item->mes }}
                                        <br>
                                        {{ $item->fecha }}
                                    </td>
                                    <td class="text-center">
                                        @if ($item->pagado)
                                            <a href="#" class="btn btn-sm btn-dark" onclick="recibo('{{ $item->id }}')">
                                                <span>Recibo</span>
                                            </a>
                                        @else
                                            @if (date("Y-m-d") >= $item->fecha)
                                                <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal_pagar" >
                                                    <span>Pagar</span>
                                                </a>
                                            @endif                                                    
                                        @endif                                              
                                    </td>
                                    <td class="text-center">
                                        @if ($item->pagado == 0)
                                            <h2 class="text-center"><i class="icon voyager-x"></i><h2>  
                                        @elseif($item->pagado == 1)
                                            <h2 class="text-center"><i class="icon voyager-thumbs-up"></i></h2>
                                        @elseif($item->pagado == 2)
                                            <h2 class="text-center"><i class="icon voyager-refresh"></i></h2>
                                        @elseif($item->pagado == 3)
                                            <h2 class="text-center"><i class="icon voyager-heart"></i></h2>
                                        
                                        @elseif($item->pagado == 4)
                                            <h2 class="text-center"><i class="icon voyager-lightbulb"></i></h2>
                                        @endif
                                    </td>                                                                                                                    
                                    <td>{{ number_format($item->monto, 2, '.', '') }}</td>
                                    <td>{{ number_format($item->interes, 2, '.', '') }}</td>
                                    <td>{{ number_format($item->capital, 2, '.', '') }}</td>
                                    <td>{{ number_format($item->cuota, 2, '.', '') }}</td>
                                    <td>{{ number_format($item->deuda, 2, '.', '') }}</td>                      
                                    <td class="text-center">{{ $item->mora }}</td>
                                    <td class="text-center">{{ $item->refin }}</td>
                                    <td class="text-center">{{ $item->amort }}</td>
                                </tr>
                            @endforeach
                        </tbody>                               
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($miplan2->estado_id != 4)
        {{-- Eliminar --}}
        <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-danger pull-right delete-confirm"
                                value="{{ __('voyager::generic.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        {{-- pago normal --}}
        <div class="modal modal-primary fade" tabindex="-1" id="modal_pagar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-plus"></i> Nuevo Pago #{{ $miplan3->nro." - ".$miplan2->tipo->nombre." - ".$miplan3->fecha }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">      
                            <div class="form-group col-xs-4">
                                <label for="">Deuda actual</label>                            
                                <input type="number" name="" id="" class="form-control" value="{{ $miplan3->monto }}" readonly>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="">Interes del mes</label>
                                <input type="number" name="" id="" class="form-control" value="{{ $miplan3->interes }}" readonly>
                            </div>
                            <div class="form-group col-xs-4">
                                <label for="">Ingreso a capital</label>
                                <input type="number" name="" id="" class="form-control" value="{{ $miplan3->capital }}" readonly>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="">Cuota</label>                            
                                <input type="number" name="" id="mcuota" class="form-control" value="{{ $miplan3->cuota }}" readonly>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="">Pasarela</label>
                                <select name="" id="pasarela_id" class="form-control">
                                    @foreach ($pasarelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-4">
                                <label for="">Fecha</label>
                                <input type="date" name="" id="fecha_pago" class="form-control" value="{{ date("Y-m-d") }}">
                            </div>
                        
                            <div class="form-group col-xs-12">
                                <label for="">Observaciones</label>
                                <textarea name="" id="mobserv" class="form-control">Pago normal en fecha: {{ date('Y-m-d') }}</textarea>
                            </div>
                            <input type="hidden" name="" id="plan_id" class="form-control" hidden>
                        </div>                          
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-dark pull-right" onclick="mipago()">
                            <i class="icon voyager-pen"></i>Pagar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- pago con mora  --}}
        <div class="modal modal-primary fade" tabindex="-1" id="modal_mora" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-helm"></i> Pago con mora #{{ $miplan3->nro." - ".$miplan2->tipo->nombre." - ".$miplan3->fecha." - ".$miplan2->plazo }}</h4>
                    </div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#opt1" aria-controls="opt1" role="tab" data-toggle="tab">Opcion Mensual</a></li>
                        <li role="presentation"><a href="#opt2" aria-controls="opt2" role="tab" data-toggle="tab">Opcion Diaria</a></li>
                    </ul>
                    <div class="modal-body">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="opt1">                            
                                    <div class="row">                                
                                        <div class="form-group col-xs-4">
                                            <label for="">Deuda actual</label>                            
                                            <input type="number" name="" id="" value="{{ number_format($miplan3->monto, 2, '.', '') }}" class="form-control" readonly>
                                        </div>
                                        
                                        <div class="col-sm-4 form-group">
                                            <label for="">Interes del mes</label>
                                            <input type="number" class="form-control" id="" value="{{ number_format($miplan3->interes, 2, '.', '') }}" readonly>
                                        </div>
                
                                        <div class="col-sm-4 form-group">
                                            <label for="">Ingreso de capital</label>
                                            <input type="number" class="form-control" id="" value="{{ number_format($miplan3->capital, 2, '.', '') }}" readonly>
                                        </div>                                
                
                                        <div class="col-sm-4 form-group">
                                            <label for="">Pago parcial</label>
                                            <input type="number" class="form-control" id="mora_pago" value="{{ $miplan3->cuota }}">
                                        </div>
                
                                        <div class="col-sm-4 form-group">
                                            <div style="margin-top: 20px;">                            
                                                <a href="#" class="btn btn-warning" onclick="btn_mora()">Re-calcular deuda</a>
                                            </div>
                                        </div>
                                    
                                        <div class="col-sm-4 form-group">
                                            <label for="">Nueva deuda</label>
                                            <input type="text" class="form-control" id="mora_deuda" value="{{ $miplan3->deuda }}" readonly>
                                        </div>
                
                                        <div class="form-group col-xs-6">
                                            <label for="">Pasarela</label>
                                            <select name="" id="mora_pasarela" class="form-control">
                                                @foreach ($pasarelas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                
                                        <div class="form-group col-xs-6">
                                            <label for="">Fecha de pago</label>
                                            <input type="date" name="" id="mora_fecha" class="form-control" value="{{ date("Y-m-d") }}">
                                        </div>
                                                    
                                        <div class="col-sm-12 form-group">
                                            <label for="">Detalle</label>
                                            <textarea name="" id="mora_detalle"  class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <a href="#" onclick="mipago_mora()" class="btn btn-dark pull-right">
                                        Enviar y guardar
                                    </a>                        
                            </div>
                            <div role="tabpanel" class="tab-pane" id="opt2">   
                                <div class="row">
                                                            
                                    <div class="form-group col-xs-3">
                                        <label for="">Dias en mora</label>
                                        <input type="number" name="" id="mora_dias" class="form-control" value="{{ $dias_mora }}" readonly>
                                    </div>

                                    <div class="col-xs-3 form-group">
                                        <div style="margin-top: 20px;">                            
                                            <a href="#" class="btn btn-warning" onclick="">Re-calcular</a>
                                        </div>
                                    </div>

                                    <div class="form-group col-xs-3">
                                        <label for="">Interes por dia</label>
                                        <input type="number" name="" id="mora_interes" class="form-control" value="0" readonly>
                                    </div>

                                    <div class="form-group col-xs-3">
                                        <label for="">Monto total</label>
                                        <input type="number" name="" id="total_mora" class="form-control" value="0">
                                    </div>
                                </div>    
                                <a href="#" onclick="mipago_mora_dias()" class="btn btn-dark pull-right">
                                    </i> Enviar y guardar
                                </a>
                            </div>
                        </div>
                    </div>
        
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        {{-- amortizar a capital  --}}
        <div class="modal modal-primary fade" tabindex="-1" id="modal_amortizacion" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-helm"></i>Pago a capital - Deuda actual: {{ $miplan3->monto }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">                

                            <div class="col-sm-4 form-group">
                                <label for="">Pago a capital</label>
                                <input type="number" class="form-control" id="pc_nmonto" value="0">
                            </div>

                            <div class="col-sm-4 form-group">
                                <div style="margin-top: 20px;">                            
                                    <a href="#" class="btn btn-warning" onclick="btn_amort()">Re-calcular deuda</a>
                                </div>
                            </div>    

                            <div class="col-sm-4 form-group">
                                <label for="">Nueva deuda</label>
                                <input type="text" class="form-control" id="pc_ndeuda" value="0" readonly>
                            </div>        

                            <div class="col-sm-12 form-group">
                                <label for="">Detalle</label>
                                <textarea name="" id="pc_detalle"  class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-dark pull-right" onclick="pago_capital()">
                            <i class="icon voyager-pen"></i> Amortizar a capital
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- refinanciar  --}}
        <div class="modal modal-primary fade" tabindex="-1" id="modal_refinanciar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-helm"></i> Refinanziar prestamo #{{ $miplan3->nro." - ".$miplan2->tipo->nombre." - ".$miplan3->fecha }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label for="">Deuda actual</label>
                                <input type="number" class="form-control" value="{{ $miplan3->monto }}" readonly>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="">Interes del mes</label>
                                <input type="number" class="form-control" value="{{ $miplan3->interes }}" readonly>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="">Ingreso a capital</label>
                                <input type="number" class="form-control" value="{{ $miplan3->capital }}" readonly>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="">Nuevo monto</label>
                                <input type="number" class="form-control" id="ref_nuevo_monto" value="0">
                            </div>
                            <div class="col-sm-4 form-group">
                                <div style="margin-top: 20px;">                            
                                    <a href="#" class="btn btn-warning" onclick="btnplan()">Re-calcular deuda</a>
                                </div>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="">Nueva deuda</label>
                                <input type="number" class="form-control" id="ref_nueva_deuda" value="0">
                            </div>

                            <div class="col-sm-12 form-group">
                                <label for="">Observaciones</label>
                                <textarea name="" id="ref_detalle" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-dark pull-right" onclick="refinanciar()">
                            <i class="icon voyager-pen"></i> Refinanciar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actualizar  --}}
        <div class="modal modal-primary fade" tabindex="-1" id="modal_actualizar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-helm"></i> Actualizar prestamo #{{ $miplan2->id }}</h4>
                    </div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class=""><a href="#opt11" aria-controls="opt11" role="tab" data-toggle="tab">Actualizar prestamo</a></li>
                        <li role="presentation" class="active"><a href="#opt22" aria-controls="opt22" role="tab" data-toggle="tab">Finalizar deuda</a></li>
                    </ul>
                    <div class="modal-body">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane" id="opt11">                            
                                    <div class="row">                                
                                        <div class="form-group col-xs-6">
                                            <label for="">Plazo</label>
                                            <input type="number" name="" id="act_plazo" class="form-control" value="{{ $miplan2->plazo }}">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Monto</label>
                                            <input type="number" name="" id="act_monto" class="form-control" value="{{ $miplan2->monto }}">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Cuota</label>
                                            <input type="number" name="" id="act_cuota" class="form-control" value="{{ $miplan2->cuota }}">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Clase</label>
                                            <select name="" id="act_clase" class="form-control">
                                                <option value="Fijo" @if($miplan2->clase=='Fijo') selected @endif>Fijo</option>
                                                <option value="Variable" @if($miplan2->clase=='Variable') selected @endif>Variable</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Detalle</label>
                                            <textarea name="" id="act_detalle" class="form-control">{{ $miplan2->observacion }}</textarea>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Estado</label>
                                            <select name="" id="act_estado_id" class="form-control">
                                            @foreach ($miestados as $item)
                                                <option value="{{ $item->id }}" @if($miplan2->estado_id==$item->id) selected @endif>{{ $item->nombre }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <a href="#" onclick="actualizar_prestamo()" class="btn btn-dark pull-right">
                                        Enviar y guardar
                                    </a>                        
                            </div>
                            <div role="tabpanel" class="tab-pane active" id="opt22">   
                                <div class="row">                                                     
                                            
                                    <div class="form-group col-xs-4">
                                        <label for="">Dias en mora</label>
                                        <input type="number" name="" id="fd_diasmora" class="form-control" value="0" readonly>
                                    </div>
                                    <div class="form-group col-xs-4">
                                        <label for="">Interes por dia</label>
                                        <input type="number" name="" id="fd_pordiainteres" class="form-control" value="0" readonly>
                                    </div>
                                    
                                    <div class="form-group col-xs-4">
                                        <label for="">Monto total</label>
                                        <input type="number" name="" id="fd_pordiatotal" class="form-control" value="0" readonly>
                                    </div>      
                                                                
                                    <div class="form-group col-xs-4">
                                        <label for="">Deuda actual</label>
                                        <input type="number" name="" id="fd_deuda" class="form-control" value="{{ $miplan3->monto }}" readonly>
                                    </div>
                                    <div class="form-group col-xs-4">
                                        <label for="">Fecha pago</label>
                                        <input type="date" name="" id="fd_fecha" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group col-xs-4">
                                        <label for="">Importe</label>
                                        <input type="number" name="" id="fd_importe" class="form-control" value="0">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="">Detalle</label>
                                        <textarea name="" id="fd_detalle" class="form-control"></textarea>
                                    </div>
                            
                                </div>    
                                <a href="#" onclick="finalizar_prestamo()" class="btn btn-dark pull-right">
                                    </i> Enviar y guardar
                                </a>
                            </div>
                        </div>
                    </div>
            
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- recibo  --}}
    <div class="modal modal-primary fade" tabindex="-1" id="modal_recibo" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-helm"></i> Recibo #{{ $miplan2->id." ".$miplan2->tipo->nombre." Plazo:".$miplan2->plazo }}</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-4 form-group">
                            <label for="">Fecha de pago</label>
                            <input type="text" class="form-control" id="recibo_fecha" value="" readonly>
                        </div>
                        <div class="col-xs-4 form-group">
                            <label for="">Pago del mes</label>
                            <input type="number" class="form-control" id="recibo_final" value="" readonly>
                        </div>
                        <div class="col-xs-4 form-group">
                            <label for="">Pasarela</label>
                            <input type="text" class="form-control" id="recibo_pasarela" value="" readonly>
                        </div>

                        <div class="col-xs-4 form-group">
                            <label for="">Editor</label>
                            <input type="text" class="form-control" id="recibo_editor" value="" readonly>
                        </div>

                        <div class="col-xs-4 form-group">
                            <label for="">Cliente</label>
                            <input type="text" class="form-control" value="{{ $micliente->nombre_completo }}" readonly>
                        </div>
                        <div class="col-xs-4 form-group">
                            <label for="">Whatsapp</label>
                            <input type="text" class="form-control" id="recibo_whatsapp" value="{{ $micliente->telefono }}" readonly>
                        </div>
                        
                        <div class="col-xs-12 form-group">
                            <label for="">Mensaje</label>
                            <textarea name="" id="recibo_detalle" rows="6" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark pull-right" onclick="whatsapp()">
                        <i class="icon voyager-pen"></i> Enviar por whatsapp
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        const llenarTabla = document.querySelector('#lista-tabla tbody');
        localStorage.removeItem("miplan")
        var eprest = "invalido"
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });
        
        @if ($miplan2->estado_id != 4)
        
            // pago normal-------------------------------------------------------------------------------
            async function mipago(){      
                // if(!$("#fecha_pago").val()){
                //     swal({
                //         icon: "error",
                //         title: "Ingresa la fecha de pago"
                //     })
                //     return true;
                // }      
                $('#modal_pagar').modal('hide');
                $("#dias_mora").val()
                swal({
                    icon: "info",
                    title:  "Esta segur@ de realizar el pago #{{ $miplan3->id }}",                
                    buttons: {
                        cancel: "Cancelar",
                        confir: "Confirmar",
                    },
                    }).then(async (value) => {
                        switch (value) {
                            case "cancel":
                                console.log("cerrar")
                                $('#modal_pagar').modal('hide');
                                toastr.error("Pago cancelado...")
                            break;
                            case "confir":
                                toastr.info("Enviando datos, espere por favor")  
                                var mipago = await axios.post("/api/plan/update", {
                                    id: "{{ $miplan3->id }}",
                                    fecha_pago: $('#fecha_pago').val(),
                                    pasarela_id: $('#pasarela_id').val(),
                                    observacion: $('#mobserv').val(),
                                    user_id: {{ Auth::user()->id }},
                                    p_final: {{ $miplan3->cuota }}
                                })
                                // console.log(mipago.data)
                                location.reload()
                            break;
                        }
                    }
                );            
            }

            //pago con mora -----------------------------------------------------------------------------
            async function btn_mora() {
                toastr.info("calculando..")
                var nueva_deuda = 0
                var nueva_interes = 0
                var nueva_capital = 0
                var miresta = $("#mora_pago").val() - {{ $miplan3->interes }}
                if($("#mora_pago").val() == {{ $miplan3->interes }}) {
                    nueva_deuda = {{ $miplan3->monto }}
                } else if($("#mora_pago").val() < {{ $miplan3->interes }} ){
                    nueva_interes = {{ $miplan3->interes }} - $("#mora_pago").val()
                    nueva_deuda ={{ $miplan3->monto }}  + nueva_interes                
                }else{
                    nueva_interes =  $("#mora_pago").val() - {{ $miplan3->interes }}
                    nueva_capital = {{ $miplan3->capital }} - nueva_interes
                    nueva_deuda ={{ $miplan3->monto }} - miresta    
                }
                $("#mora_deuda").val(nueva_deuda.toFixed(2))
                $("#mora_detalle").val("Pago con mora en fecha: "+$("#mora_fecha").val()+" y un pago de: "+$("#mora_pago").val())

                //     var mora_update =  await axios.post("/api/plan/mora/dias", {
                //         fecha: "{{ $miplan3->fecha }}",
                //         tipo_id: {{ $miplan2->tipo_id }},
                //         interes_mes: {{ $miplan3->interes }}
                //     })
                //     console.log(mora_update.data.dias_mora)
                //     $("#mora_dias").val(mora_update.data.dias_mora)
                //     $("#mora_interes").val(mora_update.data.interes_mora)
                //     $("#total_mora").val(mora_update.data.total_mora)
        
            }
            function mipago_mora(){
                $('#modal_mora').modal('hide')
                if(!$("#mora_fecha").val()){
                    swal({
                        icon: "error",
                        title: "Ingresa la fecha de pago"
                    })
                    return true;
                }    
                if(!$("#mora_detalle").val()){
                    swal({
                        icon: "error",
                        title: "Ingresar el detalle"
                    })
                    return true;
                } 
                
                swal({
                    icon: "info",
                    title:  "Esta segur@ de realizar el pago con mora ?",                
                    buttons: {
                        cancel: "Cancelar",
                        confir: "Confirmar",
                    },
                    }).then(async (value) => {
                        switch (value) {
                            case "cancel":
                                console.log("cerrar")
                                $('#modal_pagar').modal('hide');
                            break;
                            case "confir":
                                toastr.info("Enviando datos, espere por favor")  
                                var midata = await axios.post("/api/plan/mora", {
                                    plan_id: {{ $miplan3->id }},
                                    nueva_deuda: $("#mora_deuda").val(),
                                    pago_parcial: $("#mora_pago").val(),
                                    mora_detalle: $("#mora_detalle").val(),
                                    mora_pasarela: $("#mora_pasarela").val(),
                                    mora_fecha: $("#mora_fecha").val(),
                                    user_id: {{ Auth::user()->id }},
                                    plazo: {{ $miplan2->plazo }},
                                    prestamo_id: {{ $miplan2->id }},
                                    tipo_id: {{ $miplan2->tipo_id }},
                                    clase: "{{ $miplan2->clase }}",
                                    cuota: {{ $miplan2->cuota }}
                                })
                                console.log(midata.data)
                                location.reload()
                            break;
                        }
                    }
                );    
            }     

            //pago a capital amortizacion ------------------------------------------------------------------------
            function btn_amort() {
                toastr.info("Calculando..")
                var mindeuda = parseFloat({{ $miplan3->monto }}) - parseFloat($("#pc_nmonto").val())
                $("#pc_ndeuda").val(mindeuda)
                $("#pc_detalle").val("Amortización en fecha: {{ date('Y-m-d') }}, y un monto de: "+$("#pc_nmonto").val())

            }
            async function pago_capital(){
                $('#modal_amortizacion').modal('hide')
                swal({
                    icon: "warning",
                    title:  "Esta segur@ de realizar la transaccion ?",                
                    buttons: {
                        cancel: "Cancelar",
                        confir: "Confirmar",
                    },
                    }).then(async (value) => {
                        switch (value) {
                            case "cancel":
                                console.log("cancel")
                            break;
                            case "confir":    
                                toastr.info("Enviando datos, espere por favor")                        
                                var midata = await axios.post("/api/plan/amort", {
                                    prestamo_id: {{ $miplan2->id }},
                                    nro: {{ $miplan3->nro }},
                                    plan_id: {{ $miplan3->id }},
                                    pago_capital: $("#pc_nmonto").val(),
                                    nueva_deuda: $("#pc_ndeuda").val(),
                                    user_id: {{ Auth::user()->id }},     
                                    tipo_id: {{ $miplan2->tipo_id }},
                                    pc_detalle: $("#pc_detalle").val(),
                                    clase: "{{ $miplan2->clase }}"
                                })
                                console.log(midata.data)
                                location.reload()
                            break;
                        }
                    }
                );
            }

            //refinanciar --------------------------------------------------------------------------------
            function btnplan() {
                var new_monto = parseFloat($("#ref_nuevo_monto").val())
                var newmonto = {{ $miplan3->monto }} + new_monto
                var monto_actual = parseFloat({{ $miplan2->monto }})
                var monto_minimo = parseFloat({{ $miplan2->tipo->plazo_minimo }})
                if(new_monto > monto_actual){
                    swal({
                        title: "El nuevo monto no tiene que superar el monto inicial",
                        icon: "error",
                        
                    });
                    return true
                }
                if(newmonto > $("#monto_actual").val()){
                    swal({
                        title: "El nuevo monto no tiene que superar el monto inicial",
                        icon: "error",
                    });
                    return true
                }
                $("#ref_nueva_deuda").val(newmonto)
                $("#ref_detalle").val("Re financiar préstamo en fecha: {{ date('Y-m-d') }} y un monto de: "+new_monto)            
                toastr.info("Nueva deuda: "+newmonto)
            }
            async function refinanciar(){
                $('#modal_refinanciar').modal('hide')
                swal({
                    icon: "warning",
                    title:  "Esta segur@ de refinanziar el prestamo ?",                
                    buttons: {
                        cancel: "Cancelar",
                        confir: "Confirmar",
                    },
                    }).then(async (value) => {
                        switch (value) {
                            case "cancel":
                                console.log("cancel")
                            break;
                            case "confir":    
                                toastr.info("Enviando datos, espere por favor")                          
                                var midata = await axios.post("/api/plan/refin", {
                                    prestamo_id: {{ $miplan2->id }},
                                    plan_id: {{ $miplan3->id }},
                                    nro: {{ $miplan3->nro }},
                                    ref_nuevo_monto: $("#ref_nuevo_monto").val(),
                                    ref_nueva_deuda: $("#ref_nueva_deuda").val(),
                                    user_id: {{ Auth::user()->id }},    
                                    tipo_id: {{ $miplan2->tipo_id }},
                                    ref_detalle: $("#ref_detalle").val(),
                                    clase: "{{ $miplan2->clase }}",
                                    plazo: {{ $miplan2->plazo }}
                                })
                                // console.log(midata.data)
                                location.reload()
                            break;
                        }
                    }
                );
            }

            //actualizar-----------------------------------
            async function btn_actualizar() {
                // $('#modal_actualizar').modal();
                var midata = await axios.post("/api/plan/mora/dias", {
                    tipo_id: {{ $miplan2->tipo_id }},
                    clase: "{{ $miplan2->clase }}",
                    prestamo_id: {{ $miplan2->id }},
                    fecha: "{{ $miplan3->fecha }}"
                })
                console.log(midata.data)
                $("#fd_diasmora").val(midata.data.dias_mora)
                $("#fd_pordiainteres").val(midata.data.interes_mora)
                $("#fd_pordiatotal").val(midata.data.total_mora)
                var miimpot = midata.data.total_mora + {{ $miplan3->monto }}
                $("#fd_importe").val(miimpot)
                $("#fd_detalle").val("Deuda cancela, con un monto de: "+miimpot)
                

                
            }
            async function actualizar_prestamo() {
                toastr.info("mensaje enviado...")
                $('#modal_actualizar').modal('hide');
                await axios.post("/api/prestamo/actualizar", {
                    id: {{ $miplan2->id }},
                    plazo: $("#act_plazo").val(),
                    monto: $("#act_monto").val(),
                    cuota: $("#act_cuota").val(),
                    clase: $("#act_clase").val(),
                    estado_id: $("#act_estado_id").val(),
                    detalle: $("#act_detalle").val()
                })
                location.reload()
            }
            async function finalizar_prestamo() {
                $('#modal_actualizar').modal('hide')
                swal({
                    icon: "warning",
                    title:  "Esta segur@ de finalizar el prestamo ?",                
                    buttons: {
                        cancel: "Cancelar",
                        confir: "Confirmar",
                    },
                    }).then(async (value) => {
                        var midata = await axios.post("/api/prestamo/finalizar", {
                            tipo_id: {{ $miplan2->tipo_id }},
                            clase: "{{ $miplan2->clase }}",
                            prestamo_id: {{ $miplan2->id }},
                            fecha: $("#fd_fecha").val(),
                            importe: $("#fd_importe").val(),
                            detalle: $("#fd_detalle").val(),
                            nro: {{ $miplan3->nro }}

                        })
                        console.log(midata.data)
                    })
            }
        @endif

        // recibo ------------------------------------------------------------------------------
        async function recibo(id){
            $('#modal_recibo').modal('show');
            var mipago = await axios("/api/plan/"+id)
            $("#recibo_fecha").val(mipago.data.fecha_pago)
            $("#recibo_final").val(mipago.data.p_final)
            $("#recibo_pasarela").val(mipago.data.pasarelas.nombre)
            $("#recibo_detalle").val(mipago.data.observacion)
            $("#recibo_editor").val(mipago.data.user.name)            
        }

        // whatsapp-------------------------------------------------------------------------------
        async function whatsapp(){ 
            toastr.info("mensaje enviado...")
            $('#modal_recibo').modal('hide');
            var misms = $("#recibo_detalle").val()
            var miwhats = $("#recibo_whatsapp").val()
            var miurl = "{{ env('CB_URL').'/send' }}"
            var midata = {
                phone: miwhats,
                message: misms
            }
            var midata = await axios.post(miurl, midata)            
        }

        //estados----------------------------
        @if($miplan2->estado_id == 4 || $miplan2->estado_id == 5)
            $("#btn_mora").attr("disabled", true)
            $("#btn_mora").prop('disabled', true)

            $("#btn_amort").attr("disabled", true)
            $("#btn_amort").prop("disabled", true)

            $("#btn_refin").attr("disabled", true)
            $("#btn_refin").prop("disabled", true)

            $("#btn_actua").attr("disabled", true)
            $("#btn_actua").prop('disabled', true)
        @endif
       
    </script>
@stop
