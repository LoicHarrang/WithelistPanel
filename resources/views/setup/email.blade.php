@extends('layouts.dash')

@section('title', __('setup.email.title'))

@section('content')
    <div id="app">
        <br>
        <div class="container">
            @include('setup.breadcrumb')
            <br>
            <h5>@lang('setup.email.heading')</h5>
            <p>@lang('setup.email.subtitle')</p>
            <div v-if="!chosen">
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel">
                            <form @submit.prevent="enable()" v-if="!verify">
                                <div class="input-field">
                                    <input :disabled="loading || verify" type="email" name="email" v-model="email" required>
                                    <label for="email">@lang('setup.email.enable.label') <span class="red-text">*</span></label>
                                </div>
                                <button v-if="! loading" type="submit" class="btn blue waves-effect">@lang('setup.email.enable.button')</button>
                                <div v-if="loading" class="progress">
                                    <div class="indeterminate"></div>
                                </div>
                                @{{ errors.email }}
                                <br>
                                <br>
                                <div class="divider"></div>
                                <br>
                                <b>@lang('setup.email.enable.advantages')</b>
                                <ol>
                                    <li>Être notifié des avancées de votre dossier</li>
                                    <li>Être notifié des annonces importantes du serveur (mises à jour majeurs, annonce communication, ect...)</li>
                                    <li>Pouvoir participé aux différents concours potentiels</li>
                                </ol>
                                <small>
                                    Nous ne partagerons pas votre email avec des tiers
                                </small>
                            </form>
                            <form @submit.prevent="verifyCode()" v-if="verify">
                                <small>@lang('setup.email.verification.heading')</small>
                                <br><b>@{{ email }}</b>
                                <p>@lang('setup.email.verification.sent')</p>
                                <a href="{{ route('setup-email-reset') }}" class="btn blue waves-effect"><i class="material-icons left">mode_edit</i> Une erreur dans l'adresse ?</a>
                                <div v-if="loading">
                                    <br>
                                    <div class="progress">
                                        <div class="indeterminate"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- TODO remove --}}
            <div v-if="chosen && email_enabled">
                <div class="card-panel">
                    <p>ERROR</p>
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
                chosen: false,
                email_enabled: false,
                email: '{{ is_null(Auth::user()->email) ? "" : Auth::user()->email }}',
                loading: false,
                verify: {{ !is_null(Auth::user()->email_enabled) && Auth::user()->email_enabled && !is_null(Auth::user()->email) ? "true" : "false" }},
                verified: false,
                errors: {},
                code: '',
                taken: false,
                changed: true,
            },
            methods: {
                enable: function() {
                    this.loading = true;
                    axios.post('{{ route('setup-email') }}', {
                            email: this.email,
                            enable: true
                        })
                        .then(function(response) {
                            app.loading = false;
                            if(response.data === "taken") {
                                Materialize.toast("Le courriel est déjà utilisé. Choisissez-en un autre, s'il vous plaît.", 4000);
                            }
                            if(response.data === "verify") {
                                app.verify = true;
                                app.verified = false;
                            }
                            if(response.data === "next") {
                                app.loading = true;
                                window.location.replace("/setup/name");
                            }
                        }).catch(function(error) {
                        app.loading = false;
                        if(error.response.status === 422) {
                            Materialize.toast(error.response.data.errors.email[0], 4000);
                        }
                        });
                },
                verifyCode: function() {
                    app.loading = true;
                    axios.get('{{ route('setup-email') }}', {
                        email: this.email,
                        enable: true
                    })
                        .then(function(response) {
                            app.loading = false;
                            Materialize.toast(response.data, 4000);
                            if(response.data === "next") {
                                app.chosen = true;
                                window.location.replace("/setup/name");
                            }
                        }).catch(function(error) {
                        app.loading = false;
                        if(error.response.status === 422) {
                            this.errors = error.response.data;
                            Materialize.toast(this.errors.email, 4000);
                        }
                    });
                },
                disable: function() {
                    this.loading = true;
                    axios.post('{{ route('setup-email') }}', {
                        enable: false
                    })
                        .then(function(response) {
                            app.loading = true;
                            if(response.data === "next") {
                                app.chosen = true;
                                window.location.replace("/setup/name");
                            }
                        }).catch(function(error) {
                        app.loading = false;
                    });
                }
            },
            watch: {
                email: function() {
                    app.changed = true;
                }
            }
        });
    </script>
@endsection
