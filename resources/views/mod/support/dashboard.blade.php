@extends('layouts.dash')

@section('title', 'Panel Support')

@php
$country_fr = Countries::where('cca2', 'FR')->first();
$country_be = Countries::where('cca2', 'BE')->first();
@endphp

@section('content')
    @include('mod.support.menu')
    <div class="container" id="app">
        <br>
        <img src="https://cdn.discordapp.com/attachments/524207659759435800/638413640587935784/icon_sup.png" height="50" alt="">
        <br><br>
        <div class="row">
            <div class="col s12">
                    <i class="material-icons tiny">list</i> Panel Support
                </div>
            <div class="col s12 m3">
                <div class="card-panel back-gd-2 white-text">
                    <h5 class="light"><b>Joueurs totale</b><br>542</h5>
                </div>
            </div>
            <div class="col s12 m3">
                <div class="card-panel back-gd-2 white-text">
                    <h5 class="light"><b>Joueurs {!! $country_fr->flag['flag-icon'] !!}</b><br>26</h5>
                </div>
            </div>
            <div class="col s12 m3">
                <div class="card-panel back-gd-2 white-text">
                    <h5 class="light"><b>Joueurs {!! $country_be->flag['flag-icon'] !!}</b><br>652</h5>
                </div>
            </div>
            <div class="col s12 m3">
                <div class="card-panel back-gd-2 white-text">
                    <h5 class="light"><b>Joueurs en faction</b><br>652</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m4">
                <p>Accès rapide</p>
                <div class="card-panel">
                    <a href="{{ route('mod-review') }}" class="btn back-gd-2 white-text waves-effect" style="margin-top: 8px !important;">SITE</a><br>
                    <a href="{{ route('mod-review') }}" class="btn back-gd-2 white-text waves-effect" style="margin-top: 8px !important;">FORUM</a><br>
                    <a href="{{ route('mod-review') }}" class="btn back-gd-2 white-text waves-effect" style="margin-top: 8px !important;">DASHBOARD</a><br>
                    <a href="{{ route('mod-review') }}" class="btn back-gd-2 white-text waves-effect" style="margin-top: 8px !important;">TEAMSPEAK</a><br>
                    <a href="{{ route('mod-review') }}" class="btn back-gd-2 white-text waves-effect" style="margin-top: 8px !important;">IP</a>
                </div>
            </div>
            <div class="col s12 m8">
                <p>Courbe de connexion</p>
                <div class="card-panel" style="max-height: 268px !important;">
                    {!! $chart->container() !!}
                </div>
            </div>                      
        </div>
    </div>
@endsection

@section('js')
<script src="https://unpkg.com/vue"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>


<script>
    var app = new Vue({
        el: '#app',
    });
</script>

{!! $chart->script() !!}
@endsection