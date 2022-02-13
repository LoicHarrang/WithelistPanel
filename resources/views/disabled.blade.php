<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.min.css"  media="screen,projection"/>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
        </div>
    </nav>
</div>
<div class="parallax-container" style="background-color: rgba(0, 0, 0, 0.85); box-shadow: 0 0 200px rgba(0,0,0,0.9) inset;">
    <div class="container">
        <br><br>
        <img src="/img/logo_accueil.png" style="filter: grayscale(100%);width: auto; height: 50px;" alt="">
        <p class="white-text flow-text"><b>Impossible de vous connecter</b><br><br>
        @if(!is_null($reason))
            {{ $reason }}
        @endif
        </p>
        <a href="https://arma3frontiere.fr" class="btn blue waves-effect"><i class="mdi mdi-arrow-left-bold"></i> RETOUR EN LIEU SUR</a>
    </div>
    <div class="parallax"><img src="/img/hero-background.jpg"></div>
</div>
<br>
<div class="container white-text">
    <h5 class="white-text">> Question Fréquente</h5>
    <br>
    <span>Quand pourrais-je utiliser le Dashboard ?</span>
    <p>
        Le serveur <b>ouvrira</b> ses portes en <b>bêta</b> ouvert le <b>28 août 2020</b>.<br>
        Dès le <b>14 août</b>, vous pourrez vous <b>inscrire</b> sur notre <b>dashboard</b> pour être <b>prêt</b> le jour de l'<b>ouverture</b><br>
        <small>N’hésitez pas à nous suivre sur <a href="https://discord.gg/QhEvfPB" target="_blank">Discord</a> pour vous tenir au courant des nouveautés.</small>
    </p>
    <br>


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
