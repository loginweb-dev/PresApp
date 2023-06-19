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
    <div class="page-content edit-add container-fluid">
        <div class="row">
            
            <div class="col-md-5">
                <div class="panel panel-bordered">
                
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('prestamo_store') }}"
                            method="POST" enctype="multipart/form-data" id="miform">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}
                        <div class="panel-body">

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
                            <div class="form-group col-xs-12">
                                <a href="#" id="btnCalcular" class="btn btn-dark btn-block"><i class="icon voyager-activity"></i> Calcular o Simular</a>

                            </div>
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
                                <a href="#" id="btnGuardar"  class="btn btn-dark btn-block"><i class="icon voyager-data"></i> Enviar y Guardar</a>
                            </div>
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                    </div>
                </div>
                
            </div>

            <div class="col-md-7">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="lista-tabla">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>MES</th>
                                        <th>NRO</th>
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
    <!-- End Delete File Modal -->
@stop

@section('javascript')
{{-- <script src="{{ asset('js/moment.js') }}"></script> --}}
    <script>
        var params = {};
        var $file;

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

        const llenarTabla = document.querySelector('#lista-tabla tbody');
        localStorage.removeItem("miplan")
        btnCalcular.addEventListener('click', () => {
            const monto = document.getElementById('monto');
            const tiempo = document.getElementById('plazo');
            const interes = document.getElementById('interes');
            const btnCalcular = document.getElementById('btnCalcular');
            const pmensual = document.getElementById('cuota');
            const mitipo = document.getElementById('tipo_id');
            const mesinicio = document.getElementById('mes_inicio');
            if (mitipo.value == 1) {
                calcularCuota(parseFloat(monto.value), parseFloat(interes.value), parseInt(tiempo.value), parseFloat(pmensual.value), mesinicio.value);
            } else if(mitipo.value == 1){
                calcularCuota2(monto.value, interes.value, tiempo.value, pmensual.value);
            }  else{
                swal({
                    title: "Selecciona un tipo de prestamos",
                    icon: "error",
                });
            }
        })
        
        function calcularCuota(monto, interes, tiempo, pmensual, mesinicio){

            if(!mesinicio){
                swal({
                    title: "Ingresa el mes d inicio",
                    icon: "error",
                });
                return true;
            }
            while(llenarTabla.firstChild){
                llenarTabla.removeChild(llenarTabla.firstChild);
            }
            let fechas = [];
            let fecha = [];
            var miplan = []
            let mes_actual = moment(mesinicio);
            var mideuda = 0
            var mimonto = 0
            var miaxu = 0
            var mitotal = 0
            var miinteres = parseFloat(interes * monto).toFixed(2)
            var micapital = parseFloat(pmensual-miinteres).toFixed(2)
            
            for(let i = 1; i <= tiempo; i++) {
                //Formato fechas
                fechas[i] = mes_actual.format('MMMM-YY');
                fecha[i] = mes_actual.format('DD-MM-YY');
                mes_actual.add(1, 'month');
                if (i == 1) {
                    mimonto = parseFloat(monto).toFixed(2)
                    mideuda =  parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual)).toFixed(2) 
                    miaxu = parseFloat(mideuda).toFixed(2)         
                } else if(i == tiempo){
                    mimonto = parseFloat(miaxu).toFixed(2)
                    pmensual = parseFloat(mimonto) + parseFloat(miinteres) 
                    mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual)).toFixed(2)                    
                } else {
                    mimonto = parseFloat(miaxu).toFixed(2)
                    mideuda = parseFloat((parseFloat(mimonto)+parseFloat(miinteres)) - parseFloat(pmensual)).toFixed(2)  
                    miaxu = parseFloat(mideuda).toFixed(2)  
                }
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${fechas[i]}</td>
                    <td>${i}</td>
                    <td>${mimonto}</td>
                    <td>${miinteres}</td>
                    <td>${micapital}</td>
                    <td>${pmensual}</td>
                    <td>${mideuda}</td>
                `;
                llenarTabla.appendChild(row)
                mitotal+=pmensual
                miplan.push({'mes': fecha[i], 'monto': mimonto, 'interes': miinteres, 'capital': micapital, 'cuota': pmensual, 'deuda': mideuda, 'nro': i})
            }
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan='5' align='right'><h3>Total: </h3></td>
                <td colspan='5' align='left'><h3>${mitotal.toFixed(2)}</h3></td>
            `;
            llenarTabla.appendChild(row)
            localStorage.setItem("miplan", JSON.stringify(miplan))

            swal({
                title: "Plan creado correctamente",
                // text: "Plan creado correctamente",
                icon: "success",
            });
        }


        function calcularCuota2(monto, interes, tiempo, pmensual){
   
        }

        btnGuardar.addEventListener('click', () => {
            const micliente = $("#cliente_id option:selected").text()
            const miobserv = $("#observacion").val()
            const mimonto = $("#monto").val()
            const miplan = localStorage.getItem("miplan")

            if(!miplan){
                swal({
                    title: "Crea una plan de pagos",
                    icon: "error",
                });
                return true;
            }
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
                            var respt = await axios.post('/api/prestamos/store', {
                                cliente_id:  $("#cliente_id").val(),
                                tipo_id:  $("#tipo_id").val(),
                                observacion:  $("#observacion").val(),
                                miplan: miplan,
                                cuota:  $("#cuota").val(),
                                plazo:  $("#plazo").val(),
                                interes:  $("#interes").val(),
                                monto:  $("#monto").val(),
                                user_id:  "{{ Auth::user()->id }}",
                                mes_inicio:  $("#mes_inicio").val()
                            })
                            // console.log(respt.data)
                            location.href = "/admin/prestamos"
                        break;
                    }
                }
            );
        })
    </script>
@stop
