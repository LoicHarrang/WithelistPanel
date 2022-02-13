@extends('layouts.dash')

@section('title', 'Mes propriétes')

@section('content')
    <br>
    <div class="container">
        <h5>Mes propriétés</h5>
            <div class="col s12">
                <div class="card-panel">
                    <i class="mdi mdi-alert-octagram"></i> Vous n'avez aucune propriété enregistré.</a>
                </div>
            </div>
            <div id="data" class="col s12">
                <div class="row">
                    <div class="col s12 l6">
                        <p>Assurance</p>
                        <div class="card-panel">
                            <p>
                                <small>Détail de l'assurance...</small>
                                <br><span>
                            </span>
                            </p>
                        </div>
                        <p>Détails (a afficher seulement quand on séléctionne une propriété sur la panneau de droite)</p>
                        <div class="card-panel">
                            <p>
                                <small>Informations sur votre propriété:</small>
                            <ul>
                                <li>Détail sur la propriété (Garage ou non)</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="col s12 l6">
                        <p>Liste de vos propriétés</p>
                        <div class="card-panel">
                           <p>
                                <span>Propriété 1</span><br>
                                <small>Position (ville la plus proche?)</small>
                            </p>
							<p>
                                <span>Propriété 2</span><br>
                                <small>Position (ville la plus proche?)</small>
                            </p>
                            <p>
                                <span>Propriété 3</span><br>
                                <small>Position (ville la plus proche?)</small>
                            </p>
                            <p>
                                <span>Propriété 4</span><br>
                                <small>Position (ville la plus proche?)</small>
                            </p>
                            <p>
                                <span>Propriété 5</span><br>
                                <small>Position (ville la plus proche?)</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
