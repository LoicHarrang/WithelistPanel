@extends('layouts.dash')
@section('title', 'Sanctions')
@section('content')
    @include('mod.support.menu')
    <div class="container">
        <br>
        <h5>Sanctions en vigueur</h5>
        <br>

        <div class="chip dropdown-button-extend clickable @if(request()->has('type')) black white-text @endif" data-activates='dropdown-type'>
            Filtre: @if(request()->has('type')) <b>@if(request()->input('type') == '1') Banissement(s) @else Avertissement(s) @endif</b> @endif
            <i class="chipicon material-icons">list</i>
        </div>
        <!-- Dropdown Structure -->
        <ul id='dropdown-type' class='dropdown-content'>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Tout</a></li>
            <li class="divider"></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => '1']) }}" class="waves-effect">Banissement(s)</a></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => '2']) }}" class="waves-effect">Avertissement(s)</a></li>
        </ul>

        <br>
        <p>
            <i class="material-icons tiny">list</i> Résultat ({{ $sanctions->total() }})<br>
            <i class="mdi mdi-gavel red-text"></i> : Banissement
            <i class="mdi mdi-alert orange-text"></i> : Avertissement
            <i class="mdi mdi-help-circle orange-text"></i> : Inconnu
            <i class="mdi mdi-pencil-box"></i> : Raison
            <i class="mdi mdi-clock"></i> : Fin de la sanction
            (par ) : Donneur de sanction
        </p>
        @if($sanctions->total() == 0)
            <br>
            <p><b>Aucun résultat.</b></p>
        @endif
        @foreach($sanctions as $sanction)
            @php
                $user = Auth::user()->getOtherUserInfos($sanction->user_id);
                $hammer_man = Auth::user()->getOtherUserInfos($sanction->sanct_by);
                $reason = Auth::user()->reduceChars($sanction->reason,50);
            @endphp
            <a href="{{ route('sanctions.show', $sanction->id) }}">
                <div class="card-panel hoverable black-text">
                    <div class="row">
                        <div class="col s12 m6">
                            @if($sanction->type == 1)
                                <i class="mdi mdi-gavel red-text"></i>
                            @elseif($sanction->type == 2)
                                <i class="mdi mdi-alert orange-text"></i>
                            @else
                                <i class="mdi mdi-help-circle orange-text"></i>
                            @endif
                            <b>{{ Auth::user()->username($user) }}</b>
                            <small>(par {{ Auth::user()->username($hammer_man) }})</small>
                        </div>
                        <div class="col s12 m6">
                            <div class="chip @if(request()->has('type')) black white-text @endif" >
                                <i class="mdi mdi-pencil-box"></i>
                                {{ $reason }}
                            </div>
                            <div class="chip red white-text" >
                                <i class="mdi mdi-clock"></i>
                                @if ($sanction->perm == -1)
                                    Permanant
                                @else
                                    {{ $sanction->end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        {{ $sanctions->appends(\Illuminate\Support\Facades\Input::except('page'))->links() }}
    </div>
@endsection
