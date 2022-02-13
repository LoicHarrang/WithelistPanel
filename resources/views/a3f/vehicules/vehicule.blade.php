@extends('layouts.dash')

@section('title', 'Mes véhicules')

@section('content')
    <br>
    <div class="container">
        <h5>Mes vehicules</h5>
            @if(!isset($player->vehicles))
                <div class="col s12">
                    <div class="card-panel">
                        <i class="mdi mdi-alert-octagram"></i> Vous n'avez aucun véhicule enregistré.</a>
                    </div>
                </div>
            @else
                <div id="data" class="col s12">
                    <div class="row">
                        <div class="col s12 l6">
                            <p>Liste de vos véhicules</p>
                            <div class="card-panel">
                                @foreach ($player->vehicles as $vehicle)
                                    <div class="input-field">
                                       <a href="#{{$vehicle->id}}" class="waves-effect back-gd-text-2" style="display: block;">
                                            <span>{{$vehicle->classname}}</span><br>
                                            <small>{{$vehicle->plate}}</small>
                                       </a>
                                   </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col s12 l6">
                            <p>Assurance</p>
                            <div class="card-panel">
                                <p>
                                    <small>Détail de l'assurance...</small>
                                    <br><span>
                                </span>
                                </p>
                            </div>
                            <p>Détails (a afficher seulement quand on séléctionne un véhicule sur la panneau de droite)</p>
                            <div class="card-panel">
                                <p>
                                    <small>Nom du véhicule:</small>
                                <ul>
                                    <li>Détail sur véhicule (couleur, dammage ect...)</li>
                                </ul>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
@endsection