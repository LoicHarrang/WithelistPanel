@extends('layouts.dash')
@section('title', 'Examen #' . $exam->id)
@section('content')
    @include('mod.operateur.menu')
    <div class="container">
        <br>
        <small><a href="{{ route('exams.index') }}"><< liste des examens</a></small>
        <h5>Examen #{{ $exam->id }}</h5>
        <div class="row">
            <div class="col s12 m4">
                <p>Informations</p>
                <div class="card-panel">
                    <p>
                        <small>Etat:</small>
                        <br>
                        @if(is_null($exam->passed))
                            @if($exam->end_at <= \Carbon\Carbon::now() || $exam->finished)
                                <i class="mdi mdi-file-document"></i> Examen en attente de validation
                            @else
                                <i class="mdi mdi-file-document"></i> Examen en cours
                            @endif
                        @elseif(!$exam->passed)
                            <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Examen échoué</span>
                        @elseif(is_null($exam->interview_passed))
                            @if($exam->expires_at <= \Carbon\Carbon::now())
                                <i class="mdi mdi-file-document"></i> Expiration le {{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                            @elseif(is_null($exam->interview_user_id))
                                <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-clock"></i> En attente d'entretien
                                {{-- TODO botón iniciar entrevista --}}
                            @else
                                @if(is_null($exam->interview_code_at))
                                    <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-code-array"></i> Entretien en cours, en attente du code <small>({{ $exam->interviewer->username }})</small>
                                @else
                                    <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-headset"></i> Entretien en cours <small>({{ $exam->interviewer->username }})</small>
                                @endif
                            @endif
                        @elseif($exam->interview_passed)
                            <span class="green-text"><i class="mdi mdi-file-document green-text"></i><i class="mdi mdi-headset"></i> Approuvé</span>
                        @elseif(!$exam->interview_passed)
                            <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-headset red-text"></i> <span class="red-text">Échoué</span>
                        @else
                            <i>Etat inconnu ou invalide.</i>
                        @endif
                    </p>
                    <p>
                        <small>Date de l'examen:</small>
                        <br>{{ $exam->start_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <small>Expiration:</small>
                        <br>{{ $exam->expires_at->diffForHumans() }} <small>({{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }})</small>
                        {{--<a href="" class="tooltipped" data-tooltip="Extender">+</a>--}}
                    </p>
                </div>
            </div>
            <div class="col s12 m8">
                <p>Entretien</p>
                <div class="card-panel">
                    <p>
                        <small>Etat:</small>
                        <br>

                        @if(is_null($exam->passed))
                            <i class="mdi mdi-clock"></i> En attente de l'examen
                        @elseif(!$exam->passed)
                            <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Examen échoué</span>
                        @elseif(is_null($exam->interview_passed))
                            @if($exam->expires_at <= \Carbon\Carbon::now())
                                <i class="mdi mdi-file-document"></i> Expiration le {{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                            @elseif(is_null($exam->interview_user_id))
                                <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-clock"></i> En attente d'entretien

                            @else
                                @if(is_null($exam->interview_code_at))
                                    <i class="mdi mdi-code-array"></i> Entretien en cours, en attente du code <small>({{ $exam->interviewer->username }})</small>
                                @else
                                    <i class="mdi mdi-headset"></i> Entretien en cours <small>({{ $exam->interviewer->username }})</small>
                                @endif
                            @endif
                        @elseif($exam->interview_passed)
                            <span class="green-text"><i class="mdi mdi-headset"></i> Entretien approuvé</span>
                        @elseif(!$exam->interview_passed)
                            <i class="mdi mdi-headset red-text"></i> <span class="red-text">Entretien échoué</span>
                        @else
                            <i>Etat inconnu ou invalide.</i>
                        @endif

                        {{-- ENTRETIENT --}}
                        @if($user->getSetupStep() == 7)
                            @if(is_null($user->getInterviewExam()->interviewer))
                                @permission('mod-interview')
                                    <form action="{{ route('mod-interview', $user->getInterviewExam()) }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn back-gd-2 white-text waves-effect">Commencer l'entretien</button>
                                    </form>
                                @endpermission
                            @else
                                @if($user->getInterviewExam()->interviewer->is(Auth::user()))
                                    <p>En cours d'entretien.</p>
                                    <a href="{{ route('mod-interview', $user->getInterviewExam()) }}" class="btn back-gd-2 white-text waves-effect">Continuer l'entretien</a>
                                @else
                                    <span><b>{{ $user->getInterviewExam()->interviewer->username }} s'occupe de l'entretien.</b></span>
                                @endif
                            @endif
                        @endif
                    </p>
                    @if(!is_null($exam->interview_user_id))
                        <p>
                            <small>Intervenant:</small>
                            <br>{{ $exam->interviewer->username }}
                        </p>
                    @endif
                    @if(!is_null($exam->interview_at))
                        <p>
                            <small>Début de l'entretient:</small>
                            <br>{{ $exam->interview_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                        </p>
                    @endif
                    @if(!is_null($exam->interview_end_at))
                        <p>
                            <small>Fin de l'entretien:</small>
                            <br>{{ $exam->interview_end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }} ({{ $exam->interview_end_at->diffInMinutes($exam->interview_at) }} minute(s))
                        </p>
                    @endif
                </div>
                <p>Examen</p>
                <div class="card-panel">
                    <p>
                        <small>Etat:</small>
                        <br>
                        @if(is_null($exam->passed))
                            @if($exam->end_at <= \Carbon\Carbon::now() || $exam->finished)
                                <i class="mdi mdi-file-document"></i> Examen en attente de vérification
                            @else
                                <i class="mdi mdi-file-document"></i> Examen en cours
                            @endif
                        @else
                            @if(!$exam->passed)
                                <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Echec de l'examen</span>
                            @else
                                <span class="green-text"><i class="mdi mdi-file-document green-text"></i> Examen réussi</span>
                            @endif
                        @endif
                    </p>
                    @permission('mod-exam-answers')
                    @if(!is_null($exam->score))
                        <p>
                            <small>Score:</small>
                            <br>{{ $exam->score or "?" }} / 20
                        </p>
                    @endif
                    <div class="divider"></div>
                    <br>
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
                                    {{--<br><small>{{ $answer->score }}% | {{ $question['value'] }}p</small>--}}
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
                    @endpermission
                </div>
            </div>
        </div>
    </div>
@endsection
