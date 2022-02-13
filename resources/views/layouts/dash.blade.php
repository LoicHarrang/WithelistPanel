@php
    $notifications = auth()->user()->notifications;
    $unreadNotifications = $notifications->where('read_at', null);
    $readNotifications = $notifications->where('read_at', '!=' , null);

	if(!is_null(Auth::user()->country)){
	    $country = Countries::where('cca2', Auth::user()->country)->first();
	    $countryName = "?";
	    try {
	        $countryName = $country->translations->fra->common;
	    } catch(\Exception $e) {
	        // :)
	    }
	}
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link type="text/css" rel="stylesheet" href="{{ mix('/css/app.css') }}"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/img/favicon.ico">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="theme-color" content="#000000">
    <script src="https://kit.fontawesome.com/a328b94fcd.js" crossorigin="anonymous"></script>
    @yield('head')
</head>
<body style="background-color: white;">
<div class="navbar-fixed">
    <nav class="back-gd-1 white-text navbar-main">
        <div class="nav-wrapper container">
            <div class="nav-wrapper">
                <a href="{{ route('home') }}" class="brand-logo">
                    <img src="/img/logo.png" style="height: 64px;width: auto; margin: auto;">
                </a>
                @if($unreadNotifications->count() > 0)
                    <a href="#"  data-activates="slide-out" class="blue-text button-collapse"><i class="material-icons mdl-badge" data-badge="1">menu</i></a>
                @else
                    <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
                @endif
                <ul class="right hide-on-med-and-down">
                    <!--<li>
                        <a class="waves-effect waves-light" href="https://arma3frontiere.fr/forum"><i class="mdi mdi-forum left"></i> Forum</a>
                    </li>-->
            @if($unreadNotifications->count() > 0)
                    <li >
                        <a href="#modal-notifications" class="white-text waves-effect waves-light modal-trigger">
                            <i class="material-icons white-text text-accent-4 pulse left">notifications_active</i> <span class="new badge white black-text" data-badge-caption="non lue">{{ $unreadNotifications->count() }}</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="#modal-notifications" class="white-text waves-effect waves-light modal-trigger">
                            <i class="material-icons">notifications_none</i>
                        </a>
                    </li>
                @endif
                <li><a class="dropdown-button-extend" class="waves-effect" data-activates='dropdown-navbar'>@if(!is_null(Auth::user()->country)){!! $country->flag['flag-icon'] !!}@endif {{ Auth::user()->username(Auth::user()) }} <i class="material-icons right">arrow_drop_down</i></a></li>
            </ul>
            </div>
        </div>
    </nav>
</div>

<ul id="slide-out" class="side-nav">
    <li><div class="user-view">
            <img src="/img/logo.png" height="35">
            <a href="#!name"><span class="white-text name">@if(!is_null(Auth::user()->country)){!! $country->flag['flag-icon'] !!}@endif {{ Auth::user()->username(Auth::user()) }}</span></a>
            <br>
        </div></li>
    <li><a href="{{ route('home') }}" class="waves-effect"><i class="material-icons">home</i>Début</a></li>
    <li><div class="divider"></div></li>
    @if($unreadNotifications->count() > 0)
        <li >
            <a href="#modal-notifications" class="waves-effect waves-light modal-trigger">
                <i class="material-icons blue-text pulse left">notifications_active</i> {{ $unreadNotifications->count() }}
            </a>
        </li>
    @else
        <li>
            <a href="#modal-notifications" class="waves-effect waves-light modal-trigger">
                <i class="material-icons">notifications_none</i>
            </a>
        </li>
    @endif
    <li><div class="divider"></div></li>
    @permission('mod*')
    <li><a href="{{ route('mod-dashboard') }}" class="waves-effect back-gd-text-2"><i class="material-icons left">folder_open</i> ADMIN</a></li>
    @endpermission
    @if(Auth::user()->isAdmin())
        <li><a href="{{ route('acl-users') }}" class="waves-effect back-gd-text-2"><i class="material-icons left">vpn_key</i> WEBMASTER</a></li>
    @endif
    <li><a class="waves-effect" href="{{ route('logout') }}" class="waves-effect back-gd-text-2">Déconnexion</a></li>
</ul>

<!-- Notificaciones -->
<div id="modal-notifications" class="modal">
    <div class="modal-content">
        <h5>Notifications</h5>
        @if($notifications->count() == 0)
            <p>Vous n'avez pas de notification pour le moment. Nous vous ferons savoir quand vous avez une notification:</p>

            <nav class="black white-text">
                <div class="nav-wrapper">
                    <ul class="right">
                        <li>
                            <a class="white-text">
                                <i class="material-icons white-text text-accent-4 pulse left">notifications_active</i> <span class="new badge white black-text" data-badge-caption="non lue">?</span>
                            </a>
                        </li>
                        <li>
                            <a>
                                @if(!is_null(Auth::user()->country)){!! $country->flag['flag-icon'] !!}@endif {{ Auth::user()->username(Auth::user()) }} <i class="material-icons right">arrow_drop_down</i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        @endif
        @if($unreadNotifications->count() > 0)
            <p>Notification non lue</p>
            <ul class="collection white black-text">
                @foreach ($unreadNotifications as $notification)
                    @include('common.notification')
                @endforeach
            </ul>
            <form method="POST" action="{{ route('notifications-allread')  }}">
                {{ csrf_field()  }}
                <button type="submit" class="btn blue waves-effect"><i class="material-icons left">done_all</i> Marquer les notifications comme lue</button>
            </form>
        @endif
        @if($readNotifications->count() > 0)
            <p>10 dernières notifications</p>
            <ul class="collection white black-text">
                @foreach ($readNotifications->take(10) as $notification)
                    @include('common.notification')
                @endforeach
            </ul>
        @endif
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat">Fermer</a>
    </div>
</div>

<!-- Dropdown navbar -->
<ul id='dropdown-navbar' class='dropdown-content'>
    @permission('mod*')
    <li><a href="{{ route('mod-dashboard') }}"><i class="material-icons left">folder_open</i> ADMIN</a></li>
    @endpermission
    @if(Auth::user()->isAdmin())
        <li><a href="{{ route('acl-users') }}"><i class="material-icons left">vpn_key</i> MASTER</a></li>
    @endif
    <li class="divider"></li>
    <li><a href="{{ route('logout') }}">Déconnexion</a></li>
</ul>

@yield('content')

<div class="container">
    <small>
        <br>
        <span class="blue-grey-text text-darken-2">
            &copy; {{ config('dash.start_year') }}<script>new Date().getFullYear()>{{ config('dash.start_year') }}&&document.write("-"+new Date().getFullYear());</script>
            {{ config('app.name') }} -
        </span>
        <a href="{{ config('dash.url_tos') }}" class="grey-text text-darken-1">Termes et Conditions</a>
        <span class="grey-text text-darken-2">-</span>
        <a href="{{ config('dash.url_privacy') }}" class="grey-text text-darken-1">Politique de Confidentialité</a>
        <br>
        <a class="grey-text text-lighten-1" href="https://arma3frontiere.fr">A3F <i class="mdi mdi-open-in-new"></i></a>
        <br>
        <br>
    </small>
</div>
<script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
@if(\Illuminate\Support\Facades\Session::has('status'))
    <script>
        Materialize.toast('{{ \Illuminate\Support\Facades\Session::get('status') }}', 4000);
    </script>
@endif
@yield('js')
</body>
</html>
