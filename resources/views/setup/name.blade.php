@extends('layouts.dash')

@section('title', __('setup.name.title'))

@section('content')
    <div id="app">
        <br>
        <div class="container">
            @include('setup.breadcrumb')
            <br>
            <h5>@lang('setup.name.heading')</h5>
            <p>Déclaration de votre identité en jeu (<b>définitive</b>)</p>
            <div class="row">

                <div v-if="!success" class="col s12 l6">
                    <div v-if="! rulesClosed" class="card-panel">
                        <b>Informations sur les identités</b>
                        {{-- TODO convertir a página --}}
                        <p>
                            Votre identité <b>doit être écrite </b> <b>de manière correcte et intelligible</b>.
                            <br>
                        </p>
                        <p>
                            Veuillez ne pas utiliser de diminutif.
                            <br>
                            <small>Interdit: Dani, JP</small>
                        </p>
                        <p>
                            Votre identité doit être <b>sans caractères spéciaux</b>.
                            <br>
                            <small>Interdit: 毛泽东, Владимр, Björk Guðmundsdóttir</small>
                        </p>
                        <p>
                          Votre identité <b>ne doit pas contenir d'apostrophe</b>.
                            <br>
                            <small>Interdit: O'Donell, Xing-Min</small>
                        </p>
                        <p>
                            Votre <b>date de naissance doit être réaliste</b>, et en <b>corrélation avec votre RP</b>.
                            <br>
                            <small>Ex: 26/07/1974</small>
                        </p>
                        <p>
                            Votre <b>ville de naissance doit exister</b>, et en <b>corrélation avec votre RP</b>.
                            <br>
                            <small>Nous serons également intransigeant sur ce point</small>
                        </p>
                        <p>
                            Le sexe de votre personnage doit être le même que le vôtre, <b>il est interdit de prendre le sexe opposé</b>.
                            <br>
                            <small>Nous serons intransigeant sur ce point</small>
                        </p>
                        <p>
                            Votre taille <b>doit être réaliste</b>.
                            <br>
                            <small>Tout manquement à ce réalisme sera sanctionné</small>
                        </p>
                        <p>
                            Pour finir, nous vous rappelons que ces informations seront celles inscrites sur le serveur, et donc, <b>définitive</b>, alors choisissez les avec prudence.
                            <br>
                            <small>En cas d'erreur, merci de nous contacter sur Discord</small>
                        </p>
                        <p>
                            <b><small>Votre identité sera soumise à une approbation de nos équipes, nous nous réservons donc le droit de la refuser pour quelconque(s) motif(s)</small></b>
                        </p>
                        <a @click.prevent="dismissRules()" class="btn waves-effect white blue-text">@lang('setup.name.rules.accept')</a>
                    </div>
                    <form action="" method="POST" @submit.prevent="check()">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="firstName" type="text" id="firstName" data-length="14" placeholder="@lang('setup.name.form.name.placeholder')">
                                        <label for="firstName">@lang('setup.name.form.name.label') <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="lastName" type="text" id="lastName" data-length="14" placeholder="@lang('setup.name.form.lastname.placeholder')">
                                        <label for="lastName">@lang('setup.name.form.lastname.label') <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div style="margin-bottom: 1rem !important;">
                                        <p>
                                            <label for="sexe">Sexe <span class="red-text">*</span></label><br>
                                            <input :disabled="!rulesClosed || loading" value="1" v-model="sexe" class="with-gap" name="sexe" type="radio" id="1" />
                                            <label for="1">Homme</label>
                                             <input :disabled="!rulesClosed || loading" value="2" v-model="sexe" class="with-gap" name="sexe" type="radio" id="2" />
                                            <label for="2">Femme</label>
                                        </p>
                                    </div>
                                </div>
                                 <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="taille" type="text" id="taille" data-length="14" placeholder="Ex: 180">
                                        <label for="taille">Taille (sans le 'cm') <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                 <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="lieuNaiss" type="text" id="lieuNaiss" data-length="14" placeholder="Ex: Paris">
                                        <label for="lieuNaiss">Ville de naissance <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <small class="grey-text">@lang('setup.info.birth.label') <span class="red-text">*</span></small><br>
                                        <input :disabled="!rulesClosed || loading" v-model="birthDay" id="birthDay" type="date" placeholder="Ex: 02/02/20">
                                        <small><b>Format dd/mm/aaaa obligatoire - Ex : 13/06/2002</b></small>
                                    </div>
                                </div>
                                <div class="col s12">

                                    <div v-if="!nameChecked || taken">
                                        <p v-if="fullname.length > 17"><small>@lang('setup.name.form.charlimit') @{{ fullname.length }}</small></p>
                                        <button :disabled="!rulesClosed || !changed || loading || firstName === '' || lieuNaiss === '' || lastName === '' || birthDay === '' || sexe === '' || fullname.length > 17" class="btn blue waves-effect">@lang('setup.name.form.check')</button>
                                    </div>
                                    <div v-cloak v-if="nameChecked && !taken">
                                        <p>
                                            <span class="green-text"><i class="material-icons tiny">check_circle</i> @lang('setup.name.form.available')</span>
                                            <br>
                                            <small>@lang('setup.name.form.available.warning')</small>
                                        </p>
                                        <button :disabled="loading" @click.prevent="choose()" class="btn green waves-effect">@lang('setup.name.form.available.request')</button>
                                        <br><small>@lang('setup.name.form.available.edit')</small>
                                    </div>

                                    <div v-cloak v-if="nameChecked && taken">
                                        <br>
                                        <p>
                                            <span class="red-text">@lang('setup.name.form.taken')</span>
                                            <br>
                                            <small>@lang('setup.name.form.taken.edit')</small>
                                        </p>
                                    </div>

                                    <div v-if="loading">
                                        <br>
                                        <br>
                                        <div class="progress">
                                            <div class="indeterminate"></div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                    <div v-cloak v-if="rulesClosed" class="card-panel">
                        <b>Informations sur les identités</b>
                        <p>
                            Votre identité <b>doit être écrite </b> <b>de maniere correcte et intelligible</b>.
                            <br>
                        </p>
                        <p>
                            Veuillez ne pas utiliser de diminutif.
                            <br>
                            <small>Interdit: Dani, JP</small>
                        </p>
                        <p>
                            Votre identité doit être <b>sans caractères spéciaux</b>.
                            <br>
                            <small>Interdit: 毛泽东, Владимр, Björk Guðmundsdóttir</small>
                        </p>
                        <p>
                          Votre identité <b>ne doit pas contenir d'apostrophe</b>.
                            <br>
                            <small>Interdit: O'Donell, Xing-Min</small>
                        </p>
                        <p>
                            Votre <b>date de naissance doit être réaliste</b>, et en <b>corrélation avec votre RP</b>
                            <br>
                            <small>Ex: 26/07/1974</small>
                        </p>
                        <p>
                            Votre <b>ville de naissance doit exister</b>, et en <b>corrélation avec votre RP</b>.
                            <br>
                            <small>Nous serons également intransigeant sur ce point</small>
                        </p>
                        <p>
                            Le sexe de votre personnage doit être le même que le vôtre, <b>il est interdit de prendre le sexe opposé</b>
                            <br>
                            <small>Nous serons intransigeant sur ce point</small>
                        </p>
                        <p>
                            Votre taille <b>doit être réaliste</b>.
                            <br>
                            <small>Tout manquement à ce réalisme sera sanctionné</small>
                        </p>
                        <p>
                            Pour finir, nous vous rappelons que ces informations seront celles inscrites sur le serveur, et donc, <b>définitive</b>, alors choisissez les avec prudence.
                            <br>
                            <small>En cas d'erreur, merci de nous contacter sur Discord</small>
                        </p>
                    </div>
                </div>
                <div class="col hide-on-med-and-down m6">
                    <div id="dni">
                        <img id="identity_card" src="" alt="" style="width: 80%; height: auto;">
                    </div>
                </div>
                <div v-cloak v-if="success" class="col l6 s12 ">
                    <div class="card-panel green white-text">
                        <h5>@lang('setup.name.success.heading')</h5>
                        <p>@lang('setup.name.success.name') <b>@{{ fullname }}</b>.</p>
                        <p><small>Votre demande sera soumise à une validation auprès de nos équipes</small></p>
                        <a href="" class="btn white green-text waves-effect">@lang('setup.name.success.continue') <i class="material-icons right">navigate_next</i></a>
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
                nameChecked: false,
                taken: true,
                loading: false,
                firstName: "",
                lastName: "",
                sexe: "",
                birthDay: "",
                taille: "",
                lieuNaiss: "",
                rulesClosed: false,
                changed: false,
                success: false
            },
            methods: {
                dismissRules: function() {
                    this.rulesClosed = true;
                },
                update: function() {
                    this.checked = false;
                    
                },
                check: function() {
                    if(this.nameChecked && !this.taken) {
                        return;
                    }
                    this.loading = true;
                    this.checked = false;
                    this.taken = true;
                    this.changed = false;
                    this.success = false,
                    axios.post('{{ route('setup-name-check') }}', {
                        firstName: this.firstName,
                        lastName: this.lastName,
                        sexe: this.sexe,
                        date: this.birthDay,
                        taille: this.taille,
                        lieuNaiss: this.lieuNaiss,
                    })
                    .then(function(response) {
                        app.loading = false;
                        if(response.data === "taken") {
                            app.taken = true;
                            app.nameChecked = true;
                        } else {
                            app.taken = false;
                            app.nameChecked = true;
                            $('#identity_card').attr('src', response.data);
                            console.log(response.data);
                        }
                    }).catch(function(error) {
                        Materialize.toast(error.response.data.message, 4000);
                        app.loading = false;
                    });
                },
                choose: function() {
                    this.loading = true;
                    axios.post('{{ route('setup-name') }}',{
                        firstName: this.firstName,
                        lastName: this.lastName,
                        sexe: this.sexe,
                        date: this.birthDay,
                        taille: this.taille,
                        lieuNaiss: this.lieuNaiss
                    })
                    .then(function(response) {
                        app.loading = false;
                        if(response.data === "taken") {
                            app.taken = true;
                            app.nameChecked = true;
                        } else if(response.data === "OK") {
                            app.success = true;
                        }
                    }).catch(function(error) {
                    Materialize.toast(error.response.data, 4000);
                    app.loading = false;
                    });
                }
            },
            computed: {
                fullname: function() {
                    return this.firstName + " " + this.lastName;
                },
                fullnameDNI: function() {
                    try {
                        var name = this.firstName.toUpperCase() + " " + this.lastName.toUpperCase();
                        if(name.length > 17) {
                            return "";
                        }
                        return name;
                    }catch (error) {
                        return "";
                    }
                },
            },
            watch: {
                firstName: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                },
                lastName: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                },
                sexe: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                },
                birthDay: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                },
                taille: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                },
                lieuNaiss: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                }
            }
        });
    </script>

@endsection
