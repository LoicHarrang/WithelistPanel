@extends('layouts.dash')

@section('title', 'Examen terminé')

@section('content')
    <div class="container">
        <br>
        @include('setup.breadcrumb')
        <br>
        <h5>Nous avons bien recu vos réponses</h5>
        <div class="card-panel">
            <p><b><i class="mdi mdi-check-circle green-text"></i> L'examen est terminé.</b></p>
            <p>Votre examen sera examiné, afin de vérifier vos réponses.</p>
            <p>En attendant, votre dossier est placé en attente.</p>
        </div>
        <p>Questions Fréquentes</p>
        <div class="card-panel">
            <p>
                <b>Dans combien de temps sera corrigé mon examen ?</b>
                <br>
                Normalement, la correction prend moins d'une journée.
            </p>
            <p>
                <b>Comment savoir la note que j'ai eu ?</b>
                <br>
                Nous ne donnons aucune note à nos examens.
                <br>
                Mais nous vous signaleront par email lorsque nos équipes auront fournie une réponse a votre dossier.
            </p>
            <p>
                <b>Que se passe-t-il si j'echoue cet examen ?</b>
                <br>
                Si il s'agit de votre premiere tentative, pas de soucis. Vous avez jusqu'a 3 tentatives.
                <br>Si vous échoué pour la troisième fois, vous ne pouvez plus prétendre a votre inscription.
            </p>
            <p>
                <b>Y a-t-il une correction de l'examen ?</b>
                <br>
                Nous ne donnons pas la correction des examens
                <br>Nous contrôlons en permanence l'impartialité de nos équipes pour assurer la qualité des corrections.
                <br>Si vous pensez qu'une injustice a été commise, vous pouvez contacter l'administration.
            </p>
            <p>
                <b>Après mon examen, reste-t-il une étape ?</b>
                <br>
                L'examen n'est pas la dernière étape, il vous reste l'entretien oral avec nos équipes.
            </p>
            <p>
                <b>Combien de tentatives me reste-t-il ?</b>
                <br>
                Il vous reste {{ Auth::user()->getExamTriesRemaining() }} tentatives, sans compter celle-ci.
            </p>
            <p>
                <b>J'ai une question qui n'apparait pas ici !</b>
                <br>
                Nous vous invitons à nous contacter.
                <br>
            </p>
        </div>
    </div>
@endsection
