@extends('voyager::master')

@section('page_title', "Bot-whatsapp")

@section('page_header')

    <h1>
        <i class="voyager-helm"></i> Chatbot v1.0
    </h1>
@stop

@section('content')

@php
    $mihistorias = App\History::orderBy("id", "DESC")->limit(100)->get();
    $miledas = App\Lead::orderBy("id", "DESC")->limit(100)->get();
@endphp
<div class="container-fluid">

    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#estado" aria-controls="eastado" role="tab" data-toggle="tab">Inicio</a></li>
            <li role="presentation"><a href="#historial" aria-controls="historial" role="tab" data-toggle="tab">Flujos</a></li>
            <li role="presentation"><a href="#leads" aria-controls="flujos" role="tab" data-toggle="tab">Leads</a></li>
            <li role="presentation"><a href="#masivo" aria-controls="masivo" role="tab" data-toggle="tab">Envios masivos</a></li>
        </ul>
      
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="estado">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="form-group col-xs-4 text-center">
                            <label for="">Escanea la imagen con tu whatsapp (como whatsapp web)</label>
                            <img src="{{ asset('base-baileys-mysql/bot.qr.png') }}" class="img-responsive" alt="">                            
                        </div>
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label for="">Telefono</label>
                                <input type="number" id="phone" class="form-control" value="59172823861">
                            </div>
                            <div class="form-group">
                                <label for="">Mensaje</label>
                                <textarea rows="6" id="message" class="form-control">Mensaje de prueba</textarea>
                            </div>
                            <a href="#" onclick="misend()"  class="btn btn-dark">Enviar mensaje (testing)</a>
                        </div>
                        <div class="col-md-12">
                            <h2>Historial del bot</h2>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <td>#</td> 
                                        <td>Tipo</td>                             
                                        <td>Mensaje</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mihistorias as $item)                                          
                                        <tr>
                                            <td>
                                                ID: {{ $item->id }}
                                                <br>
                                                {{ $item->created_at }}
                                            </td>
                                            <td>
                                                @if ($item->options == "{}")
                                                    user
                                                @else
                                                    bot
                                                @endif
                                            </td>
                                            <td>
                                                <strong>Phone: {{ $item->phone }}</strong>
                                                <br>
                                                {{ $item->answer }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
       
            </div>
          <div role="tabpanel" class="tab-pane" id="historial">
            <div class="row">                
                <div class="col-xs-12">
                    <h3>Flujo botcenter</h3>
                    {{ menu('Flujo defecto') }}
                </div>
                <div class="col-xs-12">
                    <h3>Flujo prestamos</h3>
                    {{ menu('Flujo prestamos') }}
                </div>
            </div>

          </div>
          <div role="tabpanel" class="tab-pane" id="leads">
            <h2>Historial del bot (recibidos)</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>#</td>    
                        <td>Cateroria</td>                    
                        <td>Mensaje</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($miledas as $item)                                          
                        <tr>
                            <td>
                                ID: {{ $item->id }}
                                <br>
                                {{ $item->created_at }}
                            </td>
                            <td>
                                <p>Phone: {{ $item->phone }}</p>
                                {{ $item->categoria }}
                            </td>
                            <td>
                                {{ $item->message }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div role="tabpanel" class="tab-pane" id="masivo">
     
            <div class="row">
                <div class="form-group">
                    <h2> en desarrollo</h2>
                </div>
            </div>
          </div>
        </div>
    </div>


</div>

@stop

@section('javascript')

<script>
    async function misend() {
        try {            
            var miurl = "{{ env('CB_URL').'/send' }}"
            var midata = {
                phone: $("#phone").val(),
                message: $("#message").val()
            }
            var midata = await axios.post(miurl, midata)
                .catch(function (error) {
                    console.log(error.message);
                    if (error.message) {

                        toastr.error("Error en el chatbot, escanea el QR")
                    }else{
                        toastr.info("mensaje enviado...")
                    }                    
                })

        } catch (error) {
            // console.log(error)        
        }
    }
</script>
@stop