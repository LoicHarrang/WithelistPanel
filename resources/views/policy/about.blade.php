<!DOCTYPE html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="{{ mix('/css/app.css') }}"  media="screen,projection"/>
    <title>A Propos de - PCU</title>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body class="">

@include('policy.menu')

<div class="container">
    <div class="row">
        <div class="col s12">
            <img src="/img/logopopsinmetro.png" height="50" alt="">
            <div class="card-panel">
                <h5>A Propos</h5>
                <p>Informations sur le panel.</p>
                <b>Version</b>
                <p>V1.0 <small>Build 0</small></p>
                <b>Características</b>
                <ul class="browser-default">
                    <li>Systeme d'utilisateur avec connection via Steam</li>
                    <li>
                        Systeme de withelist automatique
                        <ul class="browser-default">
                            <li>Système d'examens aléatoires et correctifs avec l'intervention d'un opérateur</li>
                        </ul>
                    </li>
                    <li>Système de nouvelles</li>
                    <li>Système de suivi et de notification de pages/catégories</li>
                </ul>
                <b>Crédits</b>
                <p>
                    <small>Auteurs:</small>
                    <br><a href="https://arma3frontiere.fr">Loic Shmit & Sharywan</a>
                </p>
                <b>Lisence</b>
                <p>Tous droits réservés. Il est interdit de reproduire ou de réutiliser ce panel pour toute raison autre que pour:</p>
                <p><code>Arma 3 Frontiere</code></p>
                <p>Pour plus d'informations sur la licence, contactez Loic Shmit.</p>
                <b>Technologie utilisée</b>
                <p>Cette page a été créée en utilisant <a href="https://laravel.com/">Laravel 5.5</a>,
                    <a href="https://github.com/Dogfalo/materialize">Materialize</a> y <a href="https://vuejs.org/">Vue</a>
                    <a href="https://www.jetbrains.com/phpstorm/">PhpStorm</a>.</p>
            </div>
            <small>&copy; Sharywam et Loic Shmit 2020</small>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
