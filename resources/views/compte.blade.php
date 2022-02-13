@extends('layouts.dash')

@section('title', 'Mon Compte')

@section('content')
    <br>
    <div class="container">
        <h5>Mon compte</h5>

        <nav class="back-gd-1 nav-extended white-text">
            <div class="nav-wrapper">
                <ul class="left hide-on-med-and-down">
                @if(!is_null($user->country))
                    @php
                        $country = Countries::where('cca2', $user->country)->first();
                        $countryName = "?";
                        try {
                            $countryName = $country->translations->fra->common;
                        } catch(\Exception $e) {
                            // :)
                        }
                    @endphp
                    <li><a class="waves-effect" href="#">{!! $country->flag['flag-icon'] !!} {{ $user->username($user) }} ({{ $user->steamid }})</a></li>
                @else
                    <li><a class="waves-effect" href="#">{{ $user->username }} ({{ $user->steamid }})</a></li>
                @endif
                </ul>
                <div class="nav-content">
                    <ul class="tabs tabs-transparent">
                        <li class="tab"><a class="active" href="#data">Informations</a></li>
                        <li class="tab">
                            <a href="#whitelist">
                                status
                                @if(!$user->hasFinishedSetup())
                                    <span class="white black-text new badge" data-badge-caption="">{{ $user->getSetupStep() }}/{{$user->getSetupSteps()}}</span>
                                @endif
                            </a>
                        </li>
                        <li class="tab"><a href="#names">Identité</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        @if(!$user->hasFinishedSetup())
            <div class="col s12">
                <div class="card-panel">
                    <i class="mdi mdi-alert-octagram"></i> Vous n'avez pas terminé le processus d'inscription. <a href="{{ route('setup-info') }}">Cliquez ici pour continuer →</a>
                </div>
            </div>
        @endif

        <div id="data" class="col s12">
            <div class="row">
                <div class="col s12 l6">
                    <p>Informations (RP)</p>
                    <div class="card-panel">
                        <p>
                            <small>Ma Nationalité:</small>
                            <br><span>
                            @if(!is_null($user->country))
                                    @php
                                        $country = Countries::where('cca2', $user->country)->first();
                                        $countryName = "?";
                                        try {
                                            $countryName = $country->translations->fra->common;
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
                        @if(!is_null($player))
                            <p>
                                <small>Compte courant</small>
                                <br><span>
                                    @php
                                     echo number_format($player->bankacc, 0, ',', ' ');
                                    @endphp
                                    €
                            </span>
                            </p>
                            @if(isset($player->phone[0]))
                                <p>
                                    <small>Numéro de téléphone</small>
                                    <br>
                                    @php 
                                        $numFormated = str_split($player->phone[0]->num,2);; 
                                        $numFormated = implode(" ", $numFormated); 
                                    @endphp
                                    <span class="copy tooltipped clickable" data-tooltip="Copier dans le presse-papier" data-clipboard-text="{{ $player->phone[0]->num }}" onclick="Materialize.toast('Copié dans le presse-papier',  3000)">
                                        <span>{{ $numFormated }} </span>
                                        <a><i class="mdi mdi-content-copy"></i></a>
                                    </span>
                                </p>
                            @endif
                        @endif
                    </div>
                    @permission('user-abilities-view')
                    <p>Staff</p>
                    <div class="card-panel">
                        <p>
                            <small>Grade:</small>
                        <ul>
                            @foreach($user->roles as $role)
                                <li>{{ $role->display_name }}</li>
                            @endforeach
                            @if($user->roles->count() == 0)
                                <li>Vous n'appartenez à aucun groupe.</li>
                            @endif
                        </ul>
                        </p>
                    </div>
                    @endpermission
                </div>
                <div class="col s12 l6">
                    <p>Informations (HRP)</p>
                    <div class="card-panel">
                       <p>
                            <small>Nom d'utilisateur</small>
                            <br><span>
                               {{ $user->username($user) }}
                        </span>
                        </p>
                       <p>
                            <small>SteamID</small>
                            <br><span>
                                <span class="copy tooltipped clickable" data-tooltip="Copier dans le presse-papier" data-clipboard-text="{{ $user->steamid }}" onclick="Materialize.toast('Copié dans le presse-papier',  3000)">
                                    <span>{{ $user->steamid }} </span> 
                                    <a><i class="mdi mdi-content-copy"></i></a>
                                </span>
                        </span>
                        </p>
                        @if(!is_null($player))
                            <p>
                                <small>Date de première connexion</small>
                                <br><span>
                                    @php
                                        $date = date_create($player->insert_time);
                                        echo date_format($date, 'd/m/Y (H:i:s)');
                                    @endphp
                            </span>
                            </p>
                            <p>
                                <small>Dernière connexion</small>
                                <br><span>
                                    @php
                                        $date = date_create($player->last_seen);
                                        echo date_format($date, 'd/m/Y (H:i:s)');
                                    @endphp
                            </span>
                            </p>
                            <p>
                                <small>Temps de jeu</small>
                                <br><span>
                                    @php
                                        $pt = $player->playtime;
                                        $pt = str_replace("[", "", $pt);
                                        $pt = str_replace("]", "", $pt);
                                        $ptArr = explode(",", $pt);
                                        $ptFormated = $ptArr[2];
                                        $ptInt = intval($ptFormated);
                                        
                                        $heures = intval($ptInt / 60);
                                        $minutes = $ptInt % 60;

                                        echo $heures.' heure(s) '.$minutes.' minute(s)';
                                    @endphp
                            </span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div id="whitelist" class="col s12">
            <div class="row">
                <div class="col s12">
                    <p><i class="mdi mdi-passport tiny"></i> Mes accès</p>
                    @if($user->hasFinishedSetup())
                        <div class="card-panel">
                            <p><b><i class="mdi mdi-approval green-text tiny"></i> Joueur inscrit</b></p>
                            @if($user->imported_exam_exempt)
                                <p>Vous êtes exampté de passer l'inscription.</p>
                            @else
                                <p>Vous avez réussi votre inscription.</p>
                            @endif
                            @if(!is_null($user->is_beta))
                                <p><b><i class="mdi mdi-polymer orange-text tiny"></i> Bêta testeur</b></p>
                            @endif
                            @if(!is_null($player))
                            @if ($player->coplevel > 1)
                                <p><b><i class="mdi mdi-shield blue-text text-darken-2 tiny"></i> Membre de la Gendarmerie</b></p> 
                            @switch($player->coplevel)
                                @case (2)
                                    <p>1ère classe</p>
                                @break
                                @case (3)
                                    <p>Brigadier</p>
                                @break
                                @case (4)
                                    <p>Brigadier-Chef</p>
                                @break
                                @case (5)
                                    <p>Maréchal des logis</p>
                                @break
                                @case (6)
                                    <p>Gendarme</p>
                                @break
                                @case (7)
                                    <p>Maréchal des logis-Chef</p>
                                @break
                                @case (8)
                                    <p>Adjudant</p>
                                @break
                                @case (9)
                                    <p>Adjudant-Chef</p>
                                @break
                                @case (10)
                                    <p>Major</p>
                                @break
                                @case (11)
                                    <p>Sous-lieutenant</p>
                                @break
                                @case (12)
                                    <p>Lieutenant</p>
                                @break
                                @case (13)
                                    <p>Capitaine</p>
                                @break
                                @case (14)
                                    <p>Commandant</p>
                                @break
                                @default
                                    <p>Erreur</p>
                            @endswitch
                            @endif
                            @if ($player->pollevel > 0)
                                <p><b><i class="mdi mdi-shield blue-text text-darken-2 tiny"></i> Membre de la Police</b></p>
                                @switch($player->pollevel)
                                @case (1)
                                    <p>Agent</p>
                                @break
                                @case (2)
                                    <p>Premier Agent</p>
                                @break
                                @case (3)
                                    <p>Inspecteur</p>
                                @break
                                @case (4)
                                    <p>Premier Inspecteur</p>
                                @break
                                @case (5)
                                    <p>Inspecteur Principal</p>
                                @break
                                @case (6)
                                    <p>Premier Inspecteur Principal</p>
                                @break
                                 @case (7)
                                    <p>Commissaire</p>
                                @break
                                 @case (8)
                                    <p>Premier Commissaire</p>
                                @break
                                @default
                                    <p>Erreur</p>
                            @endswitch
                            @endif
                            @if ($player->mediclevel > 0)
                                <p><b><i class="mdi mdi-fire red-text tiny"></i> Membre des Sapeurs-Pompiers</b></p>
                            @switch($player->mediclevel)
                                @case (1)
                                    <p>2nd classe</p>
                                @break
                                @case (2)
                                    <p>1ère classe</p>
                                @break
                                @case (3)
                                    <p>Caporal</p>
                                @break
                                @case (4)
                                    <p>Caporal-Chef</p>
                                @break
                                @case (5)
                                    <p>Sergent</p>
                                @break
                                @case (6)
                                    <p>Sergent-Chef</p>
                                @break
                                @case (7)
                                    <p>Adjudant</p>
                                @break
                                @case (8)
                                    <p>Adjudant-Chef</p>
                                @break
                                @case (9)
                                    <p>Lieutenant</p>
                                @break
                                @case (10)
                                    <p>Capitaine</p>
                                @break
                                @case (11)
                                    <p>Commandant</p>
                                @break
                                @default
                                    <p>Erreur</p>
                            @endswitch
                            @endif
                            @endif
                        </div>
                        @php
                            $whitelist = json_decode($whitelist);
                            $whitelist = $whitelist->Response;
                            $is_wl = false;
                            foreach ($whitelist as $key => $whitelist) {
                                if ($whitelist->Guid === $user->guid) {$is_wl = true;}
                            }
                        @endphp
                            <p><i class="mdi mdi-clipboard-text tiny"></i> Mon status</p>
                            <div class="card-panel">
                                @if($is_wl)
                                    <b><i class="mdi mdi-approval green-text tiny"></i> Vous êtes actuellement sous withelist. </b>
                                    <p>Vous êtes actuellement sur la liste des personnes autorisé à rejoindre le serveur.</p>
                                @else
                                    <b><i class="mdi mdi-alert-decagram red-text tiny"></i> Vous n'êtes pas encore whitelisté. </b> 
                                    <p>Votre demande à bien été transmise au service approprié, veuillez patienter le temps du traitement de votre demande...</p>
                                @endif
                            </div>
                    @else
                        <div class="card-panel">
                            @if($user->getSetupStep() == 0)
                                Vérification du jeu
                            @elseif($user->getSetupStep() == 1)
                                Informations
                            @elseif($user->getSetupStep() == 2)
                                Adresse Email
                            @elseif($user->getSetupStep() == 3)
                                Identité
                            @elseif($user->getSetupStep() == 4)
                                Règlement
                            @elseif($user->getSetupStep() == 5)
                                Examen
                            @elseif($user->getSetupStep() == 6)
                                Forum
                            @elseif($user->getSetupStep() == 7)
                                Entretien
                            @else
                                ?
                            @endif
                                ({{ $user->getSetupStep() }}/{{$user->getSetupSteps()}})
                            <div class="progress">
                                <div class="determinate" style="width: {{ round(($user->getSetupStep() / $user->getSetupSteps()) * 100) }}%"></div>
                            </div>
                        </div>
                        <p><i class="mdi mdi-elevator tiny"></i> Vos tentatives</p>
                        @foreach($user->exams as $exam)
                            <div class="card-panel">
                                <b>
                                    @if(!$exam->finished && $exam->end_at > \Carbon\Carbon::now())
                                        En cours
                                    @endif
                                    @if(is_null($exam->passed) && ($exam->finished || $exam->end_at <= \Carbon\Carbon::now()))
                                        En attente de correction
                                    @endif
                                    @if(!is_null($exam->passed) && !$exam->passed)
                                        <i class="mdi mdi-receipt red-text tiny"></i> Épreuve écrite non réussie
                                    @endif
                                    @if(!is_null($exam->passed) && $exam->passed && is_null($exam->interview_passed))
                                        <i class="mdi mdi-alarm tiny"></i> Approuvé, en attente d'entretien
                                    @endif
                                    @if(!is_null($exam->passed) && $exam->passed && !is_null($exam->interview_passed) && !$exam->interview_passed)
                                        <i class="mdi mdi-microphone-off red-text tiny"></i> Entretien non reussie
                                    @endif
                                    @if(!is_null($exam->passed) && $exam->passed && !is_null($exam->interview_passed) && $exam->interview_passed)
                                        <i class="mdi mdi-check-circle green-text tiny"></i> Examen et entretien approuvé
                                    @endif
                                </b>
                                <br><small>{{ $exam->updated_at->diffForHumans() }}</small>
                            </div>
                            @if(!$user->hasFinishedSetup())
                                <small>Tentatives restantes: {{ $user->getExamTriesRemaining() }}/3</small>
                            @endif
                        @endforeach
                        @if($user->exams->isEmpty())
                            <small>Aucune tentative</small>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div id="names" class="col s12">
            <p>État civil</p>
            @if($user->names->count() == 0)
                <div class="card-panel">
                    <b>Aucune identité enregistrée.</b>
                </div>
            @endif
            @foreach($user->names->sortByDesc('created_at') as $name)
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12 m6">
                                <b>"{{ $name->name }}"</b>
                                <br>
                                @if($name->needs_review)
                                    <span><i class="mdi mdi-clock tiny"></i> En attente de vérification.</span>
                                @else
                                    @if(!$name->invalid)
                                        @if(is_null($name->end_at))
                                            <b><span><i class="mdi mdi-check-circle tiny green-text"></i> Activé {{ $name->active_at->diffForHumans() }}</span></b>
                                        @endif
                                    @else
                                        <i class="mdi mdi-block-helper tiny red-text"></i> Identité non valide
                                    @endif
                                @endif
                        </div>
                        <div class="col s12 m6">
                            Demande émise {{ $name->created_at->diffForHumans() }}
                            <br>Type:
                            @if($name->type == 'imported')
                                Importé
                            @elseif($name->type == 'setup')
                                Originale
                            @elseif($name->type == 'change')
                                Changement
                            @else
                                ?
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @if($user->name_changes_remaining > 0)
                <div class="card-panel">
                    <b>Changement d'identité</b>
                    <p>Vous avez la possibilité de changer d'identité {{ $user->name_changes_remaining }} {{ $user->name_changes_remaining == 1 ? "fois" : "fois" }}.</p>
                    <small>Suite à votre demande, vous avez la possibilité de changer d'identité.</small>
                    <br>
                    <small>La nouvelle identité fera l'objet d'une vérification par nos équipes</small>
                    <br>
                    <br>
                    @if(!$user->hasFinishedSetup())
                        <a disabled class="btn blue waves-effect"><i class="material-icons left">mode_edit</i> Changer d'identité</a>
                        <br><small>Afin de changer d'identité, veuillez suivre nos instructions. <a href="{{ route('setup-info') }}">Continuer</a></small>
                    @else
                        <a href="{{ route('compte-namechange') }}" class="btn blue waves-effect"><i class="material-icons left">mode_edit</i> Changement d'identité</a>
                    @endif
                    <p>
                        <small>La possibilité de changer d'identité n'est pas commune. Vous pouvez changer d'identité seulement {{ $user->name_changes_remaining }} {{ $user->name_changes_remaining == 1 ? "fois" : "fois" }}.</small>
                        <br>
                        @if(!is_null($user->name_changes_reason)) <small>Dans votre cas, le motif de changement d'identité est le suivant: {{ $user->name_changes_reason or "(non définit)" }}</small> @endif
                    </p>
                </div>
            @else
                <p><small>Aucun changement d'identité possible pour le moment.</small></p>
            @endif
        </div>
    </div>
@endsection
@section('head')
    {{--<link rel="stylesheet" href="/css/flag-icon.min.css">--}}
@endsection
