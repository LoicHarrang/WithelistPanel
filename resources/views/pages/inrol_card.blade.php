@extends('layouts.dash')
@section('title', $page->title)
@section('content')

    <nav class="white black-text">
        <div class="nav-wrapper">
            <div class="container">
                <ul id="nav-mobile" class="left">
                    <li><a class="waves-effect black-text" href="{{ route('page', ['slug' => 'inrol']) }}"><i class="mdi mdi-account left"></i> Rol</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <br>
        <h5>{{$page->title}}</h5>
        <div class="card-panel">
            {% $page->content %}
        </div>
        <small>Dernière actualisation {{ $page->updated_at->diffForHumans() }}</small>
    </div>
@endsection
