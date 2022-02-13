@extends('layouts.dash')
@section('title', 'Examen')
@section('content')
    @include('mod.operateur.menu')
    <div class="container">
        <br>
        <h5>Recherche d'examen</h5>

        <p><i class="material-icons tiny">list</i> Résultat ({{ $results->total() }})</p>
        @if($results->total() == 0)
            <br>
            <p><b>Aucun résultat.</b></p>
            <p>Veuillez repeter la recherche avec d'autres paramètres.</p>
        @endif

        @foreach($results as $exam)
            <a href="{{ route('exams.show', $exam) }}">
                <div class="card-panel black-text hoverable">
                    <b>{{ $exam->user->username($exam->user) }}</b> <small class="right">{{ $exam->updated_at->diffForHumans() }}</small>
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
                </div>
            </a>
        @endforeach
        {{ $results->links() }}
    </div>

@endsection
