@extends('layouts.dash')

@section('title', $user->username($user))

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <h5>"{{ $user->username($user) }}"</h5>
        @include('common.errors')
        <div class="row">
            <div class="col s12 m6">
                <p>@lang('acl.users.edit.data.heading')</p>
                <div class="card-panel">
                    <form action="{{ route('acl-users-edit', $user) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="input-field col s12">
                                <input disabled type="text" required value="{{ $user->username($user) }}">
                                <label for="steamid">@lang('acl.users.edit.data.id')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input disabled type="text" required value="{{ $user->steamid }}">
                                <label for="steamid">@lang('acl.users.edit.data.steamid')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="email" disabled required value="{{ $user->email or "?" }}">
                                <label for="email">@lang('acl.users.edit.data.email')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <input @if($user->isAdmin()) disabled @endif type="checkbox" name="disabled" aria-invalid="disabled" class="filled-in" id="filled-in-box" @if(!is_null(old('anonymous'))) checked="checked" @elseif($user->disabled) checked @endif/>
                                <label for="filled-in-box">@lang('acl.users.edit.data.disabled')</label>

                                <input type="checkbox" name="test" class="filled-in">
                            </div>
                        </div>
                        <br>
                        @if($user->isAdmin()) 
                            <small>Note: les informations d'un administrateur de panel ne peuvent pas être modifié, hors-mis le groupe.</small> 
                        @endif
                        <br>
                        <p>@lang('acl.users.edit.groups.heading')</p>
                        <div class="row">
                            <div class="col s12">
                                <select id="select-roles" multiple style="width: 100%" name="roles[]" id="roles" class="select2">
                                    @if(old('roles'))
                                        @foreach(old('roles') as $id)
                                            <option value="{{ $id }}" selected="selected">{{ \App\Role::findOrFail($id)->display_name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($user->roles as $role)
                                            <option selected value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <br>
                        <button class="btn green waves-effect" type="submit">@lang('acl.users.edit.submit')</button>
                    </form>
                </div>
            </div>
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

        function formatPermission (permission) {
            return  $(
                '<span><b>' + permission.text + '</b> <small>' + permission.description + '</small></span>'
            );
        };

        $('#select-roles').select2({
            data: [@foreach($roles as $role){ id: {{$role->id}}, text: "{{ $role->display_name }}", description: "{{ $role->description }}" },@endforeach],
            templateResult: formatRole,
        });
    </script>
@endsection
