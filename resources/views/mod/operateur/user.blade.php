@extends('layouts.dash')

@section('title', '"' . Auth::user()->username($user) .'"')

@section('content')
    @include('mod.operateur.menu')

    <div class="container" id="app">
        <br>
        <small><a href="{{ route('mod-users') }}"><< liste des joueurs</a></small>
        <h5><span class="copy tooltipped clickable" data-tooltip="Copier" data-clipboard-text="{{ Auth::user()->username($user) }}" onclick="Materialize.toast('Copié',  3000)">{{ Auth::user()->username($user) }} <i class="mdi mdi-content-copy tiny blue-text"></i></span></h5>
        @include('common.errors')
        <div class="row">
            <div class="col s12 l4">
                <p>Informations</p>
                <div class="card-panel">
                    <p>
                        <small>SteamID:</small>
                        <br><span class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{ $user->steamid }}" onclick="Materialize.toast('Copié',  3000)">{{ $user->steamid }} <i class="mdi mdi-content-copy tiny blue-text"></i></span>
                    </p>
                    <p>
                        <small>GUID:</small>
                        <br><small class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{ $user->guid }}" onclick="Materialize.toast('Copié',  3000)">{{ $user->guid }} <i class="mdi mdi-content-copy tiny blue-text"></i></small>
                    </p>
                    @permission('mod-reveal-email')
                    <p>
                        <small>Adresse Email:</small>
                        <br>

                        @if(is_null($user->email))
                            <i>-</i>
                        @else
                            <reveal endpoint="{{ route('mod-reveal-email', $user) }}"></reveal>
                        @endif
                    </p>
                    @endpermission
                    <p>
                        <small>Type de compte:</small>
                        <br>
                        @if($user->imported)
                            @if(!$user->imported_exam_exempt)
                                Importé
                            @else
                                <i class="mdi mdi-exit-to-app tiny"></i> Bypass
                            @endif
                        @else
                            Normal
                        @endif
                    </p>
                </div>
                @if($user->disabled)
                    <div class="card-panel">
                        <b>Utilisateur désactivé</b>
                        <br><small>Aucune connection a son compte possible.</small>
                        <p>
                            <small>Motif:</small>
                            <br><span>{{ $user->disabled_reason or "-" }}</span>
                        </p>
                        <p>
                            <small>Date de désactivation:</small>
                            <br><span>{{ $user->disabled_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }}</span>
                        </p>
                    </div>
                @endif
            </div>
            <div class="col s12 l8">
                <p><i class="mdi mdi-passport"></i> Inscription</p>
                @if($user->hasFinishedSetup())
                    <div class="card-panel">
                        <b><i class="mdi mdi-approval tiny green-text"></i> Inscrit</b>
                    </div>
                @else
                    <div class="card-panel">
                        @if($user->getSetupStep() == 0)
                            Vérification du jeu
                        @elseif($user->getSetupStep() == 1)
                            Informations
                        @elseif($user->getSetupStep() == 2)
                            Moyen de contact
                        @elseif($user->getSetupStep() == 3)
                            Identité
                        @elseif($user->getSetupStep() == 4)
                            Règlement
                        @elseif($user->getSetupStep() == 5)
                            Examen
                        @elseif($user->getSetupStep() == 6)
                            Enlace foro
                        @elseif($user->getSetupStep() == 7)
                            Entretien
                        @else
                            ?
                        @endif
                            ({{ $user->getSetupStep() }}/{{$user->getSetupSteps()}})
                        <div class="progress">
                            <div class="determinate" style="width: {{ round(($user->getSetupStep() / $user->getSetupSteps()) * 100) }}%"></div>
                        </div>

                        @if($user->getSetupStep() == 0)
                            {{--<a href="" class="btn white blue-text">Marquer le jeu comme vérifié</a>--}}
                        @elseif($user->getSetupStep() == 5)
                            {{--Examen--}}
                        @elseif($user->getSetupStep() == 7)
                            @if(is_null($user->getInterviewExam()->interviewer))
                                <p>Prêt pour entretien</p>
                                @permission('mod-interview')
                                    <form action="{{ route('mod-interview', $user->getInterviewExam()) }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn white blue-text waves-effect">Commencer l'entretien</button>
                                    </form>
                                @endpermission
                            @else
                                @if($user->getInterviewExam()->interviewer->is(Auth::user()))
                                <p>En cours d'entretien.</p>
                                <a href="{{ route('mod-interview', $user->getInterviewExam()) }}" class="btn indigo white-text waves-effect">Continuer l'entretien</a>
                                @else
                                <span><b>{{ $user->getInterviewExam()->interviewer->username }} s'occupe de l'entretien.</b></span>
                                @endif
                            @endif
                        @endif
                    </div>
                @endif
                <p><i class="mdi mdi-account-card-details"></i> Identité(s)</p>
                <div class="card-panel">
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Obs.</th>
                            <th>Etat</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($user->names()->orderBy('created_at', 'desc')->get() as $name)
                                    <tr>
                                        <td>
                                            @php
                                                $nameUser = Auth::user()->getOtherUserInfos($name->user_id);
                                            @endphp
                                            @if(!is_null($nameUser->country))
                                                @php
                                                    $country = Countries::where('cca2', $nameUser->country)->first();
                                                    $countryName = "?";
                                                    try {
                                                        $countryName = $country->translations->fra->official;
                                                    } catch(\Exception $e) {
                                                        // :)
                                                    }
                                                @endphp
                                                {!! $country->flag['flag-icon'] !!} 
                                            @endif
                                            {{ $name->name }}
                                        </td>
                                        <td>
                                            @if(!is_null($name->original_name) && $name->name != $name->original_name)
                                                <i class="mdi mdi-pencil tiny tooltipped" data-tooltip="{{ $name->original_name }} → {{ $name->name }}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($name->invalid)
                                                <span class="red-text"><i class="mdi mdi-account-card-details"></i> Non Valide</span>
                                            @else
                                                @if($name->needs_review)
                                                    <i class="mdi mdi-clock"> En attente de validation
                                                @else
                                                    @if(!is_null($name->end_at))
                                                        Fin {{ $name->end_at->diffForHumans() }}
                                                    @elseif(!is_null($name->active_at))
                                                        <b><i class="mdi mdi-check-circle tiny green-text"></i> Activé {{ $name->active_at->diffForHumans() }}</b>
                                                    @else
                                                        ?
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('names.show', $name)}}" class="btn-flat waves-effect"><i class="mdi mdi-eye"></i></a>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/js/countdown.min.js"></script>
<script src="/js/moment-countdown.min.js"></script>
<script src="/js/clipboard.min.js"></script>
<script type="text/x-template" id="template-reveal">
    <span>
        <span v-if="!loading && !error && !loaded">
            <a href="" @click.prevent="reveal()">Cliquer pour révéler...</a>
        </span>
        <span v-if="loading">
            <a>Chargement<span class="ellipsis-anim"><span>.</span><span>.</span><span>.</span></span></a>
        </span>
        <span v-if="!loading && error">
            <small class="red-text">@{{ errorMessage }}</small>
        </span>
        <span v-if="!loading && !error && loaded">
            @{{ info }}
            <span v-if="info == ''"><i>Erreur.</i></span>
        </span>
    </span>
</script>
<script>
    Vue.component('reveal', {
        template: '#template-reveal',
        props: {endpoint: {required: true}},
        data: function() {
            return {
                loading: false,
                loaded: false,
                error: false,
                info: null,
                errorMessage: "Les informations n'ont pas pu être obtenues.",
            }
        },
        methods: {
            reveal: function() {
                var vm = this;
                if(!confirm("Information personnel.\nVotre accès est-il necessaire? Vous serez enregistré.")) {
                    return false;
                }
                vm.loading = true;
                axios.post(vm.endpoint)
                .then(function(response) {
                    vm.loading = false;
                    vm.error = false;
                    vm.info = response.data;
                    vm.loaded = true;
                }).catch(function(error) {
                    vm.loading = false;
                    vm.error = true;
                    if(error.response.status === 403) {
                        vm.errorMessage = "Vous n'avez pas l'autorisation."
                    }
                });
            }
        }
    });

    var app = new Vue({
        el: '#app',
        containers: ['reveal']
    });
</script>
@endsection
@section('head')
    <style>

        .ellipsis-anim span {
            opacity: 0;
            -webkit-animation: ellipsis-dot 1s infinite;
            animation: ellipsis-dot 1s infinite;
        }

        .ellipsis-anim span:nth-child(1) {
            -webkit-animation-delay: 0.0s;
            animation-delay: 0.0s;
        }
        .ellipsis-anim span:nth-child(2) {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s;
        }
        .ellipsis-anim span:nth-child(3) {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
        }

        @-webkit-keyframes ellipsis-dot {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        @keyframes ellipsis-dot {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
@endsection
