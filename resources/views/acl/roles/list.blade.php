@extends('layouts.dash')

@section('title', __('acl.roles.list.title'))

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h5>Groupe(s)</h5>
            </div>
            <div class="col s12">
                <div class="card-panel">
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>@lang('acl.roles.list.table.heading.name')</th>
                            <th>@lang('acl.roles.list.table.heading.members')</th>
                            <th>@lang('acl.roles.list.table.heading.actions')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->users()->count() }}</td>
                                <td><a href="{{ route('dash-roles-edit', $role) }}" class="btn-flat"><i class="material-icons">mode_edit</i></a></td>
                            </tr>
                        @endforeach
                        @if($roles->count() == 0)
                            {!! __('acl.roles.list.table.empty') !!}
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection