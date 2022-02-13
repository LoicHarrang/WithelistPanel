@extends('layouts.dash')
@section('title', 'Identité #' . $name->name)
@section('content')
    @include('mod.operateur.menu')
    <div class="container">
        <br>
        <small><a href="{{ route('names.index') }}"><< liste des identités</a></small>
        <h5>{{ $name->name }} <spawn>(Identité)</small> </h5>
        <div class="row">
            <div class="col s12 m6">
                <p>Informations</p>
                <div class="card-panel">
                    <p>
                        <small>Identité:</small>
                        <br>{{ $name->name }}
                    </p>
                    @if(!is_null($name->original_name))
                        <p>
                            <small>Identité sans vérification:</small>
                            <br>{{ $name->original_name }}
                        </p>
                    @endif
                    <p>
                        <small>Ma Nationalité:</small><br>
                        <span>
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
                                {{ $countryName }}
                            @else
                                Non Indiqué
                            @endif
                        </span>
                    </p>
                   <p>
                        <small>Lieu de naissance:</small><br> <!-- TODO SWITCH RELIER -->
                        <span>{{ $name->lieu }}</span>
                    </p>
                    <p>
                        <small>Sexe:</small><br> <!-- TODO SWITCH RELIER -->
                        @if ($name->sexe == 1)
                            <span>Homme</span>
                        @else
                            <span>Femme</span>
                        @endif
                    </p>
                    <p>
                        <small>Age:</small><br> <!-- TODO SWITCH RELIER -->
                        @php
                            $date = str_replace (",","-", $name->birthday);
                            $birth = Carbon::parse($date);
                        @endphp
                        <span>{{ $birth->DiffInYears() }} ans ({{ $birth->format('d/m/Y')}})</span>
                    </p>
                    <p>
                        <small>Taille:</small><br> <!-- TODO SWITCH RELIER -->
                        <span>{{ $name->taille }} cm</span>
                    </p>
                    <hr>
                    <p>
                        <small>Type:</small>
                        <br>
                        <code>
                            @if($name->type == 'imported')
                                Importé
                            @elseif($name->type == 'change')
                                Changement
                            @elseif($name->type == 'setup')
                                Originale
                            @else
                                <code>{{ $name->type or "Inconnu" }}</code>
                            @endif
                        </code>
                    </p>
                    <p>
                        <small>Création:</small>
                        <br>{{ $name->created_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <small>Dernière modification:</small>
                        <br>{{ $name->updated_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <small>Actions:</small>
                        <br>
                        @if($name->invalid || !is_null($name->end_at) || $name->needs_review)
                            @permission('mod-name-accept')
                            <form onsubmit="return confirm('Activer l\'identité?')" action="{{ route('mod-user-name-enable', $name->user) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="nameid" value="{{ $name->id }}">
                                <button type="submit" class="btn white waves-effect green-text"><i class="material-icons left">check_circle</i> Activer</button>
                            </form>
                            @endpermission
                        @endif
                        @if(isset($name->active_at) && is_null($name->end_at))
                            @permission('mod-name-reject')
                            <form onsubmit="return confirm('Désactiver l\'identité?')" action="{{ route('mod-user-name-disable', $name->user) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="nameid" value="{{ $name->id }}">
                                <button type="submit" class="btn white waves-effect red-text"><i class="material-icons left">block</i> Désactiver</button>
                            </form>
                            @endpermission
                        @endif
                    </p>
                </div>

            </div>
            <div class="col s12 m6">
                <p>État</p>
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12">
                            <p>
                                <small>État:</small>
                                <br>
                                @if($name->needs_review)
                                    En attente de vérification
                                @else
                                    @if($name->invalid)
                                         <i class="mdi mdi-close-circle red-text"></i> <b>Non conforme</b> (suite à la validation)
                                    @elseif(!is_null($name->end_at))
                                        <i class="mdi mdi-alert-circle orange-text"></i> <b>Ancienne identité</b> désactivé depuis le {{ $name->end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }}
                                    @elseif(!is_null($name->active_at))
                                        <i class="mdi mdi-check-circle green-text"></i> <b>Activé</b> depuis le {{ $name->active_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }}
                                    @else
                                        <i class="mdi mdi-alert-box red-text"></i><b> ERREUR</b> aucune données trouvée
                                    @endif
                                @endif
                            </p>
                        </div>
                        @permission('mod-name-reviewers')
                        <br>
                        <div class="col s12">
                            <small>Vérification:</small>
                        </div>

                        @foreach($name->reviews as $review)
                            <div class="col s4">
                                <div class="card-panel @if($review->score == 100) green lighten-4 @endif @if($review->score < 100 && $review->score > 0) orange lighten-4 @endif @if($review->score == 0) red lighten-3 @endif">
                                    <i class="chipicon material-icons">@if($review->score == 100) thumb_up @elseif($review->score == 0) thumb_down @endif</i>
                                    <br><small>{{ $review->user->username($review->user) }}</small>
                                    <br><small>{{ $review->created_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                        @if($name->reviews->count() == 0)
                            <div class="col s12">
                                <p>Aucune vérification.</p>
                            </div>
                        @else
                            <div class="col s12">
                                <small>{{ $name->reviews->count() }}/3 validation(s)</small>
                            </div>
                        @endif
                        @endpermission
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
