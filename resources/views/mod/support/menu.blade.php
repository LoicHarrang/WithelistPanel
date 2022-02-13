<nav class="back-gd-2">
    <div class="nav-wrapper">
        <div class="container">
            <ul id="nav-mobile" class="left">
                <li><a class="waves-effect" href="{{ route('mod-dashboard') }}"><i class="material-icons left">subdirectory_arrow_left</i></a></li>
                <li><a class="waves-effect" href="{{ route('mod-support-dashboard') }}"><i class="material-icons left">folder_open</i> Modération</a></li>
            </ul>
            <ul class="left hide-on-small-only right">
                @permission('mod-search')
                <li><a class="waves-effect" href="{{ route('sanctions.index') }}">Sanctions</a></li>
                @endpermission
                @permission('mod-search')
                <li><a class="waves-effect" href="{{ route('names.index') }}">Joueurs</a></li>
                @endpermission
            </ul>
        </div>
    </div>
</nav>
