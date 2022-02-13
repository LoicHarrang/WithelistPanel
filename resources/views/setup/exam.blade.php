@extends('layouts.dash')

@section('title', 'Examen')

@section('content')
    <div id="app">
        <br>
        <form @submit.prevent="subimtAnswer()">
            <div class="container">
                @include('setup.breadcrumb')
                @if($type === 'first')
                    <br>
                    <h5>Examen</h5>
                    <div class="card-panel" style="margin-top: 16px">
                        <b>Début de l'examen !</b>
                        <p>
                            <small>Durée accordé pour l'examen:</small>
                            <br>
                            <span>{{ $exam->end_at->diffInMinutes($exam->start_at) }} minutes</span>
                        </p>
                        <p>
                            <small>Nombre de questions:</small>
                            <br>
                            <span>{{ $exam->getQuestionCount() }} questions</span>
                        </p>
                        <p><b>ATTENTION:</b> Une fois validé, les questions ne peuvent plus être modifié.</p>
                    </div>
                @endif

                @if(!is_null($group))

                   <div style="margin-top: 16px">
                       @php
                           $questionCount = 1;
                       @endphp
                       @foreach($exam->structure as $questionGroup)
                           @if(! $loop->first) <span style="margin-right: 5px;" >|</span> @endif
                           @foreach($questionGroup['questions'] as $questionMenu)
                               <div class="chip @if($pageNumber == $questionCount) black white-text @elseif(!is_null($questionMenu['answer_id'])) blue lighten-2 @endif">
                                   {{ $questionCount }}
                               </div>
                               @php
                                   $questionCount++;
                               @endphp
                           @endforeach
                       @endforeach
                   </div>

                    <div class="card-panel" style="margin-top: 16px">
                                <b class="flow-text">Examen en cours</b>
                                <p>Ne paniquez pas, il n'y a aucune question piège</p>
                    </div>
                @endif
                @if(isset($question))
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="card-panel">
                                <p>
                                    <b>Question {{ $pageNumber }}</b>
                                    <br>
                                    <small>Vaut 2 points.</small>
                                    <br><br>
                                </p>
                            </div>
                        </div>
                        <div class="col s12 m8">
                            <div class="card-panel">
                                @include('setup.exam.question', ['question' => \App\Question::find($question['id'])])
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m6">
                                    <div class="hide-on-med-and-up" style="margin-top: 16px">
                                    </div>
                                    <b>Terminé <span v-cloak>@{{ diff }}</span></b>
                                    <br><small>L'examen sera envoyé automatiquement quand le temps sera écoulé.</small>
                                </div>
                                <div class="col s12 m6">
                                    @if($type == 'first')
                                        <a href="{{ route('setup-exam', ['page' => 1]) }}" class="btn blue waves-effect pulse right">Commencer l'examen <i class="material-icons right">navigate_next</i></a>
                                    @else
                                        <button v-if="!loading" :disabled="loading" class="btn blue waves-effect right">Valider et Continuer <i class="material-icons right">navigate_next</i></button>
                                        @if($pageNumber == 1)
                                            <div v-if="!loading" class="right">
                                                <small>Vous ne pouvez plus modifier vos réponses une fois qu'elles sont validées.</small>
                                            </div>
                                        @endif
                                        <div v-cloak v-if="loading">
                                            <div class="progress">
                                                <div class="indeterminate"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    {{--<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>--}}
    {{--<script src="https://unpkg.com/vue"></script>--}}
    {{--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}
    {{--<script src="/js/countdown.min.js"></script>--}}
    {{--<script src="/js/moment-countdown.min.js"></script>--}}
    <script>
        {{-- pour ceux qui éditent côté client --}}
        /*
        Lisez-moi avant de modifier le code.
        -----
        Ne perdez pas votre temps.
        Vous pouvez manipuler le client, mais pas le serveur.
        Malheureusement pour vous, nous disposons d'une validation côté serveur.
        Si vous changez quelque chose ici, vous serez le seul à le voir.
        Retarder l'heure de la fin ? Dommage pour vous. Nous n'obtiendrons pas vos réponses.
        Honnêtement, je vous recommande de vous concentrer sur le test.
        Si vous ne réussissez pas, CHEY.

        En tout cas, si vous trouvez des bugs dans le coin, aller voir l'administration.
        Je suis sûr qu'il serons en mesure de vous récompenser ;)
         */
        var app = new Vue({
            el: '#app',
            data: {
                passed: false,
                date: moment.tz('{{ $exam->started_at }}', '{{ config('app.timezone') }}'),
                dateAllow: moment.tz('{{ $exam->end_at }}', '{{ config('app.timezone') }}'),
                load: new Date(),
                countdown: "",
                diff: "",
                now: moment(new Date()),
                answer: null,
                loading: false,
                message: '',
                writingMessage: false,

            },
            methods: {
                update: function() {
                    var self = this;
                    this.diff = moment(new Date()).to(this.dateAllow).toString();
                    this.countdown = moment(this.dateAllow).countdown().toString();
                    this.now = moment(new Date());
                    setTimeout(function(){ self.update() }, 1000)
                },
                subimtAnswer: function(force) {
                    if(app.loading) {
                        return;
                    }
                    force = typeof force !== 'undefined' ? force : false;
                    app.loading = true;
                    if(app.answer === null && !force && !confirm("Vous n'avez rien répondu. Voulez-vous continuer ?")) {
                        app.loading = false;
                        return false;
                    }
                    axios.post('{{ route('setup-exam', ['id' => $pageNumber]) }}',{
                        answer: app.answer,
                        number: {{ $pageNumber }},
                        message: app.message
                    })
                    .then(function(response) {
                        app.loading = true;
                        if(response.data === "next") {
                            window.location.replace("/setup/exam/" + {{ $pageNumber + 1 }});
                        }
                        if(response.data === "end") {
                            window.location.replace("/setup/examwait");
                        }
                    }).catch(function(error) {
                        app.loading = false;
                        Materialize.toast(error.response.data, 4000);
                    });
                }
            },
            computed: {
                allowExam: function() {
                    return this.now > this.dateAllow;
                },
                allowSubmit: function() {
//                    return this.load < this.load.
                },
                timeColor: function() {

                }
            },
            watch: {
                allowExam: function() {
//                    if
                }
            },
            created: function() {
                this.update();
            }

        });
    </script>
@endsection
