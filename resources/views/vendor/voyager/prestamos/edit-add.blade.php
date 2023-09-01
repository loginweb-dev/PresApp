@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1>
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}

    </h1>
    @include('voyager::multilingual.language-selector')
    
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-xs-5">
                {{-- <a href="#" id="btnCalcular" class="btn btn-block btn-dark"><i class="icon voyager-helm"></i> 
                    Crear plan pagos
                </a> --}}
                {{-- <div class="panel panel-bordered"> --}}
                    {{-- <div class="panel-body"> --}}
           
                        {{-- <br> --}}
                        <!-- form start -->
                        {{-- <form role="form"
                                class="form-edit-add"
                                action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('prestamo_store') }}"
                                method="POST" enctype="multipart/form-data" id="miform">
                    
                            @if($edit)
                                {{ method_field("PUT") }}
                            @endif

                    
                            {{ csrf_field() }} --}}
                            {{-- <div class="panel-body"> --}}

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Adding / Editing -->
                                @php
                                    $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                                @endphp
                                {{-- <div class="form-group col-xs-12">
                                    <a href="#" id="btnCalcular" class="btn btn-warning btn-block"><i class="icon voyager-activity"></i> Crear plan pagos</a>
                                </div> --}}
                                @foreach($dataTypeRows as $row)
                                    <!-- GET THE DISPLAY OPTIONS -->
                                    @php
                                        $display_options = $row->details->display ?? NULL;
                                        if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                        }
                                    @endphp
                                    @if (isset($row->details->legend) && isset($row->details->legend->text))
                                        <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                    @endif

                                    <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>

                                        {{ $row->slugify }}
                                        <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                        @include('voyager::multilingual.input-hidden-bread-edit-add')
                                        @if ($add && isset($row->details->view_add))
                                            @include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details])
                                        @elseif ($edit && isset($row->details->view_edit))
                                            @include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details])
                                        @elseif (isset($row->details->view))
                                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                        @elseif ($row->type == 'relationship')
                                            @include('voyager::formfields.relationship', ['options' => $row->details])
                                        @else
                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                        @endif

                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                        @endforeach
                                        @if ($errors->has($row->field))
                                            @foreach ($errors->get($row->field) as $error)
                                                <span class="help-block">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach

                                <div class="form-group col-xs-12">
                                    <button id="btnGuardar"  class="btn btn-dark btn-block"><i class="icon voyager-data"></i> Enviar y Guardar</button>
                                </div>
                            {{-- </div> --}}
                        {{-- </form> --}}

                        <div style="display:none">
                            <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                            <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                        </div>
                    {{-- </div> --}}
                {{-- </div> --}}
                
            </div>

            <div class="col-xs-7">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="lista-tabla">
                        <thead>
                            <tr>
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
                    <div id="miaccion" class="text-right"></div>
                </div>
            </div>
          
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade modal-primary" id="modal_plan">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i>Nuevo plan</h4>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-4">
                            <label for="">Ultima fecha</label>
                            <input type="date" name="" id="plan_fecha" class="form-control">         
                            <div id="idplan"></div>                   
                        </div>
                        <div class="col-sm-4">
                            <label for="">Deuda actual</label>
                            <input type="number" name="" id="plan_deuda" class="form-control">                            
                        </div>
                        <div class="col-sm-4">
                            <label for="">Nueva cuota</label>
                            <input type="number" name="" id="plan_nueva_cuota" class="form-control">                            
                        </div>
                        <div class="col-sm-4">
                            <label for="">Interes</label>
                            <input type="number" name="" id="plan_interes" class="form-control">                            
                        </div>
                        <div class="col-sm-4">
                            <label for="">Capital</label>
                            <input type="number" name="" id="plan_capital" class="form-control">                            
                        </div>
                        <div class="col-sm-4">
                            <label for="">Nueva deuda</label>
                            <input type="number" name="" id="plan_nueva_deuda" class="form-control">                            
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="store_plan()" class="btn btn-warning">Enviar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')

<script src=" https://cdn.jsdelivr.net/npm/markdown-it@13.0.1/dist/markdown-it.min.js "></script>
    <script>
        var params = {};
        var $file;
        const llenarTabla = document.querySelector('#lista-tabla tbody');
        var miplan = []
        localStorage.removeItem("miplan")
        localStorage.removeItem("mitipo")
        $("#fecha_prestamos").val("{{ date('Y-m-d') }}")
        $("#mes_inicio").val("{{ date('Y-m-d') }}")
        var md = window.markdownit();

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

        $("#tipo_id").change(async function (e) { 
            e.preventDefault();

            var mitipo = await axios("/api/tipo/"+this.value)
            localStorage.setItem('mitipo', JSON.stringify(mitipo.data))
            $("#plazo").val(mitipo.data.plazo_minimo)   
            simular()
        });

        $("#monto").keyup(function (e) {     
  
            simular()
        });
        
        $("#cuota").keyup(function (e) {     
            simular()
        });

        $("#plazo").keyup(function (e) {     
            
        });

        $("#clase").change(function (e) {     
            simular()
        });
    
        $("#mes_inicio").keyup(function (e) {     
            simular()
        });
        

        btnGuardar.addEventListener('click', async () => {
            const micliente = $("#cliente_id option:selected").text()
            const miobserv = $("#observacion").val()
            const mimonto = $("#monto").val()
            // const miplan = localStorage.getItem("miplan")
            const fecha_prestamos = $("#fecha_prestamos").val()
            if(micliente == ''){
                swal({
                    title: "Selecciona un cliente",
                    icon: "error",
                });
                return true;
            }
            if(miobserv.length < 10){
                swal({
                    title: "Ingresa una descripción del prestamos, con 10 caracteres minimo (cant. actual: "+miobserv.length+")",
                    icon: "error",
                });
                return true;
            }
            if(fecha_prestamos == ''){
                swal({
                    title: "Ingres@ la fecha del prestamo",
                    icon: "error",
                });
                return true;
            }
            swal({
                icon: "warning",
                title: "Cliente: "+micliente,
                text:  "Esta segur@ de guardar el nuevo prestamo de: "+parseFloat(mimonto).toFixed(2)+" Bs. ?",                
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
                            toastr.info("Enviado datos al servidor, espere por favor...")
                            $("#btnGuardar").hide()
                            var codigo = (Math.random() + 1).toString(36).substring(7)
                            const tipodata = JSON.parse(localStorage.getItem('mitipo'))
                            localStorage.setItem("miplan", JSON.stringify(miplan))   
                            // const miplanes = localStorage.getItem("miplan")
                            // console.log(localStorage.getItem("miplan"))
                            var respt = await axios.post('/api/prestamos/store', {
                                cliente_id:  $("#cliente_id").val(),
                                tipo_id:  $("#tipo_id").val(),
                                estado_id:  1,                                
                                observacion:  $("#observacion").val(),
                                cuota:  $("#cuota").val(),
                                plazo:  $("#plazo").val(),
                                interes:  tipodata.monto_interes,
                                monto:  $("#monto").val(),
                                user_id:  "{{ Auth::user()->id }}",
                                mes_inicio:  $("#mes_inicio").val(),
                                fecha_prestamos:  $("#fecha_prestamos").val(),
                                miplan: localStorage.getItem("miplan"),
                                codigo: codigo,
                                clase: $("#clase").val()
                            })

                            if(document.getElementById('documentos').files[0]){
                                let data = new FormData();
                                data.append('documentos', document.getElementById('documentos').files[0]);
                                data.append('prestamo_id', respt.data.id);
                                await axios.post('/api/upload', data).then(function (response) {
                                    console.log(response.data);
                                });                                
                            }else{
                                
                            }
                            // console.log(respt.data)
                            location.href = "/admin/prestamos"
                        break;
                    }
                }
            );
        })

        function simular(){

            const cuota = parseFloat(document.getElementById('cuota').value);
            const monto = document.getElementById('monto').value;
            const mitipo = document.getElementById('tipo_id').value;
            const mesinicio = document.getElementById('mes_inicio').value;
            const miclase = document.getElementById('clase').value;
            const tipodata = JSON.parse(localStorage.getItem('mitipo'))

            //limpiar table
            while(llenarTabla.firstChild){
                llenarTabla.removeChild(llenarTabla.firstChild);
            }
            // localStorage.removeItem("miplan")
            miplan = []

            //procesamiento
            let mimes = null;
            let fecha = null;
            let mes_actual = moment(mesinicio);
            var mideuda = monto
            var mimonto = 0
            var micuota = 0
            var miaxu = 0
            var miplazo = 1
            var micapital = 0
            while (cuota <= mideuda && miplazo <= tipodata.plazo_maximo) {
                $("#plazo").val(miplazo)
                mimes = mes_actual.format('MMMM-YY')
                fecha = mes_actual.format('YYYY-MM-DD')
              
                // mimonto = (miplazo == 1) ? parseFloat(monto) : parseFloat(miaxu)

                // if (miclase == 'Fijo') {
                //     miinteres = parseFloat(tipodata.monto_interes * monto)
                //     micapital = parseFloat(cuota-miinteres)
                    
                // } else if(miclase == 'Variable'){
                //     miinteres = parseFloat(tipodata.monto_interes * mimonto)
                //     micapital = parseFloat(cuota-miinteres)
                // }
                // switch ("{{ setting('prestamos.redondear') }}") {
                //     case 'nor':
                //         mimonto = (miplazo == 1) ? parseFloat(monto) : parseFloat(miaxu)
                //         mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(cuota))
                //         break;
                //     case 'rmx': //redondear al maximo
                        mimonto = (miplazo == 1) ? parseFloat(monto) : Math.round(parseFloat(miaxu))
                        if (miclase == 'Fijo') {
                            miinteres = Math.round(parseFloat(tipodata.monto_interes * monto))
                            micapital = Math.round(parseFloat(cuota-miinteres))                     
                        } else if(miclase == 'Variable'){
                            miinteres = Math.round(parseFloat(tipodata.monto_interes * mimonto))
                            micapital = Math.round( parseFloat(cuota-miinteres))
                        }
                        mideuda =  Math.round(parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(cuota)))                        
                //         break;
                //     case 'rmi':
                //         mimonto = (miplazo == 1) ? parseFloat(monto) : parseFloat(miaxu)
                //         mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(cuota))            
                //         break;
                // }
                miaxu = parseFloat(mideuda)

                const row = document.createElement('tr');
                row.innerHTML = `          
                    <td>NRO:${miplazo}<br>${mimes}</td>                    
                    <td>${mimonto.toFixed(0)}</td>
                    <td>${miinteres.toFixed(0)}</td>
                    <td>${micapital.toFixed(0)}</td>
                    <td>${cuota.toFixed(0)}</td>
                    <td>${mideuda.toFixed(0)}</td>
                `;
                llenarTabla.appendChild(row)
                miplan.push({'mes': mimes, 'fecha': fecha, 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': cuota, 'deuda': mideuda, 'nro': miplazo})     

                mes_actual.add(1, 'month')
                miplazo = miplazo + 1                
            }
                

            if( miaxu > 0){
                mimes = mes_actual.format('MMMM-YY')
                fecha = mes_actual.format('YYYY-MM-DD')
                mimonto = Math.round(parseFloat(miaxu))
                
                miinteres = Math.round(parseFloat(tipodata.monto_interes * mimonto))
                micuota = Math.round(parseFloat(miaxu + miinteres))
                micapital = Math.round( parseFloat(micuota-miinteres))
                
                mideuda =  Math.round(parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(micuota))) 
                
                const row = document.createElement('tr')
                row.innerHTML = `          
                    <td>NRO:${miplazo}<br>${mimes}</td>                    
                    <td>${mimonto.toFixed(0)}</td>
                    <td>${miinteres.toFixed(0)}</td>
                    <td>${micapital.toFixed(0)}</td>
                    <td>${micuota.toFixed(0)}</td>
                    <td>${mideuda.toFixed(0)}</td>
                `;
                llenarTabla.appendChild(row)
                $("#plazo").val(miplazo)
                miplan.push({'mes': mimes, 'fecha': fecha, 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': cuota, 'deuda': mideuda, 'nro': miplazo})  
            }
        }

        // function modal_plan(miplan_aux) { 
        //     $("#modal_plan").modal()
        //     $("#plan_nueva_cuota").val(miplan_aux.cuota)
        //     $("#plan_deuda").val(miplan_aux.deuda)
        //     $("#plan_interes").val(miplan_aux.interes)
        //     $("#plan_capital").val(miplan_aux.capital)
        //     $("#plan_nueva_deuda").val(0)
        //     $("#plan_fecha").val(miplan_aux.fecha)
        //     //$("#idplan").html("<small>"+miplan_aux.length+"</small>")
        // }

        function store_plan(){
            $("#modal_plan").modal('hide')
            var mes_actual = moment($("#plan_fecha").val())
            mes_actual.add(1, 'month')

            var miplazo = parseInt($("#plazo").val()) + 1
            var mimes = mes_actual.format('MMMM-YY')
            var fecha = mes_actual.format('YYYY-MM-DD')
            var micuota = parseFloat($("#plan_nueva_cuota").val())
            var micapital = parseFloat($("#plan_capital").val())
            var miinteres = parseFloat($("#plan_interes").val())
            var mimonto = parseFloat($("#plan_deuda").val())
            var mideuda = parseFloat($("#plan_nueva_deuda").val())

            const row = document.createElement('tr');
                row.innerHTML = `
          
                    <td>NRO:${miplazo}<br>${mimes}</td>                    
                    <td>${mimonto.toFixed(2)}</td>
                    <td>${miinteres.toFixed(2)}</td>
                    <td>${micapital.toFixed(2)}</td>
                    <td>${micuota.toFixed(2)}</td>
                    <td>${mideuda.toFixed(2)}</td>
                `;
                llenarTabla.appendChild(row)
                
            miplan.push({'mes': mimes, 'fecha': fecha, 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': micuota, 'deuda': mideuda, 'nro': miplazo})     
            
            $("#plazo").val(miplazo)
            toastr.success("Plan creado..")
            // console.log(miplan)
        }

        //ultimo pago
        $("#plan_nueva_cuota").keyup(function (e) { 

            if ($("#plan_deuda").val() == $("#plan_nueva_cuota").val()) {
                $("#plan_capital").val(0)            
                $("#plan_nueva_deuda").val(0)
                $("#plan_interes").val(0)
            } else {
                var micapital = $("#plan_nueva_cuota").val() - $("#plan_interes").val()      
                var mideuda = $("#plan_deuda").val() - micapital
                $("#plan_capital").val(micapital)            
                $("#plan_nueva_deuda").val(mideuda)
            } 

        });
    </script>
@stop
