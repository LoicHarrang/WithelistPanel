@extends('layouts.dash')

@section('title', 'Entretien')

@section('content')
    <div class="container" id="app">
        <br>
        <div v-if="!accepted">
            <div class="card-panel">
                <h5>Début de l'entretien</h5>
                <p>Afin de <b>conclure</b> la procédure d'<b>inscription</b>, vous aller passer un court <b>entretien</b> avec un membre de nos équipes.<br>
                N'appuyez <b>pas</b> sur le bouton si vous n'en avez pas été <b>averti oralement</b> par l'intervenant.</p>
                <p><small>En cliquant sur continuer, vous acceptez le fait que la décision prise à la suite de votre entretien est <b>définitive</b> et n'est <b>pas discutable</b>.</small></p>
                <a href="#" @click.prevent="accepted = true" class="btn blue waves-effect">Continuer</a>
            </div>
        </div>

        <div v-if="accepted" v-cloak>
            <h5>Validation de votre dossier - Entretien</h5>
            <p>Vous êtes en entretien avec {{ Auth::user()->username($exam->interviewer) }}.</p>
            @if(is_null($exam->interview_code_at))
                <div class="card-panel">
                    <p>Début de votre entretien, vous êtes pris en charge par: {{ Auth::user()->username($exam->interviewer) }}.</p>
                    <p><b>Un code va vous être demandé, le voici:</b></p>
                </div>
                <div class="card-panel">
                    <code class="flow-text">{{ $exam->interview_code }}</code>
                    <a class="btn-flat blue-text waves-effect clickable copy tooltip" data-tooltip="Copier" data-clipboard-text="{{ $exam->interview_code }}" onclick="Materialize.toast('Copié',  3000)"><i class="material-icons left">content_copy</i>Copier</a>
                </div>
            @else
                <div class="card-panel">
                    <p><b>Vous êtes actuellement en entretien.</b></p>
                    <p>Votre code a été validé.</p>
                </div>
            @endif
            <p class="smallprint">
                <small>
                    <b>Avis sur la confidentialité:</b><br>
                    Nous vous rappelons que, dans le but d'améliorer la qualité du roleplay sur le serveur, il est interdit de divulger toute information par
                    rapport à cet entretien.<br>
                    Nous nous réservons le droit de sanctionner chaque manquement à cette confidentialité.
                </small>
            </p>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                accepted: false,
            }
        });
    </script>
@endsection
