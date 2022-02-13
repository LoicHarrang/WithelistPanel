@extends('layouts.dash')

@section('title', 'Recherche d\'utilisateurs')

@section('content')
    @include('mod.operateur.menu')
    <div class="container">
        <br>
        <h5>Recherche</h5>
        <br>
        @include('common.errors')
        <form action="">
            <input name="q" type="text" placeholder="Entrez un nom, SteamID ou GUID et lancez la recherche" value="{{ request()->input('q') }}" autofocus>
        </form>
        <br>
        <p><i class="material-icons tiny">list</i> Résultat ({{ $results->count() }})</p>
        <br>
        @if($results->count() == 0)
            <p><b>Aucun résultat.</b></p>
            <p>Veuillez répéter la recherche avec d'autres paramètres.</p>
        @endif
        @foreach($results as $user)
            <a href="{{ route('mod-user', $user) }}">
                <div class="card-panel hoverable black-text">
                    <div class="row">
                        <div class="col s12 m6">
                            <b>@if($user->hasFinishedSetup()) <i class="mdi mdi-approval green-text tiny"></i> @endif {{ Auth::user()->username($user) }}</b>
                            <br>{{ $user->steamid }}
                        </div>
                        <div class="col s12 m6">
                        @if(!$user->hasFinishedSetup())
                                Etat: {{ $user->getSetupStep() }}/{{$user->getSetupSteps()}}
                                (@if($user->getSetupStep() == 0)
                                    Vérification du jeu
                                @elseif($user->getSetupStep() == 1)
                                    Nationalité
                                @elseif($user->getSetupStep() == 2)
                                    Moyen de contact
                                @elseif($user->getSetupStep() == 3)
                                    Identité
                                @elseif($user->getSetupStep() == 4)
                                    Règlement
                                @elseif($user->getSetupStep() == 5)
                                    Examen
                                @elseif($user->getSetupStep() == 6)
                                    C'EST LA FAUTE DE LOIC
                                @elseif($user->getSetupStep() == 7)
                                    Entretien
                                @else
                                    ?
                                @endif)
                                <div class="progress">
                                    <div class="determinate" style="width: {{ round(($user->getSetupStep() / $user->getSetupSteps()) * 100) }}%"></div>
                                </div>
                        @else
                                @if($user->hasFinishedSetup()) <i class="mdi mdi-approval green-text tiny"></i> <b>Inscrit</b> @endif
                        @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        {{ $results->links() }}
    </div>
@endsection
