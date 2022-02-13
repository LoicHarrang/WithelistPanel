@extends('layouts.dash')

@section('title', 'Accueil')

@section('content')

<br>

<div class="container" id="app">
    <div class="row">
      @include('home.player')
    </div>
</div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                opening: moment.tz(new Date('{{ $opening }}'), '{{ config('app.timezone') }}'),
                timezone: '{{ Auth::user()->timezone }}'
            }
        });
    </script>
@endsection
