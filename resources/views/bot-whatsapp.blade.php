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
@endphp
<div class="container-fluid">

    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#estado" aria-controls="eastado" role="tab" data-toggle="tab">Inicio</a></li>

          <li role="presentation"><a href="#historial" aria-controls="historial" role="tab" data-toggle="tab">Historial</a></li>
          <li role="presentation"><a href="#flujos" aria-controls="flujos" role="tab" data-toggle="tab">Flujos</a></li>
          <li role="presentation"><a href="#conf" aria-controls="conf" role="tab" data-toggle="tab">Configuracion</a></li>
        </ul>
      
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="estado">
                <h3>Escanea la imagen</h3>
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <img src="{{ asset('base-baileys-mysql/bot.qr.png') }}" class="img-responsive" alt="">
                </div>
            </div>
          <div role="tabpanel" class="tab-pane" id="historial">
            <div class="row">
                
                <div class="col-md-12">
                    <h3>Historial de chats</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td>#</td>                        
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
          <div role="tabpanel" class="tab-pane" id="flujos">
            <h3>Flujo del Bot</h3>
            {{ menu('whatsapp') }}
          </div>
          <div role="tabpanel" class="tab-pane" id="conf">
     
          </div>
        </div>
      
      </div>


</div>

@stop