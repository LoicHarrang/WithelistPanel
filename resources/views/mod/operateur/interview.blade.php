@extends('layouts.dash')

@section('title', 'Entretien #' . $exam->id)

@section('content')
    @include('mod.operateur.menu')
    <div class="container" id="app">
        <br>
        <h5>Entretien #{{ $exam->id }} ({{ Auth::user()->username($exam->user) }})</h5>

        <div class="row">
            <div class="col s12 m4">
                <p>Informations</p>
                <div class="card-panel">
                    <p>
                        <small>Nom:</small>
                        <br>{{ Auth::user()->username($exam->user) }}
                    </p>
                    <p>
                        <small>SteamID:</small>
                        <br><span class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{ $exam->user->steamid }}" onclick="Materialize.toast('Copié',  3000)">{{ $exam->user->steamid }} <i class="mdi mdi-content-copy tiny blue-text"></i></span>
                    </p>
                    <p>
                        <small>GUID:</small>
                        <br><small class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{ $exam->user->guid }}" onclick="Materialize.toast('Copié',  3000)">{{ $exam->user->guid }} <i class="mdi mdi-content-copy tiny blue-text"></i></small>
                    </p>
                    @permission('mod-reveal-email')
                    <p>
                        <small>Adresse Email:</small>
                        <br>
                        @if(is_null($exam->user->email))
                            <i>-</i>
                        @else
                            <span class="clickable copy tooltipped" data-tooltip="Copier" data-clipboard-text="{{$exam->user->email}}" onclick="Materialize.toast('Copié',  3000)">{{$exam->user->email}} <i class="mdi mdi-content-copy tiny blue-text"></i></span>
                        @endif
                    </p>
                    @endpermission
                </div>
            </div>

            @if(!is_null($exam->interview_code_at))
                <div class="col s12 m8">
                    <p>Examen indisponible</p>
                </div>
                    {{--<div class="card-panel">
                        <p>
                            <small>Score:</small>
                            <br><b>{{ $exam->score or "?" }} / 20</b>
                        </p>
                        <p>
                            <small>Date de début:</small>
                            <br>{{ $exam->start_at->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            <small>Date de fin:</small>
                            <br>@if(!is_null($exam->finish_at))
                                {{ $exam->finish_at->format('d/m/Y H:i') }}
                            @else
                                {{ $exam->end_at->format('d/m/Y H:i') }} <small>(Automatiquement)</small>
                            @endif
                        </p>
                    </div>
                    <p>Détail de l'examen</p>
                    <div class="card-panel">
                    @php
                        $questionCount = 1;
                    @endphp
                    @foreach($exam->structure as $group)
                        @foreach($group['questions'] as $question)
                            @php
                                $questionModel = \App\Question::find($question['id']);
                                $answer = \App\Answer::find($question['answer_id']);
                            @endphp
                            @if (!is_null($answer))
                                <a href="#question-{{ $questionCount }}" class="chip @if(!is_null($answer->score)) @if($answer->score == 100) green lighten-4 @else red lighten-3 @endif @endif">
                                    {{ $questionCount }}

                                    @if($answer->question->type == 'text')
                                        <i class="chipicon material-icons">edit</i>
                                    @elseif($answer->question->type == 'single')
                                        <i class="chipicon material-icons">more_vert</i>
                                    @endif
                                </a>
                            @else
                                <a href="#question-{{ $questionCount }}" class="chip red lighten-4">
                                    {{ $questionCount }}
                                </a>
                            @endif
                            @php
                                $questionCount++;
                            @endphp
                        @endforeach
                        @if(!$loop->last) | @endif
                    @endforeach

                    <div class="divider" style="margin-top: 8px"></div>
                    @php
                        $questionCount = 1;
                    @endphp
                    @foreach($exam->structure as $group)
                        <div class="divider"></div>
                        <p>
                            <span class="flow-text">{{ $group['name'] }}</span>
                        @foreach($group['questions'] as $question)
                            @php
                                $questionModel = \App\Question::find($question['id']);
                                $answer = \App\Answer::find($question['answer_id']);
                            @endphp
                            <p id="question-{{ $questionCount }}">
                                @if (!is_null($answer))
                                    <span class="chip @if(!is_null($answer->score)) @if($answer->score == 100) green lighten-4 @else red lighten-3 @endif @endif">
                                        {{ $questionCount }}
                                        @if($answer->question->type == 'text')
                                            <i class="chipicon material-icons">edit</i>
                                        @elseif($answer->question->type == 'single')
                                            <i class="chipicon material-icons">more_vert</i>
                                        @endif
                                    </span>
                                @else
                                     <span class="chip red lighten-4">
                                        {{ $questionCount }}
                                    </span>
                                @endif
                                <span>{{ $questionModel->question }}</span>
                                @if(!is_null($answer) && ($questionModel->type == 'text'))
                                    <br>
                                    <code>"{{ $answer->answer }}"</code>
                                    {{--<br><small>{{ $answer->score }}% | {{ $question['value'] }}p</small>
                                @elseif(!is_null($answer) && $questionModel->type == 'single')
                                    <br>
                                    <code>"{{ collect($answer->question->options)->where('id', $answer->answer)->first()['text'] }}"</code>
                                @endif
                                @if(is_null($answer))
                                    <br>
                                    Sans réponse
                                @endif
                                @permission('mod-exam-answers-reviews')
                                @if($answer->reviews->count() > 0)
                                    <br>
                                    @foreach($answer->reviews as $review)
                                        <div class="chip @if($review->score == 100) green lighten-4 @elseif($review->score == 0) red lighten-3 @endif">
                                            <small>({{ $review->user->username }})</small>
                                            <i class="chipicon material-icons">@if($review->score == 100) thumb_up @elseif($review->score == 0) thumb_down @endif</i>
                                        </div>
                                    @endforeach
                                @endif
                                @endpermission
                            @if(!is_null($answer->score))
                                @php
                                    $scoreFormatted = (($answer->score / 100) * 20) / 10;
                                @endphp
                                <small>{{ $scoreFormatted }} / 2</small>
                            @endif

                            @php
                                $questionCount++;
                            @endphp
                            </p>
                            @endforeach
                            </p>
                            @endforeach
                    </div>
                </div>--}}
                <div class="col s12">
                    <div class="card-panel">
                        <button :disabled="loading" @click.prevent="grade(false, false)" class="btn red waves-effect"><i class="material-icons left">block</i> Refuser</button>
                        <button :disabled="loading" @click.prevent="grade(true, false)" class="btn green waves-effect"><i class="material-icons left">done</i> Approuver</button>
                    </div>
                </div>
            @else
                <div class="col s12 m8">
                    <p>Code de sécurité</p>
                    @include('common.errors')
                    <div class="card-panel">
                        <form action="{{ route('mod-interview-code', $exam) }}" method="POST">
                            {{ csrf_field() }}
                            <input name="code" type="text" placeholder="Code donné par l'utilisateur" required minlength="32" maxlength="32" data-length="32" spellcheck="false">
                            <button type="submit" class="btn back-gd-2 waves-effect">Vérifier</button>
                        </form>
                    </div>
                    <div class="card-panel">
                        <b>Instructions:</b>
                        <ol>
                            <li><p>Veuillez faire connecter l'utilisateur sur le dash. </p></li>
                            <li><p>Veuillez demander à l'utilisateur de cliquer sur CONTINUER.</p></li>
                            <li><p>Un code va apparaitre, demander lui de vous le fournir <br> <small>Exemple: <code>mmaUuU5x1PdFLzUi0maUuU5x1PdFLzUi</code></small></p></li>
                            <li><p>Veuillez rentrer le code, valider et demander à l'utilisateur de rafraichir sa page.</p></li>
                        </ol>
                    </div>
                </div>
            @endif
            <div class="col s12">
                <form onsubmit="return confirm('Annuler l\'entretien? @if(!is_null($exam->interview_code_at)) \nL\'utilisateur sera notifié. @endif')" action="{{ route('mod-interview-cancel', $exam) }}" method="POST">
                    {{ csrf_field() }}
                    <button class="btn-flat waves-effect red-text right">Annuler l'entretien</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                loading: false
            },
            methods: {
                grade: function(pass, pegi) {
                    this.loading = true;
                    axios.post('{{ route('mod-interview-grade', $exam) }}', {
                        pass: pass,
                        pegi: pegi
                    }).then(function() {
                        window.location.replace('{{ route('mod-user', $exam->user) }}');
                    }).catch(function() {
                        {{--window.location.replace('{{ route('mod-user', $exam->user) }}');--}}
                    });
                }
            }
        });
    </script>
@endsection
