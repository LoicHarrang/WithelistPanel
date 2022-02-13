@extends('layouts.dash')

@section('title', 'Nouvel utilisateur')

@section('content')
    @include('acl.users.menu')
<div class="container">
    <h5>Ajouter un utilisateur</h5>
    @include('common.errors')
    <div class="card-panel">
        <form action="{{ route('acl-users-new') }}" method="POST">
            {{ csrf_field() }}
            <p>Informations</p>
            <div class="row">
                <div class="input-field col s6">
                    <input id="name" name="name" type="text" required value="{{ old('name') }}">
                    <label for="name">Identité <span class="red-text">*</span></label>
                </div>
                <div class="col s6">
                    <span>Identité complète de l'utilisateur.</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="steamid" name="steamid" type="text" required value="{{ old('steamid') }}">
                    <label for="steamid">SteamID <span class="red-text">*</span></label>
                </div>
                <div class="col s6">
                    <span>SteamID necessaire pour se connecter</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="email" name="email" type="email" required value="{{ old('email') }}">
                    <label for="email">Adresse Email <span class="red-text">*</span></label>
                </div>
                <div class="col s6">
                    <span>L'adresse email est necessaire pour les notifications.</span>
                </div>
            </div>
            <p>Groupe</p>
            <div class="row">
                <div class="col s12">
                    <select multiple style="width: 100%" name="roles[]" id="roles" class="select2">
                        @if(!is_null(old('roles')))
                            @foreach(old('roles') as $id)
                                <option value="{{ $id }}" selected="selected">{{ \App\Role::findOrFail($id)->display_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <br>
            <button class="btn green waves-effect" type="submit">Creer l'utilisateur</button>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script>


        function formatRole (role) {
            return  $(
                '<span><b>' + role.text + '</b> <small>' + role.description + '</small></span>'
            );
        };

        $('select').select2({
            data: [@foreach($roles as $role){ id: {{$role->id}}, text: "{{ $role->display_name }}", description: "{{ $role->description }}" },@endforeach],
            templateResult: formatRole,
        });
    </script>
@endsection
