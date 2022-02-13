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
                {{--<li><a href=""><b>A3F</b></a></li>--}}
            </ul>
        </div>
    </nav>
</div>
<div class="parallax-container" style="background-color: rgba(0, 0, 0, 0.85); box-shadow: 0 0 200px rgba(0,0,0,0.9) inset;">
    <div class="container">
        <br><br>
        <img src="/img/logo.png" style="filter: grayscale(100%);" alt="">
        <img src="/img/PEGI_16.svg" height="125px" alt="">
        <p class="white-text flow-text">Vous n'avez pas l'age necessaire pour nous rejoindre. <br> Pour jouer, vous devez avoir <b>16</b> ans.</p>
        <br>
        <img src="/img/pegi1.gif">
        <img src="/img/pegi2.gif">
        <img src="/img/pegi3.gif">
        <img src="/img/pegi4.gif">
        <img src="/img/pegi5.gif">
        <img src="/img/pegi6.gif">
    </div>
    <div class="parallax"><img src="/img/hero-background.png"></div>
</div>
<br>
<div class="container white-text">
    <h5 class="white-text">Questions suivantes</h5>
    <br>
    <span>> J'ai 16 ans dans très peu de jours. Je peux entrer maintenant ?</span>
    <p>Aucune exception n'est faite. Ne vous inquiétez pas si vous n'avez plus grand-chose, laissez de coté ArmA et venez quand vous aurez l'age, nous vous attendrons !</p>
    <br>
    <span>> Si je fais un don, puis-je venir en avance ?</span>
    <p>Les dons sont appréciés, mais ils ne vous permettront pas d'entrer avant l'âge de 16 ans.</p>
    <br>
    <span>> C'est fini. Je vais mentir pour commencer à jouer maintenant.</span>
    <p>Nous prenons la limite d'âge très au sérieux. Si on te surprend à jouer en dessous de 16 ans, tu ne pourras plus jamais revenir.</p>
    <br>
    <span>> J'ai 16 ans maintenant. Je peux jouer ?</span>
    <p>Vous devez remplir un formulaire pour faire appel d'une sanction dans le forum. Lien: <a href="{{ config('dash.url_forum') }}">Forum de {{ config('app.name') }}</a></p>
    <br>
    <p>Pour toute autre question, vous pouvez nous poser des questions sur TeamSpeak. <code>{{ config('exam.ts_address') }}</code></p>



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
