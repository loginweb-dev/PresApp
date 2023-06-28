@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="">
        <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;

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
        {{-- <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-dark">
            <i class="icon voyager-angle-left"></i> <span class="hidden-xs hidden-sm">Volver</span>
        </a> --}}
        {{-- <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_refinanciar">
            <i class="icon voyager-data"></i> <span class="hidden-xs hidden-sm">Eventos</span>
        </a> --}}

        <a href="{{ route('pdf_prestamo', $dataTypeContent->getKey()) }}" class="btn btn-success">
            <i class="icon voyager-certificate"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
        </a>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_refinanciar">
            <i class="icon voyager-params"></i> <span class="hidden-xs hidden-sm">Refinanciar</span>
        </a>
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
                                    <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($row->field) ?: '' }}">
                                        {{ __('voyager::generic.download') }}
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
                    $pasarelas = App\Pasarela::all();
                    $countcsp = 0;
                    $countcnp = 0;
                    $count_mora = 0;
                @endphp
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>MES</th>
                                        <th>NRO</th>
                                        <th>MONTO</th>
                                        <th>INTERES</th>
                                        <th>CAPITAL</th>
                                        <th>CUOTA</th>
                                        <th>DEUDA</th>
                                        <th>PAGADO</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- style="background-color:#FF0000" --}}
                                    @foreach ($miplan as $item)

                                        <tr>
                                            <td>{{ $item->id }}</td>
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
                                                    {{ $item->nro }}
                                                @endif                     
                                            </td>
                                            <td>{{ $item->monto }}</td>
                                            <td>{{ $item->interes }}</td>
                                            <td>{{ $item->capital }}</td>
                                            <td>{{ $item->cuota }}</td>
                                            <td>{{ $item->deuda }}</td>
                                            <td>
                                                @if ($item->pagado)
                                                    <h2 class="text-center"><i class="icon voyager-thumbs-up"></i></h2>                                                    
                                                @else
                                                    <h2 class="text-center"><i class="icon voyager-x"></i></h2>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->pagado)
                                                    @php $countcsp++ @endphp
                                                    <a href="#" class="btn btn-sm btn-dark" onclick="detalle('{{ $item->id }}')"> <span>Detalle</span>
                                                @else
                                                    @php $countcnp++ @endphp
                                                    <a href="#" class="btn btn-sm btn-warning" onclick="pagar('{{ $item->id }}')"> <span>Pagar</span>
                                                </a>
                                                @endif
                                              
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                               
                            </table>
                            <h4>Cuatas Pagadas:  {{ $countcsp }} | Cuatas No pagadas: {{ $countcnp }} | En mora: {{ $count_mora }}</h4>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
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
    </div><!-- /.modal -->

    <div class="modal modal-primary fade" tabindex="-1" id="modal_pagar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-plus"></i> Nuevo Pago</h4>
                </div>
                <div class="modal-body">
                    <div class="row">             
                        <div class="form-group col-xs-6">
                            <label for="">Pasarela</label>
                            <select name="" id="pasarela_id" class="form-control">
                                @foreach ($pasarelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="">Fecha</label>
                            <input type="date" name="" id="fecha_pago" class="form-control">
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="">Cuota</label>
                            <input type="text" name="" id="mcuota" class="form-control" readonly>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="">Interes</label>
                            <input type="text" name="" id="minteres" class="form-control" readonly>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="">Capital</label>
                            <input type="text" name="" id="mcapital" class="form-control" readonly>
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="">Observaciones</label>
                            <textarea name="" id="mobserv" class="form-control">Sin observación</textarea>
                        </div>
                        <input type="hidden" name="" id="plan_id" class="form-control" hidden>
                    </div>                          
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark btn-sm pull-right" onclick="mipago()">
                        <i class="icon voyager-pen"></i>Guardar
                    </a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal modal-primary fade" tabindex="-1" id="modal_refinanciar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-helm"></i> Refinanciar</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="" class="col-sm-12">Reglas</label>
                            <div>
                                <label class="col-sm-3 checkbox-inline">
                                    <input id="" type="checkbox" value="">Mora
                                </label>
                                <label class="col-sm-3 checkbox-inline">
                                  <input id="genMale" type="checkbox" value="genMale">Plazo
                                </label>
                                <label class="col-sm-3 checkbox-inline">
                                  <input id="genFemale" type="checkbox" value="genFemale">Monto
                                </label>
                              </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="">Plan de pago</label>
                            <select name="" id="" class="form-control">
                                @foreach ($miplan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nro }} | {{ $item->mes }} | {{ $item->fecha }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="">Nuevo plazo</label>
                            <input type="number" class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="">Nuevo monto</label>
                            <input type="number" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark btn-sm pull-right" onclick="refinanciar()">
                        <i class="icon voyager-pen"></i> Actualizar
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

        
        async function refinanciar(){
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
                            location.reload()
                        break;
                    }
                }
            );
        }

        async function pagar(id){
            $('#modal_pagar').modal('show');
            var mipago = await axios("/api/plan/"+id)
            $('#mcuota').val(mipago.data.cuota);
            $('#minteres').val(mipago.data.interes);
            $('#mcapital').val(mipago.data.capital);
            $('#plan_id').val(id);
        }

        async function detalle(id){
            $('#modal_detalle').modal('show');
            var mipago = await axios("/api/plan/"+id)
            console.log(mipago.data)
            var misms = "Pasarela: "+mipago.data.pasarelas.nombre+"\n Detalle: "+mipago.data.observacion
            // console.log(misms)
            swal({
                icon: "info",
                title: "Fecha: "+mipago.data.fecha_pago,
                text: misms

            });
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
    </script>
@stop
