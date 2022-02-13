@extends('layouts.dash')

@section('content')
    <div class="container">
        <br>
        <br>
        <h5><b>Merci de votre participation !</b></h5>
        <p>Vous avez atteint la dernière étape de notre programme bêta ! (nous sommes en train de developper le reste du panel)</p>
        <div class="row">
            <div class="col s12 m9 l10">
                <div class="card-panel">
                    <div id="conceptos" class="section scrollspy">
                        <p>1. <b>Où en est le développement ?</b></p>
                        <div class="card-panel">
                            <p>Actuellement, nous sommes en train de développer la partie joueur du panel.<br> Alors prenez votre mal en patience et laissez nous vous surprendre !</p>
                        </div>
                    </div>
                        <p>2. <b>Quel est votre rôle à présent ?</b></p>
                        <div class="card-panel">
                            <div id="personaje" class="section scrollspy">
                            <p>Votre rôle est maintenant de nous informer de votre éxperience.<br>En effet, si vous avez rencontré des bugs, des incompréhension ou si vous avez une critique capable d'améliorer le panel, n'hésitez pas à nous le remonter sur discord.
                        </div>
                    </div>
                        <p>3. <b>Que faut-il savoir ?</b></p>
                        <div class="card-panel">
                            <div id="roles" class="section scrollspy">
                            <p>Nous sommes ammené à modifier régulièrement le panel.</p>
                            <p>N'hésitez pas à venir voir cette page de temps en temps, afin de tester les nouveautés !</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection