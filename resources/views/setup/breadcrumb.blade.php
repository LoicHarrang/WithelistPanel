<nav class="back-gd-2 white-text hide-on-med-and-down">
    <div class="nav-wrapper">
        <div class="col s12">
            <p></p>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/info') white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.data')</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/email') white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.email')</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/name') white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.name')</a>
            @if(!Auth::user()->imported_exam_exempt)
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/rules') white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.rules')</a>
            {{--<a class="breadcrumb @if(request()->is('setup/exam') || request()->is('setup/exam/*')) white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.test')</a>--}}
            @endif
            @if(!config('dash.forum_skip'))
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/forum') white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.forum')</a>
            @endif
            @if(!Auth::user()->imported_exam_exempt)
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/interview' || (request()->is('setup/exam') || request()->is('setup/exam/*'))) white-text @else grey-text @endif">@lang('setup.breadcrumb.steps.interview')</a>
            @endif
        </div>
    </div>
</nav>
