@extends('layouts.dash')

@section('title', __('setup.info.title'))

@section('content')
    <br>
    <div class="container" id="app">
        @include('setup.breadcrumb')
        <br>
        <h5>Nationalité</h5>
        @include('common.errors')
        <form action="{{ route('setup-info') }}" method="POST" onsubmit="return confirm('Êtes vous sûr de votre choix ? Il sera DÉFINITIF');">
            {{ csrf_field() }}
            <div class="card-panel">
                @if(is_null(auth()->user()->country))
                @php
                    $countryFR = Countries::where('cca2', "FR")->first();
                    $countryBE = Countries::where('cca2', "BE")->first();
                @endphp
                <b>ATTENTION</b><br>
                <small>Ce choix est définitif, vous ne pourrez pas revenir en arrière</small><br><br>
                <label>Nationalité <span class="red-text">*</span></label>
                <select name="country" id="country" class="browser-default" required>
                    <option value="" disabled selected>@lang('setup.info.country.placeholder')</option>
                    <option value="FR">{!! $countryFR->flag['flag-icon'] !!} France</option>
                    <option value="BE">{!! $countryBE->flag['flag-icon'] !!} Belgique</option>
                </select>
                <small>Séléctionez votre nationalé définitive de votre identité RP</small>
                @endif
            </div>
            <div class="card-panel">
                <button type="submit" class="btn blue waves-effect">@lang('setup.info.continue') <i class="material-icons right">navigate_next</i></button>
            </div>
        </form>
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('select').material_select();
    });
</script>
@endsection