@extends('layouts.dash')

@section('title', 'Liste des utilisateurs')

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5>Utilisateurs</h5>
                <form action="" method="GET">
                    <input name="q" type="text" placeholder="Rechercher un nom, SteamID ou GUID" value="@if(isset($q)){{ $q }}@endif" autofocus onfocus="var temp_value=this.value; this.value=''; this.value=temp_value">
                    @foreach(\Illuminate\Support\Facades\Input::except('q') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                <div class="chip dropdown-button-extend clickable @if(request()->has('group')) black white-text @endif" data-activates='dropdown-group'>
                    Groupe: @if(request()->has('group')) {{ request()->input('group') }} @endif
                    <i class="chipicon material-icons">group_work</i>
                </div>
                <!-- Dropdown Structure -->
                <ul id='dropdown-group' class='dropdown-content'>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('group') +  ['group' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Normal</a></li>
                    <li class="divider"></li>
                    @foreach($roles as $role)
                        <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except(['group', 'has-groups']) +  ['has-groups' => true, 'group' => $role->name]) }}" class="waves-effect">{{ $role->display_name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col s12">
                <p><i class="material-icons tiny">list</i> Résultat(s) ({{ $results->total() }})</p>

                <div class="card-panel">
                    @if($results->total() == 0)
                        <p><b>Aucun résultat.</b></p>
                        <p>Veuillez repeter la recherche avec d'autres paramètres.</p>
                    @endif
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Identité</th>
                            <th>SteamID</th>
                            <th>Groupe</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($results as $user)
                            <tr>
                                <td>{{ $user->username($user) }}</td>
                                <td>{{ $user->steamid }}</td>
                                <td>@foreach($user->roles as $role) {{$role->display_name}} @endforeach @if($user->roles()->count() == 0) - @endif</td>
                                <td>@if($user->isDisabled()) <i class="material-icons red-text tooltipped" data-tooltip="Compte désactivé">highlight_off</i> @endif
                                    @if($user->isAdmin()) <i class="material-icons green-text tooltipped" data-tooltip="Administrateur du panel">supervisor_account</i> @endif
                                    @if($user->permissions()->count() > 0) <i class="material-icons black-text tooltipped" data-tooltip="Permissions Individuelles">lock</i> @endif
                                </td>
                                <td><a href="{{ route('acl-users-edit', $user) }}" class="btn-flat"><i class="material-icons">mode_edit</i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection
