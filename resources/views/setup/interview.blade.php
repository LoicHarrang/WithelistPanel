@extends('layouts.dash')

@section('title', 'Entretien')

@section('content')
    <div id="app">
        <div class="container">
            <br>
            @include('setup.breadcrumb')
            <div>
                <br>
                <h5>Félicitations, vous avez terminé votre inscription !</h5>
                <p>Vous avez désormais la possibilité de participer à l'entretien.<br>Merci de suivre ces instructions pour finaliser votre dossier.</p>
                <div class="card-panel">
                    <b>Règlement</b>
                    <p>Avant de continuer, nous vous conseillons de bien (re)lire le règlement.</p>
                    <a href="{{ route('setup-rules') }}" class="btn white blue-text waves-effect"><i class="material-icons left">navigate_before</i> Voir le règlement</a>
                </div>
                <div class="card-panel">
                    <b>1. Si vous ne possédez pas Teamspeak, téléchargez le et installez le</b>
                    <p>Téléchargez le logiciel de communication vocale Teamspeak 3 depuis son site officiel et installez-le...</p>
                    <a href="https://teamspeak.com/fr/downloads/" class="btn white blue-text waves-effect">Ouvrir la page de téléchargement <i class="material-icons right">open_in_browser</i></a>
                </div>
                <div class="card-panel">
                    <b>2. Accéder à notre serveur</b>
                    <p>Connectez-vous à notre serveur vocale, qui sera l'endroit où vous passerez l'entretien.</p>
                    <a href="{{ config('exam.ts_link') }}" class="btn white blue-text waves-effect">Connexion au serveur <i class="material-icons right">call_made</i></a>
                    <br>
                    <br>
                    <small>
                        Si le bouton ne fonctionne pas, entrer l'adresse suivante sur le logiciel:
                        <br>
                        <code>ts.arma3frontiere.fr</code>
                    </small>
                </div>
                <div class="card-panel">
                    <b>3. Changer votre identité</b>
                    <p>Localisez-vous. Votre nom sera <b>mis en gras</b>.</p>
                    <p>Double-cliquez sur votre nom, et remplacez-le par l'identité exacte que vous nous avez renseigné.</p>
                </div>
                <div class="card-panel">
                    <b>4. Accéder à la salle d'attente</b>
                    <p>Localiser le channel suivant : Entretiens->"Salle d'attente".</p>
                    <p>Double-cliquez dessus pour rejoindre le canal.</p>
                    <small>Il est possible que vous soyez déjà dans ce canal si vous avez utilisé le bouton ci-dessus.</small>
                </div>
                <div class="card-panel">
                    <b>5. Un membre de nos équipes vous déplacera pour commencer l'entretien</b><br>
                    <small>Si personne n'est disponible, merci d'agir en conséquence et de prendre votre mal en patience.</small>
                </div>
                <div class="card-panel">
                    <b>6. Validation de votre dossier</b>
                    <p>S'il n'y a pas de problème pendant l'entretien, vous aurez terminé votre dossier d'inscription.</p>
                    <p>Il ne vous restera plus qu'à rejoindre le serveur pour commencer votre aventure !</p>
                </div>
            </div>
        </div>
    </div>
@endsection