@extends('layouts.dash')

@section('title', 'Vérification')

@section('content')
@include('mod.operateur.menu')
    <div id="app">
        <div class="container">
            <br>
            <h5>Vérification</h5>
            <div class="card-panel" v-if="loading && review === null">
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>
            </div>
            <div v-if="done" v-cloak>
                <p>Message du système</p>
                <div class="card-panel">
                    <div v-if="streak <= 5">
                        <b>Aucune vérification en attente.</b>
                        <p>Pour le moment, aucune vérification n'est necessaire.</p>
                    </div>
                    <div v-if="streak > 5">
                        <b>Vous avez terminé votre session...</b>
                        <p>Toutes les réponses disponibles ont été vérifié.</p>
                        <p><b>Total: @{{ streak }} (gg et merci pour tout !)</b></p>
                    </div>
                    <a href="{{ route('mod-operateur-dashboard') }}" class="btn white back-gd-text-2 waves-effect"><i class="material-icons left">arrow_back</i> Retour</a>
                </div>
            </div>
            <div v-cloak v-if="review != null">
                <div v-if="type === 'answer'">
                    <div class="chip">
                        <i class="chipicon material-icons">format_quote</i>
                        <b>Réponses</b>
                    </div>
                    <answer :answer="review" v-on:reviewed="next()"></answer>
                </div>
                <div v-if="type === 'name'">
                    <div class="chip">
                        <i class="chipicon material-icons">account_circle</i>
                        <b>Vérification d'identité</b>
                    </div>
                    <name :name="review" v-on:reviewed="next()"></name>
                </div>
            </div>
            {{-- Rachas --}}
            <div class="card-panel" v-cloak v-if="streak > 5">
                <p v-cloak class="orange-text" v-if="streak > 5 && streak < 15">
                    <i class="material-icons tiny">whatshot</i>  Streak de @{{ streak }} (petit joueur)
                </p>
                <p v-cloak class="red-text" v-if="streak >= 15 && streak < 30">
                    <i class="material-icons tiny">whatshot</i>  Streak de @{{ streak }} (pas mal)
                </p>
                <p v-cloak class="red-text" v-if="streak >= 30 && streak < 45">
                    <b><i class="material-icons tiny">whatshot</i>  Streak de @{{ streak }} (excellent !)</b>
                </p>
                <p v-cloak class="black-text" v-if="streak >= 45 && streak < 60">
                    <b><i class="material-icons tiny">whatshot</i>  Streak de @{{ streak }} (t'es chaud toi)</b>
                </p>
                <p v-cloak class="black-text" v-if="streak >= 60 && streak < 75">
                    <b><i class="material-icons tiny">whatshot</i><i class="material-icons tiny">whatshot</i>  Streak de @{{ streak }} (bon arette la laisse en aux potos)</b>
                </p>
                <p v-cloak class="black-text" v-if="streak >= 75 && streak < 100">
                    <b><i class="material-icons red-text tiny">whatshot</i><i class="material-icons orange-text tiny">whatshot</i><i class="material-icons yellow-text tiny">whatshot</i>  Streak de @{{ streak }} (...)</b>
                </p>
                <p v-cloak class="black white-text" v-if="streak >= 100">
                    <b><i class="material-icons red-text tiny">whatshot</i><i class="material-icons orange-text tiny">whatshot</i><i class="material-icons yellow-text tiny">whatshot</i>  Streak de @{{ streak }} (bon merci vous êtes viré)</b>
                </p>
            </div>
            <div v-cloak>
                <br>
                <small>Vérification:</small>
                <br>
                <small v-cloak v-for="(user, index) in presence" >@{{ user.username }}<span v-if="index != presence.length - 1">, </span> </small>
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
<script src="/js/estimate.min.js"></script>
<script type="text/x-template" id="answer-review-template">
    <div>
        <div v-if="!loading">
            <button @keyup.alt.65="review(100)" :disabled="timeout || loading || reporting" @click.prevent="review(100)" class="btn green waves-effect tooltipped" data-tooltip="Bien"><i class="material-icons">thumb_up</i></button>
            <button :disabled="timeout || loading || reporting" @click.prevent="review(0)" class="btn red waves-effect tooltipped" data-tooltip="Mal"><i class="material-icons">thumb_down</i></button>
            <!--<span v-if="hasAbuse">|</span>
            <button v-if="hasAbuse" :disabled="timeout || loading || reporting" @click.prevent="reporting = true" class="btn red waves-effect tooltipped" data-tooltip="Suspendre un examen"><i class="material-icons">block</i></button>-->
            <span v-if="timeout && timeoutUnlock">
                <br>
                <small><a href="#" @click.prevent="timeout = false">Prise de décision</a></small>
            </span>
            <div v-if="reporting" class="card-panel">
                <b>Report d'un comportement abusif</b>
                <br>
                <label for="">Motif</label>
                <select v-model="select" class="browser-default" name="" id="">
                    <option value="-1" disabled selected>Séléctionner un motif...</option>
                    <option>Spam</option>
                    <option>Insulte sur correcteur</option>
                    <option>Copie/Plagiat</option>
                    <option>Tentative d'utilisation de bug</option>
                    <option value="100">Autre (veuillez spécifier)</option>
                </select>
                <div style="margin-top: 16px">
                    <textarea required v-if="select == 100" data-length="200" v-model="abuseMessage" class="materialize-textarea" placeholder="Incluye más información..." name="" id="" cols="30" rows="10"></textarea>
                    <button @click.prevent="abuse()" :disabled="loading || select == -1" class="btn red white-text waves-effect"><i class="material-icons left">block</i>Valider</button>
                    <button :disabled="loading" class="btn-flat waves-effect" @click.precent="reporting = false; abuseMessage = null; select = -1">Annuler</button>
                </div>
            </div>
        </div>
        <div>
            <div v-if="loading">
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>
            </div>
        </div>
        <!-- Modal Structure -->
        <div id="modal-abuse" class="modal">
            <div class="modal-content">
                <h4>Suspendre l'examen et reporter un comportement abusif</h4>
                <p>Si une réponse enfreint les règles, veuillez la marquer comme abusive.</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="answer-template">
    <div class="card-panel">
        <div class="row">
            <div class="col s12 m6">
                <span v-if="answer.question.question != undefined">
                <b>@{{ answer.question.question }}</b>
                </span>
                <span v-if="answer.answer != undefined">
                <br>
                    <p>"<span v-html="answer.answer"></span>"</p>
                </span>
            </div>
            <div class="col s12 m6">
                <answer-review :data-provided="answer.answer" v-on:reviewed="itemReviewed($event)" type="answer" :has-abuse="true" :simple="false" :answer-id="answer.id"></answer-review>
                <p v-if="answer.question.staff_message != null">
                    <i class="mdi mdi-information"></i> Opérateur:
                    <br>
                    @{{ answer.question.staff_message }}
                </p>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="name-template">
    <div>
        <div class="card-panel">
            <div class="row">
                <div class="col s12 m6">
                    <span class="flow-text">"<a class="searchable"  :href="linkName" target="_blank">@{{ name.name.split(' ')[0] }}</a>
                    <a class="searchable"  :href="linkLastName" target="_blank">@{{ name.name.replace(name.name.split(' ')[0], '') }}</a>"</span>
                    <p>
                        <small>Lieu de naissance:</small><br> <!-- TODO SWITCH RELIER -->
                        <span>@{{ name.lieu }}</span>
                    </p>
                    <p>
                        <small>Sexe:</small><br>
                        <span v-if="name.sexe == 1">Homme</span>
                        <span v-if="name.sexe == 2">Femme</span>
                    </p>
                    <p>
                        <small>Age:</small><br>
                        <span>@{{ ageFrom }} (@{{ age }})</span>
                    </p>
                    <p>
                        <small>Taille:</small><br>
                        <span>@{{ name.taille }} cm</span>
                    </p>
                </div>
                <div class="col s12 hide-on-med-and-up">
                    <p></p>
                </div>
                <div class="col s12 m6">
                    <answer-review :data-provided="name.name" v-on:reviewed="itemReviewed($event)" type="name" :has-abuse="false" :simple="true" :answer-id="name.id"></answer-review>
                </div>
            </div>
        </div>
    </div>
</script>
<script>
    Vue.component('name', {
        template: '#name-template',
        props: {name: {required: true}},
        methods: {
            itemReviewed: function() {
                this.$emit('reviewed');
            }
        },
        computed: {
            linkName: function() {
                return 'https://www.google.fr/search?q=' + this.name.name.split(' ')[0];
            },
            age: function() {
                moment.locale('fr');
                return moment(this.name.birthday).format("dddd D MMMM, YYYY");
            },
            ageFrom: function() {
                moment.locale('fr');
                var fromDay = moment(this.name.birthday).fromNow(true);
                return fromDay;
            },
            linkLastName: function() {
                return 'https://www.google.fr/search?q=' + this.name.name.replace(this.name.name.split(' ')[0], '');
            }
        }
    })

    Vue.component('answer', {
        template: '#answer-template',
        props: {answer: {required: true}},
        data: function() {
            return {};
        },
        methods: {
            itemReviewed: function() {
                this.$emit('reviewed');
            }
        }
    });


    Vue.component('answer-review', {
        template: '#answer-review-template',
        props: {answerId: {type: Number, required: true}, type: {required: true}, hasAbuse: {required: true}, simple: {required: true}, dataProvided: {required: true}},
        data: function() {
            return {
                loading: true,
                reporting: false,
                select: -1,
                abuseMessage: null,
                timeout: true,
                timeoutUnlock: false,
            }
        },
        methods: {
            review: function(score) {
                var vm = this;
                if(vm.loading) {
                    return;
                }
                vm.loading = true;
                axios.post('{{ route('mod-review') }}', {
                    type: vm.type,
                    id: vm.answerId,
                    score: score,
                    abuse: false,
                })
                .then(function(response) {
                    vm.loading = false;
                    vm.reporting = false;
                    vm.abuseMessage = null;
                    vm.select = -1;
                    vm.$emit('reviewed');
                }).catch(function(error) {
                    vm.loading = false;
                    vm.reporting = false;
                    vm.abuseMessage = null;
                    vm.select = -1;
                    vm.$emit('reviewed');
                });
            },
            abuse: function() {
                var vm = this;
                vm.loading = true;
                axios.post('{{ route('mod-review') }}', {
                    type: vm.type,
                    id: vm.answerId,
                    score: 0,
                    abuse: true,
                    abuseId: vm.select,
                    abuseMessage: vm.abuseMessage,
                })
                    .then(function(response) {
                        vm.loading = false;
                        vm.reporting = false;
                        vm.abuseMessage = null;
                        vm.select = -1;
                        Materialize.toast('Rapport correctement envoyé', 3000);
                        vm.$emit('reviewed');
                    }).catch(function(error) {
                        vm.loading = false;
                        vm.reporting = false;
                        vm.abuseMessage = null;
                        vm.select = -1;
                        vm.$emit('reviewed');
                });
            }
        },
        mounted: function() {
            var vm = this;
            setTimeout(function() {
                vm.loading = false;
                setTimeout(function() {
                    vm.timeoutUnlock = true;
                }, 15000, vm);
                setTimeout(function() {
                    vm.timeout = false;
                }, estimate.text(vm.dataProvided) * 350, vm);
            }, 500, vm);
        }

    });

    var app = new Vue({
        el: '#app',
        components: ['answer-review'],
        data: {
            loading: true,
            type: 'Revisión',
            review: null,
            streak: 0,
            done: false,
            presence: []
        },
        methods: {
            load: function() {
                axios.get('{{ route('mod-review-get') }}')
                .then(function(response) {
                    app.loading = true;
                    var object = response.data;
                    if(object.length === 0) {
                        app.loading = false;
                        app.review = null;
                        app.done = true;
                        return;
                    }
                    if(_.first(_.keys(object)) === 'exam') {
                        app.type = 'Prueba';
                        var object = object.exam;
                        app.review = object;
                    }
                    if(_.first(_.keys(object)) === 'answer') {
                        app.type = 'answer';
                        var object = object.answer;
                        app.review = object;
                    }
                    if(_.first(_.keys(object)) === 'name') {
                        app.type = 'name';
                        var object = object.name;
                        app.review = object;
                    }
                    app.loading = false;
                }).catch(function(error) {
                    app.loading = false;
                    Materialize.toast(error.response.data, 4000);
                });
            },
            next: function() {
                var vm = this;
                vm.review = null;
                vm.loading = true;
                vm.streak++;
                vm.load();
            },
            addPresence: function(joiningMember) {
                this.presence = _.uniq(this.presence.push(joiningMember));
            },
            removePresence: function(leavingMember) {
                var leaving = leavingMember;
                this.presence = _.uniq(_.filter(this.presence, {id: leavingMember.id}));
            }
        },
        created: function() {
            this.load();
        },
        ready: function() {
            var vm = this;
            window.addEventListener('keyup', function(event) {
                // If down arrow was pressed...
                if (event.keyCode == 65) {
                    vm.$broadcast('a-key-pressed');
                }
            });
        }
    });

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections')['pusher']['key'] }}',
        cluster: 'eu'
    });

    Echo.join('presence-review')
        .here(function (members) {
            app.presence = _.uniq(members);
        })
        .joining(function (joiningMember, members) {
            app.addPresence(joiningMember);

        })
        .leaving(function (leavingMember, members) {
            console.table(leavingMember);
            app.removePresence(leavingMember);
        });

</script>
@endsection
