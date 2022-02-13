@extends('layouts.dash')

@section('title', __('setup.welcome.title'))

@section('content')
    <div class="container">
        <div>
            <br>
            <h5>@lang('setup.welcome.regular.heading')</h5>
            <div class="card-panel">
                <p>@lang('setup.welcome.ligne1')</p>
                <p>@lang('setup.welcome.ligne2')</p>
                <p>@lang('setup.welcome.ligne3')</p>
                <p>@lang('setup.welcome.ligne4')</p>
                <br>
                <p>@lang('setup.welcome.ligne5')</p>
                <br>
            </div>
            @if (is_null($user->discord_id) || isset($error))
                <div class="card-panel">
                    @if (isset($error))
                        <span><b class="red-text"><i class="mdi mdi-close-box"></i> Une erreur s'est produite avec la connexion ({{$error}})</b></span>
                    @else
                        <b class="orange-text"><i class="mdi mdi-account-alert"></i> Vous devez vous connecter</b>
                    @endif
                    <p>Pour poursuivre, vous devez vous connecter à l'aide de votre compte Discord.</p>
                    <a href="{{ action('SetupController@discord_login') }}"><img src="https://discordapp.com/assets/fc0b01fe10a0b8c602fb0106d8189d9b.png" style="margin: auto;height: 64px;"></a><br>
                    <small>Cette étape ne vous sera demandé qu'une seule fois</small>
                </div>
            @elseif (!is_null($user->discord_id))
	            <div class="card-panel">
                    <b class="green-text"><i class="mdi mdi-check-all"></i> Vous êtes connecté, {{ $user_discord->user->username }}</b>
                    <p><b>Vous pouvez maintenant continuer</p>
                </div>
            @endif
        </div>
        <div class="card-panel">
            <a href="{{ route('setup-checkgame') }}" class="btn blue waves-effect" @if (is_null($user->discord_id) || isset($error)) disabled @endif>@lang('setup.welcome.start') <i class="material-icons right">navigate_next</i></a>
        </div>
    </div>
@endsection
