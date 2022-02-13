@extends('layouts.dash')
@section('title', 'Identités')
@section('content')
    @include('mod.operateur.menu')
    <div class="container">
        <br>
        <h5>Recherche d'identité</h5>
        <br>
        <form action="">
            <input name="q" type="text" placeholder="Entrer un nom, SteamID ou GUID et cliquer sur entrer" value="{{ request()->input('q') }}" autofocus>
            @foreach(\Illuminate\Support\Facades\Input::except('q') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>

        <div class="chip dropdown-button-extend clickable @if(request()->has('type')) black white-text @endif" data-activates='dropdown-type'>
            Filtre: @if(request()->has('type')) <b>{{ request()->input('type') }}</b> @endif
            <i class="chipicon material-icons">list</i>
        </div>
        <!-- Dropdown Structure -->
        <ul id='dropdown-type' class='dropdown-content'>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Tout</a></li>
            <li class="divider"></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => 'imported']) }}" class="waves-effect">Importations</a></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => 'change']) }}" class="waves-effect">Changements</a></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => 'setup']) }}" class="waves-effect">Originelles</a></li>
        </ul>

        <br>
        <p><i class="material-icons tiny">list</i> Résultat ({{ $results->total() }})</p>
        @if($results->total() == 0)
            <br>
            <p><b>Aucun résultat.</b></p>
            <p>Veuillez repeter la recherche avec d'autres paramètres.</p>
        @endif
        @foreach($results as $name)
            <a href="{{ route('names.show', $name) }}">
                <div class="card-panel hoverable black-text">
                    <div class="row">
                        <div class="col s12 m6">
                            @if($name->needs_review)
                                <i class="mdi mdi-dots-horizontal"></i>
                            @else
                                @if($name->invalid)
                                    <i class="mdi mdi-close-circle red-text"></i>
                                @elseif(!is_null($name->end_at))
                                    <i class="mdi mdi-alert-circle orange-text"></i>
                                @elseif(!is_null($name->active_at))
                                    <i class="mdi mdi-check-circle green-text"></i>
                                @else
                                    <i class="mdi mdi-checkbox-blank-circle grey-text"></i>
                                @endif
                            @endif
                            <b>{{$name->name}}</b>
                            @if($name->name != $name->user->username)
                                <small>({{ $name->user->username }})</small>
                            @elseif($name->type == 'change')
                                <small>(<i class="mdi mdi-clock"></i> {{ $name->user->names()->whereNotNull('end_at')->latest()->first()->name }})</small>
                            @endif
                        </div>
                        <div class="col s12 m6">
                                <div class="chip @if(request()->has('type')) black white-text @endif" >
                                    @if($name->type == 'imported')
                                        Importé
                                    @elseif($name->type == 'change')
                                        Changement
                                    @elseif($name->type == 'setup')
                                        Originale
                                    @else
                                        <code>{{ $name->type or "Inconnu" }}</code>
                                    @endif
                                    <i class="chipicon material-icons">list</i>
                                </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        {{ $results->appends(\Illuminate\Support\Facades\Input::except('page'))->links() }}
    </div>
@endsection
