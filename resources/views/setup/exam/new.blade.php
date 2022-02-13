@extends('layouts.dash')

@section('title', __('setup.exam.new.title'))

@section('content')
    <div class="container">
        <br>
        @include('setup.breadcrumb')
        <br>
        <h5>@lang('setup.exam.new.heading')</h5>
        <p>@lang('setup.exam.new.subtitle')</p>
        <div class="card-panel">
            <ol>
                <li><p>@lang('setup.exam.new.tips.duration', ['duration' => config('exam.duration')])</p></li>
                <li><p>@lang('setup.exam.new.tips.countdown_start')</p></li>
                <li><p>@lang('setup.exam.new.tips.one_way')</p></li>
                <li><p>@lang('setup.exam.new.tips.limited_attempts')</p></li>
                <li><p>@lang('setup.exam.new.tips.wrong_answers_insist')</p></li>
            </ol>
        </div>
        @if(!Auth::user()->imported_exam_exempt && Auth::user()->imported)
            <div class="card-panel">
                <p>@lang('setup.exam.new.imported.title')</p>
                <small>@lang('setup.exam.new.imported.report_errors')</small>
            </div>
        @endif
        @if(!config('exam.enabled'))
            <div class="card-panel">
                <b>@lang('setup.exam.new.unavailable.title')</b>
                <p>@lang('setup.exam.new.unavailable.subtitle')</p>
                <a href="{{ route('setup-rules') }}" class="btn white blue-text waves-effect"><i class="material-icons left">navigate_before</i> @lang('setup.exam.new.unavailable.back') </a>
            </div>
        @else
            <div class="card-panel">
                <div class="row">
                    @if(Auth::user()->hasExamCooldown())
                        <div class="col s12">
                            <b><i class="mdi mdi-information"></i> @lang('setup.exam.new.cooldown.title')</b>
                            <p>@lang('setup.exam.new.cooldown.subtitle')</p>
                            <p>@lang('setup.exam.new.cooldown.remaining', ['time' => Auth::user()->hasExamCooldown(true)->setTimezone(Auth::user()->timezone)->format('d/m/Y (H:i)'), 'timezone' => Auth::user()->timezone])</p>
                            <p>@lang('setup.exam.new.cooldown.tip')</p>
                            <p>{{ trans_choice('setup.exam.new.cooldown.tries_remaining', Auth::user()->getExamTriesRemaining(), ['value' => Auth::user()->getExamTriesRemaining()]) }}</p>
                        </div>
                    @endif
                    <div class="col s12 m6">
                        <a href="{{ route('setup-rules') }}" class="btn white blue-text waves-effect"><i class="material-icons left">navigate_before</i> @lang('setup.exam.new.back') </a>
                    </div>
                    <div class="col s12 m6">
                        @if(Auth::user()->hasExamCooldown())
                            <button class="btn blue right" disabled>@lang('setup.exam.new.start') <i class="material-icons right">navigate_next</i></button>
                        @else
                            <form method="POST" action="{{ route('setup-exam') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="right btn blue waves-effect">@lang('setup.exam.new.start') <i class="material-icons right">navigate_next</i></button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <p>@lang('setup.exam.new.faq.title')</p>
        <div class="card-panel">
            @lang('setup.exam.new.faq', ['tries' => Auth::user()->getExamTriesRemaining()])
        </div>
    </div>
@endsection
