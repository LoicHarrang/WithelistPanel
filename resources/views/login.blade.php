<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.min.css"  media="screen,projection"/>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">
     <link rel="icon" type="image/png" href="/img/favicon.ico">
    <meta name="msapplication-TileColor" content="#000000">
    {{--<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">--}}
    <meta name="theme-color" content="#000000">    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        .heading-parallax {
            text-shadow: 0px 0px 5px rgba(150, 150, 150, 1);
        }
    </style>
</head>
<body class="black">
<div class="navbar-fixed">
    <nav class="black">
        <div class="nav-wrapper container">
            <ul class="hide-on-med-and-down">
            </ul>
            <ul class="right hide-on-med-and-down">
                <li><a @if(session('status')) disabled @endif href="{{ route('login') }}" class="btn-flat white-text waves-effect waves-blue"><i class="mdi mdi-steam left" style="height: inherit; line-height: inherit;"></i> CONTINUER AVEC STEAM</a></li>
            </ul>
        </div>
    </nav>
</div>
<div class="parallax-container" style="background-color: rgba(0, 0, 0, 0.6); box-shadow: 0 0 200px rgba(0,0,0,0.9) inset;">
    <div class="container">
        <br><br>
        <img src="/img/logo_accueil.png" alt="" style="width: auto; height: 50px;">
        <p class="white-text flow-text">Bienvenue sur le dashboard d'Arma 3 Frontière</p>
        <br>
        <a href="{{ route('login') }}" class="btn blue waves-effect"><i class="mdi mdi-steam left"></i> CONTINUER AVEC STEAM</a>
        <br>
        <small class="white-text">
            {!! __('misc.login.hero.legal', ['terms' => config('dash.url_tos'), 'privacy' => config('dash.url_privacy')]) !!}
            <br>
        </small>
    </div>
    <div class="parallax"><img src="/img/hero-background.jpg"></div>
</div>
<br>
<div class="container">
    <small>
        <br>
        <span class="grey-text text-lighten-3">
            &copy; {{ config('dash.start_year') }}<script>new Date().getFullYear()>{{ config('dash.start_year') }}&&document.write("-"+new Date().getFullYear());</script>
            {{ config('app.name') }}
        </span>
        <!--<a href="{{ config('dash.url_tos') }}" class="grey-text text-lighten-2">Termes et Conditions</a>
        <span class="grey-text text-lighten-4">-</span>
        <a href="{{ config('dash.url_privacy') }}" class="grey-text text-lighten-4">Politique de Confidentialité</a>-->
        <br>
        <a class="grey-text text-lighten-1" href="https://arma3frontiere.fr">A3F <i class="mdi mdi-open-in-new"></i></a>
        <br>
        <br>
    </small>
</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/js/materialize.min.js"></script>
<script>
    $(document).ready(function(){
        $('.parallax').parallax();
    });
</script>
</body>
</html>
