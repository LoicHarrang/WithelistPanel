@extends('layouts.dash')

@section('title', 'Panel Opérateur')

@section('content')
    @include('mod.operateur.menu')
    <div class="container">
        <br>
        <img src="https://cdn.discordapp.com/attachments/524207659759435800/638413414699630602/icon_ope.png" height="50" alt="">
        <br><br>
        @permission(['mod-review-names'])
            <div class="row">
                <div class="col s12">
                    <i class="material-icons tiny">list</i> Panel opérateur
                </div>
                <div class="col s12">
                    <div class="card-panel back-gd-2 white-text">
                        @php
                        $toReview = array("Identités");
                        @endphp
                        @foreach($toReview as $item)
                            {{ $item }}@if(!$loop->last), @endif
                        @endforeach
                        @if($count > 0)
                            <h5 class="light"><b>{{ $count }}</b> à vérifier</h5>
                            <a href="{{ route('mod-review') }}" class="btn white back-gd-text-2 waves-effect" style="margin-top: 8px">Vérifier</a>
                        @else
                            <h5 class="light">0 en attente</h5>
                            <a href="{{ route('mod-review') }}" class="btn back-gd-2 white-text waves-effect" style="margin-top: 8px">Vérifier</a>
                        @endif
                    </div>
                </div>
            </div>
        @endpermission
    </div>
@endsection
