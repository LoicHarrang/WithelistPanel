<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.min.css"  media="screen,projection"/>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css"  media="screen,projection"/>
    <style>
        body {
            padding-top: 24px;
        }
    </style>
    <title>Erreur 403 - {{ config('app.name') }}</title>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body class="">

<div class="container">
    <div class="row">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel">
                <h5>Erreur 403</h5>
                <p>Autorisation insuffisante.</p>
                <div class="row">
                    <div class="col s12">
                        <a href="{{ url('/') }}" class="btn green waves-effect">Retour</a>
                    </div>
                </div>
            </div>
            <small>&copy; {{ config('pcu.start_year') }}<script>new Date().getFullYear()>{{ config('pcu.start_year') }}&&document.write("-"+new Date().getFullYear());</script>
                {{ config('app.name') }}</small>
        </div>
    </div>
</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/js/materialize.min.js"></script>
</body>
</html>
