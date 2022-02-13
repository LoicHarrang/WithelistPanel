@extends('layouts.dash')

@section('title', 'Sanction #'.$sanction->id)

@section('content')
    @include('mod.support.menu')

    <div class="container" id="app">
        <br>
        <small><a href="{{ route('sanctions.index') }}"><< liste des sanctions</a></small>
        <h5>Sanction #{{ $sanction->id }}</h5>
        @include('common.errors')
        <div class="row">
            <div class="col s12 l4">
                <p>Informations</p>
                <div class="card-panel">
                    <p>
                        <small>Joueur:</small>
                        <br>{{ $user->username($user) }}
                    </p>
                    <p>
                        <small>SteamID:</small>
                        <br><span class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{ $user->steamid }}" onclick="Materialize.toast('Copié',  3000)">{{ $user->steamid }} <i class="mdi mdi-content-copy tiny blue-text"></i></span>
                    </p>
                    <p>
                        <small>GUID:</small>
                        <br><small class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{ $user->guid }}" onclick="Materialize.toast('Copié',  3000)">{{ $user->guid }} <i class="mdi mdi-content-copy tiny blue-text"></i></small>
                    </p>
                    @if ($sanction->active != null)
                    <p>
                        <small>Actions:</small>
                        <br>
                        <form onsubmit="return confirm('Relever la sanction ? ATTENTION CECI EST IREVERSSIBLE')" action="{{ route('mod-sup-sanction-disable', $sanction->id) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="userid" value="{{ $user->id }}">
                        <button type="submit" class="btn white waves-effect red-text"><i class="material-icons left">block</i> Lever la sanction</button>
                        </form>
                    </p>
                    @endif
                </div>
            </div>
            <div class="col s12 l8">
                <p><i class="mdi mdi-receipt"></i> Sanction</p>
                    <div class="card-panel">
                        <div class="row">
                            <div class="col s12">
                                @if ($sanction->active == null)
                                    <div class="alert alert-danger">
                                        Cette sanction n'est plus en vigueur
                                    </div>
                                @endif
                                <p>
                                    <small>Statut:</small>
                                    <br>
                                    @if($sanction->type == 1)
                                        <i class="mdi mdi-gavel red-text"></i> <b>Bannissement</b>, @if ($sanction->active == null) Révolu @else fin le @if ($sanction->perm == -1) Permanant @else {{ $sanction->end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }} @endif @endif
                                    @else
                                        <i class="mdi mdi-alert orange-text"></i> <b>Avertissement</b>, @if ($sanction->active == null) Révolu @else fin le @if ($sanction->perm == -1) Permanant @else {{ $sanction->end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }} @endif @endif
                                    @endif
                                </p>
                            </div>
                            <br>
                            <div class="col s12">
                                <small>Raison de la sanction:</small>
                            </div>
                            <div class="col s12">
                                <code class="text-justify">
                                    {{ $sanction->reason }}
                                </code>
                            </div>
                            <br>
                            <div class="col s12">
                                <small>Sanctionné par:</small>
                            </div>
                            <div class="col s12">
                                <code class="text-justify">
                                    {{ Auth::user()->username(Auth::user()->getOtherUserInfos($sanction->sanct_by)) }}
                                </code>
                            </div>
                            <br>
                            <div class="col s12">
                                <small>Dates clés:</small>
                            </div>
                            <div class="col s12">
                                    <ol>
                                        <small>Mise de la sanction:</small><br>
                                        <ol>
                                            <code>{{ $sanction->active_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }}</code><br>
                                        </ol>
                                        <small>Fin de la sanction:</small>
                                        <br>
                                        @if ($sanction->perm == -1)
                                            <ol>
                                                <code>Jamais</code>
                                            </ol>
                                        @else
                                            <ol>
                                                <code>{{ $sanction->end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }}</code>
                                            </ol>
                                        @endif
                                    </ol>
                            </div>
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
