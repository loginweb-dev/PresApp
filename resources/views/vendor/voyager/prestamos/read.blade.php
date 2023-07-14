@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="">
        <i class="{{ $dataType->icon }}"></i> 
        {{-- {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp; --}}
        Kardex del prestamo
        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan
        @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan
        @can('browse', $dataTypeContent)
        {{-- <a href="{{ route('pdf_prestamo', $dataTypeContent->getKey()) }}" class="btn btn-dark">
            <i class="icon voyager-certificate"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
        </a> --}}
        <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#modal_mora">
            <i class="icon voyager-params"></i> <span class="hidden-xs hidden-sm">Pago con mora</span>
        </a>

        <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#modal_refinanciar">
            <i class="icon voyager-helm"></i> <span class="hidden-xs hidden-sm">Refinanciar</span>
        </a>
        <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#modal_amortizacion">
            <i class="icon voyager-person"></i> <span class="hidden-xs hidden-sm">Amortizacion</span>
        </a>
        {{-- <a href="/docs/1.0/pagos" class="btn btn-dark">
            <i class="icon voyager-info-circled"></i> <span class="hidden-xs hidden-sm">Ayuda</span>
        </a> --}}
        @endcan
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-sm-3">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- form start -->
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

                </div>
            </div>

            <div class="col-sm-9">
                @php
                    $miplan = App\PrestamoPlane::where("prestamo_id", $dataTypeContent->getKey())->with("pasarelas")->get();
                    $miplan2 = App\Prestamo::where("id", $dataTypeContent->getKey())->with("tipo")->first();
                    $pasarelas = App\Pasarela::all();
                    $countcsp = 0;
                    $countcnp = 0;
                    $count_mora = 0;
                    $miplan3 = App\PrestamoPlane::where("prestamo_id", $dataTypeContent->getKey())->where("pagado", 0)->first();
                @endphp
                <div class="panel panel-bordered">
                    {{-- <div class="panel-body"> --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th>ACCION</th>
                                        <th>MES</th>
                                        <th>NRO</th>
                                        <th>MONTO</th>
                                        <th>INTERES</th>
                                        <th>CAPITAL</th>
                                        <th>CUOTA</th>
                                        <th>DEUDA</th>
                                        <th>ESTADO</th>                                        
                                        <th>MORA</th>
                                        <th>REFIN</th>
                                        <th>AMORT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($miplan as $item)
                                        <tr>
                                            {{-- <td>{{ $item->id }}</td> --}}
                                            <td>
                                                @if ($item->pagado)
                                                    {{-- @php $countcsp++ @endphp --}}
                                                    <a href="#" class="btn btn-sm btn-dark" onclick="detalle('{{ $item->id }}')"> <span>Detalle</span>
                                                @else
                                                    {{-- @php $countcnp++ @endphp --}}
                                                    <a href="#" class="btn btn-sm btn-warning" onclick="pagar('{{ $item->id }}')"> <span>Pagar</span>
                                                </a>
                                                @endif                                              
                                            </td>
                                            <td>
                                                {{ $item->mes }}
                                                <br>
                                                {{ $item->fecha }}
                                            </td>
                                            <td>                                                
                                                @if (date("Y-m-d") > $item->fecha && !$item->pagado)
                                                    <span class="badge badge-pill badge-primary">{{ $item->nro }} en mora</span>
                                                    @php $count_mora++ @endphp
                                                @else
                                                    {{ $item->nro }} <br>
                                                    #{{ $item->id }}
                                                @endif                     
                                            </td>
                                            <td>{{ number_format($item->monto, 2, '.', '') }}</td>
                                            <td>{{ number_format($item->interes, 2, '.', '') }}</td>
                                            <td>{{ number_format($item->capital, 2, '.', '') }}</td>
                                            <td>{{ number_format($item->cuota, 2, '.', '') }}</td>
                                            <td>{{ number_format($item->deuda, 2, '.', '') }}</td>
                                            <td>
                                                @if ($item->pagado == 0)
                                                    <h2 class="text-center"><i class="icon voyager-x"></i></h2>                                                    
                                                @elseif($item->pagado == 1)
                                                    <h2 class="text-center"><i class="icon voyager-thumbs-up"></i></h2>
                                                @elseif($item->pagado == 2)
                                                    <h2 class="text-center"><i class="icon voyager-refresh"></i></h2>
                                                @elseif($item->pagado == 3)
                                                    <h2 class="text-center"><i class="icon voyager-heart"></i></h2>
                                                @endif
                                            </td>
                        
                                            <td>{{ $item->mora }}</td>
                                            <td>{{ $item->refin }}</td>
                                            <td>{{ $item->amort }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>                               
                            </table>
                            <h4>Totales</h4>
                            <h4>Pagadas:  {{ $countcsp }} | No pagadas: {{ $countcnp }} | En mora: {{ $count_mora }} | Refin: {{ $count_mora }} | Amort: {{ $countcnp }}</h4>
                            @php
                                $miupdate = App\Prestamo::find($dataTypeContent->getKey());
                                if ($count_mora > 0) {
                                    $miupdate->estado_id  = 2; //mora
                                    $miupdate->save();                                             
                                }else if($count_mora === 0){
                                    $miupdate->estado_id  = 1; //activado
                                    $miupdate->save();  
                                }else if($countcsp === $miupdate->plazo){
                                    $miupdate->estado_id  = 4; //completado
                                    $miupdate->save();  
                                }
                            @endphp
                        </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>


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

    {{-- form pago plan --}}
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
                            <input type="number" name="" id="mmonto" class="form-control" readonly>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="">Interes</label>
                            <input type="number" name="" id="minteres" class="form-control" readonly>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="">Capital</label>
                            <input type="number" name="" id="mcapital" class="form-control" readonly>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="">Cuota</label>                            
                            <input type="number" name="" id="mcuota" class="form-control" readonly>
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
                            <input type="date" name="" id="fecha_pago" class="form-control">
                        </div>

                        {{-- <div class="col-sm-4 form-group">
                            <div style="margin-top: 25px;">                            
                                <a href="#" class="btn btn-dark btn-block" onclick="btnplan2()">Calcular pago</a>
                            </div>
                        </div> --}}
                        <div class="form-group col-xs-12">
                            <label for="">Observaciones</label>
                            <textarea name="" id="mobserv" class="form-control">Sin observación</textarea>
                        </div>
                        <input type="hidden" name="" id="plan_id" class="form-control" hidden>
                    </div>                          
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark pull-right" onclick="mipago()">
                        <i class="icon voyager-pen"></i>Pagar
                    </a>
                    {{-- @if (Auth::user()->role_id==1) --}}
                        {{-- <a href="#" class="btn btn-warning btn-sm pull-left" onclick="mipago_mora()">
                            <i class="icon voyager-pen"></i>Guardar con mora
                        </a> --}}
                    {{-- @endif --}}
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
                    <h4 class="modal-title"><i class="voyager-helm"></i> Refinanziar prestamo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label for="">Monto inicial</label>
                            <input type="number" class="form-control" value="{{ $miplan2->monto }}" id="monto_actual" readonly>
                        </div>
                   
                        <div class="col-sm-4 form-group">
                            <label for="">Plazo inicial</label>
                            <input type="number" class="form-control" value="{{ $miplan2->plazo }}" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Interes</label>
                            <input type="number" class="form-control" id="mtipo" value="{{ $miplan2->interes }}" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Cuota actual</label>
                            <input type="number" class="form-control" value="{{ $miplan2->cuota }}" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Deuda actual</label>
                            <input type="number" class="form-control" value="{{ $miplan3->monto }}" id="deuda_actual" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Nuevo monto</label>
                            <input type="number" class="form-control" id="new_monto" value="0">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label for="">Nuevo plazo</label>
                            <input type="number" class="form-control" id="new_plazo" value="0">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label for="">Nueva cuota</label>
                            <input type="number" class="form-control" id="new_cuota" value="{{ $miplan2->cuota }}">
                        </div>
                        <div class="col-sm-4 form-group">
                            <div style="margin-top: 20px;">                            
                                <a href="#" class="btn btn-dark" onclick="btnplan()">Re-calcular plan</a>
                            </div>
                        </div>
               
                        <div class="col-sm-12 form-group">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="lista-tabla">
                                    <thead>
                                        <tr>
                                            <th>NRO</th>
                                            <th>MES</th>                                            
                                            <th>MONTO</th>
                                            <th>INTERES</th>
                                            <th>CAPITAL</th>
                                            <th>CUOTA</th>
                                            <th>DEUDA</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>                                
                            </div>                        
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="">Observaciones</label>
                            <textarea name="" id="" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-4">
                        <input type="number" class="form-control" id="new_monto2" placeholder="nuevo monto" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="miestado" placeholder="estado" readonly>
                    </div>
                    <a href="#" class="btn btn-dark pull-right" onclick="refinanciar()">
                        <i class="icon voyager-pen"></i> Refinanciar prestamo
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- amortizar  --}}
    <div class="modal modal-primary fade" tabindex="-1" id="modal_amortizacion" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-helm"></i> Amortizacion a capital</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                  
                        <div class="form-group col-xs-4">
                            <label for="">Deuda actual</label>                            
                            <input type="number" name="" id="" value="{{ $miplan3->monto }}" class="form-control" readonly>
                        </div>
                        
                        <div class="col-sm-4 form-group">
                            <label for="">Interes a la deuda</label>
                            @php
                                $ideuda = $miplan2->interes * $miplan3->monto;
                                $icapital = $miplan3->monto - $ideuda;
                            @endphp
                            <input type="number" class="form-control" id="ainteres" value="{{ $ideuda }}" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Capital de la deuda</label>
                            <input type="number" class="form-control" id="acapital" value="{{ $icapital }}" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Nueva deuda</label>
                            <input type="text" class="form-control" id="andeuda" value="0" readonly>
                        </div>


                        <div class="col-sm-4 form-group">
                            <label for="">Pago a capital</label>
                            <input type="number" class="form-control" id="apago" value="0">
                        </div>

                        <div class="col-sm-4 form-group">
                            <div style="margin-top: 20px;">                            
                                <a href="#" class="btn btn-dark" onclick="btn_amort()">Calcular deuda</a>
                            </div>
                        </div>
             
                        <div class="col-sm-12 form-group">
                           
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="lista-tabla">
                                    <thead>
                                        <tr>
                                            <th>NRO</th>
                                            <th>MES</th>                                            
                                            <th>MONTO</th>
                                            <th>INTERES</th>
                                            <th>CAPITAL</th>
                                            <th>CUOTA</th>
                                            <th>DEUDA</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>                                
                            </div>   
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="">Detalle</label>
                            <textarea name="" id=""  class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark pull-right" onclick="refinanciar()">
                        <i class="icon voyager-pen"></i> Amortizar a capital
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- em mora  --}}
    <div class="modal modal-primary fade" tabindex="-1" id="modal_mora" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-helm"></i> Pago con mora #{{ $miplan3->nro." - ".$miplan2->tipo->nombre." - ".$miplan3->fecha." - ".$miplan2->plazo }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    
                        <div class="form-group col-xs-4">
                            <label for="">Deuda actual</label>                            
                            <input type="number" name="" id="" value="{{ $miplan3->monto }}" class="form-control" readonly>
                        </div>
                        
                        <div class="col-sm-4 form-group">
                            <label for="">Interes a la deuda</label>
                            @php
                                $ideuda = $miplan2->interes * $miplan3->monto;
                                $icapital = $miplan3->monto - $ideuda;
                            @endphp
                            <input type="number" class="form-control" id="" value="{{ $miplan3->interes }}" readonly>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label for="">Capital de la deuda</label>
                            <input type="number" class="form-control" id="" value="{{ $miplan3->capital }}" readonly>
                        </div>

                 

                        <div class="col-sm-4 form-group">
                            <label for="">Pago parcial</label>
                            <input type="number" class="form-control" id="mora_pago" value="{{ $miplan3->cuota }}">
                        </div>

                        <div class="col-sm-4 form-group">
                            <div style="margin-top: 20px;">                            
                                <a href="#" class="btn btn-dark" onclick="btn_mora()">Re-calcular deuda</a>
                            </div>
                        </div>
                
                        <div class="col-sm-4 form-group">
                            <label for="">deuda acumulada</label>
                            <input type="text" class="form-control" id="mora_deuda" value="0" readonly>
                        </div>

                        <div class="col-sm-12 form-group">
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tabla-mora">
                                    <thead>
                                        <tr>
                                            <th>NRO</th>
                                            <th>MES</th>                                            
                                            <th>MONTO</th>
                                            <th>INTERES</th>
                                            <th>CAPITAL</th>
                                            <th>CUOTA</th>
                                            <th>DEUDA</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>                                
                            </div>   
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="">Detalle</label>
                            <textarea name="" id=""  class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark pull-right" onclick="refinanciar()">
                        <i class="icon voyager-pen"></i> Pagar con mora
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
        


        async function detalle(id){
            $('#modal_detalle').modal('show');
            var mipago = await axios("/api/plan/"+id)
            console.log(mipago.data)
            var misms = "Pasarela: "+mipago.data.pasarelas.nombre+"\n Detalle: "+mipago.data.observacion+"\n Editor: "+mipago.data.user.name
            // console.log(misms)
            swal({
                icon: "info",
                title: "Fecha: "+mipago.data.fecha_pago,
                text: misms

            });
        }

        //cargar plan de pago
        async function pagar(id){
            $('#modal_pagar').modal('show');
            var mipago = await axios("/api/plan/"+id)
            $('#mmonto').val(mipago.data.monto.toFixed(2));
            $('#mnumero').val(mipago.data.nro);
            $('#mdeuda').val(mipago.data.deuda.toFixed(2));
            $('#mcuota').val(mipago.data.cuota);
            $('#minteres').val(mipago.data.interes.toFixed(2));
            $('#mcapital').val(mipago.data.capital.toFixed(2));
            $('#plan_id').val(id);
            localStorage.setItem("miplan", JSON.stringify(mipago.data))
        }

        async function mipago(){      
            if(!$("#fecha_pago").val()){
                swal({
                    icon: "error",
                    title: "Ingresa la fecha de pago"
                })
                return true;
            }      
            swal({
                icon: "info",
                title:  "Esta segur@ de realizar el pago #"+$('#plan_id').val(),                
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
                            var mipago = await axios.post("/api/plan/update", {
                                id: $('#plan_id').val(),
                                fecha_pago: $('#fecha_pago').val(),
                                pasarela_id: $('#pasarela_id').val(),
                                observacion: $('#mobserv').val(),
                                user_id: "{{ Auth::user()->id }}"
                            })
                            location.reload()
                        break;
                    }
                }
            );            
        }

        //recalculawr para mora
        function recalcular(){
            var tipo_id = {{ $miplan2->tipo_id }}
            var miplan = JSON.parse(localStorage.getItem("miplan"))    
            if(tipo_id == 2){
                var midiff = parseFloat(miplan.cuota) - $("#mcuota").val()
                var newmonto = parseFloat($("#mmonto").val()) + midiff
                $("#mmonto").val(newmonto.toFixed(2))    
                var newinter = $("#mmonto").val() * $("#mtipo").val()                                           
                $("#minteres").val(newinter.toFixed(2))
                var newcap = $("#mcuota").val() - newinter
                $("#mcapital").val(newcap.toFixed(2))
            }else if(tipo_id == 1){
                $("#mcapital").val($("#mcuota").val() - $("#minteres").val())  
            }
            if(parseFloat($("#mcuota").val()) < parseFloat($("#minteres").val())){
                swal({
                    title: "La cuota tiene que ser mayor o igual al interes",
                    icon: "error",
                });
                return true
            }
                            
            var mideuda = parseFloat(miplan.cuota) - $("#mcuota").val()
            var newdeuda = parseFloat($("#mdeuda").val()) + parseFloat(mideuda)
            $("#mdeuda").val(newdeuda)         
            $("#mobserv").val($("#mobserv").val()+" el monto adeudado es "+mideuda.toFixed(2))
            toastr.info("Cantidad faltante: "+mideuda.toFixed(2))
        }

        function mipago_mora(){
            if(!$("#fecha_pago").val()){
                swal({
                    icon: "error",
                    title: "Ingresa la fecha de pago"
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
                            var miplan = JSON.parse(localStorage.getItem("miplan")) 
                            var mideuda = parseFloat(miplan.cuota) - $("#mcuota").val()
                            var mipago = await axios.post("/api/plan/update/mora", {
                                id: $('#plan_id').val(),
                                fecha_pago: $('#fecha_pago').val(),
                                pasarela_id: $('#pasarela_id').val(),
                                observacion: $('#mobserv').val(),
                                user_id: "{{ Auth::user()->id }}",
                                mora: mideuda,
                                deuda: $('#mdeuda').val(),
                                capital: $('#mcapital').val(),
                                cuota: $('#mcuota').val(),
                                interes: $('#minteres').val(),
                                tipo_id: {{ $miplan2->tipo_id }},
                                monto_inicial: {{ $miplan2->monto }}
                            })
                            location.reload()
                        break;
                    }
                }
            );    
        }

        //crear plan nuevo
        function btnplan() {

            var newmonto = parseFloat($("#new_monto").val()) + parseFloat($("#deuda_actual").val())
            var new_monto = parseFloat($("#new_monto").val())
            var new_plazo = parseFloat($("#new_plazo").val())

            var monto_actual = parseFloat({{ $miplan2->monto }})
            var monto_minimo = parseFloat({{ $miplan2->tipo->plazo_minimo }})

            if(new_plazo < monto_minimo){
                swal({
                    title: "Error en el nuevo plazo",
                    icon: "error",
                    text: "Plazo min:"+monto_minimo+"\nPlazo max: {{ $miplan2->tipo->plazo_maximo }}"
                });
                return true
            }

            if(new_monto > monto_actual){
                swal({
                    title: "El nuevo monto no tiene que superar el monto inicial",
                    icon: "error",
                    
                });
                return true
            }
            
            if(new_plazo > {{ $miplan2->plazo }}){
                swal({
                    title: "El nuevo plazo no tiene que superar el plazo inicial",
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

            // console.log(newmonto)
            // var micouta = (((newmonto *  parseFloat($("#mtipo").val())) * parseFloat($("#new_plazo").val())) + newmonto) / parseFloat($("#new_plazo").val())
            // $("#new_cuota").val(micouta.toFixed(2))
            calcular_plan(newmonto)    
        }

        //calcular nuevo plan del prestamo
        function calcular_plan(newmonto){ 
            eprest = "valido"

            //limpiar table
            while(llenarTabla.firstChild){
                llenarTabla.removeChild(llenarTabla.firstChild);
            }
            $("#table_totales").empty()

            //variables
            var monto = newmonto
            var tiempo = $("#new_plazo").val()
            var pmensual = parseFloat($("#new_cuota").val())
            var mesinicio = "{{ $miplan3->fecha }}"
            var minro = {{ $miplan3->nro }} - 1

            if ({{ $miplan2->tipo_id }} === 1) {                 
                //procesamiento
                let fechas = [];
                let fecha = [];
                var miplan = []
                let mes_actual = moment(mesinicio);
                var mideuda = 0
                var mimonto = 0
                var miaxu = 0
                var mitotal = 0
                var mitotalI = 0
                var miinteres = parseFloat(0.03 * monto)
                var micapital = parseFloat(pmensual-miinteres)
                var miaxu2 = 0 //%
                for(let i = 1; i <= tiempo; i++) {               
                    fechas[i] = mes_actual.format('MMMM-YY');
                    fecha[i] = mes_actual.format('YYYY-MM-DD');
                    mes_actual.add(1, 'month');
                    if (i == 1) {
                        mimonto = parseFloat(monto)
                        mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu = parseFloat(mideuda)     
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    } else if(i == tiempo){
                        mimonto = parseFloat(miaxu)
                        pmensual = parseFloat(mimonto) + parseFloat(miinteres) 
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))    
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto)      
                    } else {
                        mimonto = parseFloat(miaxu)
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu = parseFloat(mideuda)
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    }
                    miaxu2 = 100 - miaxu2
                    minro = minro + 1
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${minro}</td>
                        <td>${fechas[i]}</td>                    
                        <td>${mimonto.toFixed(2)}</td>
                        <td>${miinteres.toFixed(2)}</td>
                        <td>${micapital.toFixed(2)}</td>
                        <td>${pmensual.toFixed(2)}</td>
                        <td>${mideuda.toFixed(2)}</td>
                    `;
                    llenarTabla.appendChild(row)

                    if (mideuda < 0) {
                        row.style.backgroundColor = "#C95D58"
                        eprest = "invalido"
                    }
                    
                    mitotal = parseFloat(mitotal) + parseFloat(pmensual)
                    mitotalI = parseFloat(mitotalI + miinteres)
                    miplan.push({'mes': fechas[i], 'fecha': fecha[i], 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': pmensual, 'deuda': mideuda, 'nro': minro})
                }             
            } else if({{ $miplan2->tipo_id }} === 2){                
                let fechas = [];
                let fecha = [];
                var miplan = []
                let mes_actual = moment(mesinicio);
                var mideuda = 0
                var mimonto = 0
                var miaxu = 0
                var mitotal = 0
                var mitotal = 0
                var mitotalI = 0
                var miinteres = parseFloat(0.05 * monto)
                var micapital = parseFloat(pmensual-miinteres)       
                var miaxu2 = 0 //%
                for(let i = 1; i <= tiempo; i++) {
                    fechas[i] = mes_actual.format('MMMM-YY');
                    fecha[i] = mes_actual.format('YYYY-MM-DD');
                    mes_actual.add(1, 'month');
                    if (i == 1) {
                        mimonto = parseFloat(monto)
                        mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu = parseFloat(mideuda)
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto)       
                    } else if(i == tiempo){
                        mimonto = parseFloat(miaxu)                
                        miinteres = parseFloat(interes * mimonto)
                        pmensual = parseFloat(parseFloat(mimonto) + parseFloat(miinteres))
                        micapital = parseFloat(pmensual-miinteres)     
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    } else {
                        mimonto = parseFloat(miaxu)                                    
                        miinteres = parseFloat(interes * mimonto)
                        micapital = parseFloat(pmensual-miinteres) 
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))  
                        miaxu = parseFloat(mideuda) 
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    }
                    miaxu2 = 100 - miaxu2

                    const row = document.createElement('tr');
                    row.innerHTML = `                    
                        <td>${i}</td>
                        <td>${fechas[i]}</td>
                        <td>${mimonto.toFixed(2)}</td>
                        <td>${miinteres.toFixed(2)}</td>
                        <td>${micapital.toFixed(2)}</td>
                        <td>${pmensual.toFixed(2)}</td>
                        <td>${mideuda.toFixed(2)}</td>
                    `;
                    llenarTabla.appendChild(row)
                    
                    if (mideuda < 0) {
                        row.style.backgroundColor = "#C95D58"
                        eprest = "invalido"
                    }

                    mitotal = parseFloat(mitotal) + parseFloat(pmensual)
                    mitotal+=pmensual
                    mitotalI+=miinteres
                    miplan.push({'mes': fechas[i], 'fecha': fecha[i], 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': pmensual, 'deuda': mideuda, 'nro': i})                
                }
            }

            // totales
            var mitotalG = (mitotalI*100) / monto
            localStorage.setItem("miplan", JSON.stringify(miplan))
            
            //validar plan
            if (eprest == "valido") {
                swal({
                    title: "Plan creado correctamente",
                    icon: "success",
                });               
                $("#new_monto2").val(newmonto)
                $("#miestado").val(eprest)
            }else{
                toastr.error("Error en el plan..")
                $("#new_monto2").val(0)
                $("#miestado").val(eprest)
            }
        }

        // refinanciar
        async function refinanciar(){
            const miplan = localStorage.getItem("miplan")
            var avanze = ({{ $miplan3->nro }} * 100) / {{ $miplan2->plazo }} 
            if ({{ setting('prestamos.refin_50') }} && avanze < 50) {
                swal({
                    title: "El tiempo minimo para refinanziar es del 50% de avanze",
                    icon: "error",
                    text: "Avance actual es de %: "+avanze.toFixed(2)
                });
                return true
            }

            if (eprest=="invalido") {
                swal({
                    title: "Corrige en plan de pagos",
                    icon: "error"
                });
                return true
            }
            swal({
                icon: "warning",
                // title: "Cliente: "+micliente,
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
                            
                            var midata = await axios.post("/api/plan/refin", {
                                prestamo_id: {{ $miplan2->id }},
                                nro: {{ $miplan3->nro }},
                                miplan: miplan,
                                new_monto: $("#new_monto").val(),
                                id: {{ $miplan3->id }}
                            })
                            // console.log(midata.data)
                            location.reload()
                        break;
                    }
                }
            );
        }

        //calcular mora
        const tabla_mora = document.querySelector('#tabla-mora tbody');
        function btn_mora() {
            // toastr.info("proceso exitoso..")                        
            // var new_data  = {
            //     interes: $("#minteres").val(),
            //     capital: $("#mcapital").val(),
            //     deuda: $("#mdeuda").val()
            // }
            // mcuota
            // $("#minteres").val(new_data.interes)
            // var new_interes =   parseFloat($("#new_monto").val())

            
            //limpiar table
            while(tabla_mora.firstChild){
                tabla_mora.removeChild(tabla_mora.firstChild);
            }

            //variables
            var monto = $("#mora_pago").val()
            var tiempo = ({{ $miplan2->plazo}} - {{ $miplan3->nro }})
            var pmensual = {{ $miplan3->cuota }} 
            var mesinicio = "{{ $miplan3->fecha }}"
            var minro = {{ $miplan3->nro }} - 1

            console.log({{ $miplan2->tipo_id }})
            if ({{ $miplan2->tipo_id }} === 1) {                 
                //procesamiento
                let fechas = [];
                let fecha = [];
                var miplan = []
                let mes_actual = moment(mesinicio);
                var mideuda = 0
                var mimonto = 0
                var miaxu = 0
                var mitotal = 0
                var mitotalI = 0
                var miinteres = parseFloat(0.03 * monto)
                var micapital = parseFloat(pmensual-miinteres)
                var miaxu2 = 0 //%
                for(let i = 1; i <= tiempo; i++) {               
                    fechas[i] = mes_actual.format('MMMM-YY');
                    fecha[i] = mes_actual.format('YYYY-MM-DD');
                    mes_actual.add(1, 'month');
                    if (i == 1) {
                        mimonto = parseFloat(monto)
                        mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu = parseFloat(mideuda)     
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    } else if(i == tiempo){
                        mimonto = parseFloat(miaxu)
                        pmensual = parseFloat(mimonto) + parseFloat(miinteres) 
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))    
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto)      
                    } else {
                        mimonto = parseFloat(miaxu)
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu = parseFloat(mideuda)
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    }
                    miaxu2 = 100 - miaxu2
                    minro = minro + 1
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${minro}</td>
                        <td>${fechas[i]}</td>                    
                        <td>${mimonto.toFixed(2)}</td>
                        <td>${miinteres.toFixed(2)}</td>
                        <td>${micapital.toFixed(2)}</td>
                        <td>${pmensual.toFixed(2)}</td>
                        <td>${mideuda.toFixed(2)}</td>
                    `;
                    tabla_mora.appendChild(row)

                    // if (mideuda < 0) {
                    //     row.style.backgroundColor = "#C95D58"
                    //     eprest = "invalido"
                    // }
                    
                    mitotal = parseFloat(mitotal) + parseFloat(pmensual)
                    mitotalI = parseFloat(mitotalI + miinteres)
                    miplan.push({'mes': fechas[i], 'fecha': fecha[i], 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': pmensual, 'deuda': mideuda, 'nro': minro})
                }             
            } else if({{ $miplan2->tipo_id }} === 2){                
                let fechas = [];
                let fecha = [];
                var miplan = []
                let mes_actual = moment(mesinicio);
                var mideuda = 0
                var mimonto = 0
                var miaxu = 0
                var mitotal = 0
                var mitotal = 0
                var mitotalI = 0
                var miinteres = parseFloat(0.05 * monto)
                var micapital = parseFloat(pmensual-miinteres)       
                var miaxu2 = 0 //%
                for(let i = 1; i <= tiempo; i++) {
                    fechas[i] = mes_actual.format('MMMM-YY');
                    fecha[i] = mes_actual.format('YYYY-MM-DD');
                    mes_actual.add(1, 'month');
                    if (i == 1) {
                        mimonto = parseFloat(monto)
                        mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu = parseFloat(mideuda)
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto)       
                    } else if(i == tiempo){
                        mimonto = parseFloat(miaxu)                
                        miinteres = parseFloat(interes * mimonto)
                        pmensual = parseFloat(parseFloat(mimonto) + parseFloat(miinteres))
                        micapital = parseFloat(pmensual-miinteres)     
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    } else {
                        mimonto = parseFloat(miaxu)                                    
                        miinteres = parseFloat(interes * mimonto)
                        micapital = parseFloat(pmensual-miinteres) 
                        mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual))  
                        miaxu = parseFloat(mideuda) 
                        miaxu2 = (parseFloat(mideuda) * 100) / parseFloat(mimonto) 
                    }
                    miaxu2 = 100 - miaxu2

                    const row = document.createElement('tr');
                    row.innerHTML = `                    
                        <td>${i}</td>
                        <td>${fechas[i]}</td>
                        <td>${mimonto.toFixed(2)}</td>
                        <td>${miinteres.toFixed(2)}</td>
                        <td>${micapital.toFixed(2)}</td>
                        <td>${pmensual.toFixed(2)}</td>
                        <td>${mideuda.toFixed(2)}</td>
                    `;
                    llenarTabla.appendChild(row)
                    
                    if (mideuda < 0) {
                        row.style.backgroundColor = "#C95D58"
                        eprest = "invalido"
                    }

                    mitotal = parseFloat(mitotal) + parseFloat(pmensual)
                    mitotal+=pmensual
                    mitotalI+=miinteres
                    miplan.push({'mes': fechas[i], 'fecha': fecha[i], 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': pmensual, 'deuda': mideuda, 'nro': i})                
                }
            }
        }

        //calular amort
        function btn_amort() {
            var mindeuda = parseFloat($("#acapital").val()) - (parseFloat($("#apago").val()) - parseFloat($("#ainteres").val()))
            $("#andeuda").val(mindeuda)
        }
    </script>
@stop
