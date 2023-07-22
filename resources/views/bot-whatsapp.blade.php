@extends('voyager::master')

@section('page_title', "Bot-whatsapp")

@section('page_header')

    <h1>
        <i class="voyager-helm"></i> Bot-whatsapp
    </h1>
@stop

@section('content')

@php
    $mihistorias = App\History::orderBy("created_at", "DESC")->limit(100)->get();
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <h3>Escanea la imagen</h3>
            <div class="panel panel-bordered" style="padding-bottom:5px;">
                <img src="{{ asset('base-baileys-mysql/bot.qr.png') }}" class="img-responsive" alt="">
                <h3>Flujo del Bot</h3>
                {{ menu('whatsapp') }}
            </div>
        </div>
        
        <div class="col-md-8">
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

@stop