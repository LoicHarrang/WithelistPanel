@extends('layouts.dash')

@section('title', __('setup.checkgame.title'))

@section('content')
    <div id="app">
        <br>
        <div class="container">
            @include('setup.breadcrumb')
            <br>
            <h5>@lang('setup.checkgame.title')</h5>
            <p>@lang('setup.checkgame.subtitle')</p>
            <div v-cloak v-if="error">
                <p class="red-text">@lang('setup.checkgame.error.unknown')</p>
            </div>
            <div v-if="!error">
                <p v-if="!checked">@lang('setup.checkgame.loading')</p>
                <div v-if="loading || !checked">
                    <div class="card-panel">
                        <div class="progress">
                            <div class="indeterminate"></div>
                        </div>
                    </div>
                </div>
                <div v-cloak v-if="checked && !purchased && !loading">
                    <div class="card-panel">
                        <b>@lang('setup.checkgame.private.heading')</b>
                        <p>@lang('setup.checkgame.private.subtitle')</p>
                        <a href="#" @click.prevent="instructions = true" v-if="!instructions" class="waves-effect btn white blue-text">@lang('setup.checkgame.private.reveal')</a>
                        <div v-if="instructions">
                            <small>@lang('setup.checkgame.private.howto.heading')</small>
                            <p>
                                <b>@lang('setup.checkgame.private.howto.1.title')</b>
                                <br><span>{!! __('setup.checkgame.private.howto.1.link', ['steamid' => auth()->user()->steamid]) !!}</span>
                            </p>
                            <p>
                                <b>@lang('setup.checkgame.private.howto.2.title')</b>
                                <br><img src="https://i.imgur.com/kl6dwDh.png" height="100">
                            </p>
                            <p>
                                @lang('setup.checkgame.private.howto.tip')
                            </p>
                        </div>
                    </div>
                    <div class="card-panel">
                        @lang('setup.checkgame.buy', ['name' => config('app.name')])
                    </div>
                    @if(! Agent::is('Windows'))
                        <div class="card-panel red white-text">
                            @lang('setup.checkgame.oswarning', ['name' => config('app.name')])
                        </div>
                    @endif
                    <div class="card-panel">
                        <iframe src="https://store.steampowered.com/widget/107410/31539/" frameborder="0" style="width: 100%" height="190"></iframe>
                    </div>
                    <div class="card-panel">
                        <button :disabled="loading|cooldown" @click.prevent="check()" class="btn blue waves-effect"><i class="material-icons left">refresh</i> @lang('setup.checkgame.recheck')</button>
                    </div>
                </div>
                <div v-cloak v-if="purchased">
                    <div class="card-panel green white-text">
                        @lang('setup.checkgame.success.text')
                        <a href="{{ route('setup-info') }}" class="btn white green-text waves-effect">@lang('setup.checkgame.success.continue') <i class="material-icons right">navigate_next</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.0/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var app = new Vue({
            el: '#app',
            data: {
                checked: false,
                loading: false,
                purchased: false,
                error: false,
                cooldown: false,
                instructions: false
            },
            methods: {
                check: function() {
                    this.loading = true;
                    this.cooldown = true;
                    axios.post('{{ route('setup-checkgame') }}', {})
                    .then(function(response) {
                        app.loading = false;
                        app.checked = true;
                        if(response.data === true) {
                            app.purchased = true;
                        }
                        setTimeout(function() { app.cooldown = false}, 5000);
                    }).catch(function() {
                        app.loading = false;
                        app.error = true;
                        setTimeout(function() { app.cooldown = false}, 5000);
                    });
                }
            },
            created: function() {
                this.check();
                console.log('created');
            }
        });
    </script>
@endsection