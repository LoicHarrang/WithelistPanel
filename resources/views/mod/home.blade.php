@extends('layouts.dash')

@section('title', 'Accueil')

@section('content')

<br>

<div class="container" id="app">
    <div class="row">
        <div class="col s12 m4">
            <div class="card-panel teaser-rouge white-text"> 
                <small class="white-text">ADMIN</small><br><br>
                <span class="flow-text white-text"> <b>{{ $user->username($user) }}</b></span><br>
                <span class="white-text">
                	@foreach($user->roles as $role) 
                		{{ $role->display_name }} 
                	@endforeach 
                </span>
            </div>
        </div>
        @permission('user-abilities-view')
        <div class="col s12 m8">
            <div class="row">
                <div class="col s12 l6">
                    <div class="card-panel">
                        <center><img src="https://cdn.discordapp.com/attachments/524207659759435800/638413414699630602/icon_ope.png" height="50" alt="">
                        	<br>
                        	<span class="black-text">Opérateur</span>
                            <br><br>
                            <a href="{{ route('mod-operateur-dashboard') }}" class="btn-flat blue-text waves-effect">Inscriptions</a>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endpermission
</div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                opening: moment.tz(new Date('{{ $opening }}'), '{{ config('app.timezone') }}'),
                timezone: '{{ Auth::user()->timezone }}'
            }
        });
    </script>
@endsection