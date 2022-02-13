@extends('layouts.dash')

@section('title', 'Changement d\'identité')

@section('content')
    <div id="app">
        <div class="container">
            <br>
            <h5>Changement d'identité</h5>
            <p>Validation d'une nouvelle identité.</p>
            <div class="row">

                <div v-if="!success" class="col s12 l6">
                    <div v-if="! rulesClosed" class="card-panel">
                        <b>Règlement sur les identités</b>
                        <p>
                            Identité réaliste, approprié et correcte exigé.
                            <br>
                        </p>

                        <a @click.prevent="dismissRules()" class="btn waves-effect white blue-text">Accetper</a>
                    </div>
                    <form action="" method="POST" @submit.prevent="check()">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="firstName" type="text" id="firstName" data-length="14" placeholder="Manolo">
                                        <label for="firstName">Prénom <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="lastName" type="text" id="lastName" data-length="14" placeholder="Pérez">
                                        <label for="lastName">Nom <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">

                                    <div v-if="!nameChecked || taken">
                                        <p v-if="fullname.length > 17"><small>La limite de caractère est de 17. Votre identité en contient @{{ fullname.length }}.</small></p>
                                        <button :disabled="!rulesClosed || !changed || loading || firstName === '' || lastName === '' || fullname.length > 17" class="btn blue waves-effect">Consulter</button>
                                    </div>
                                    <div v-cloak v-if="nameChecked && !taken">
                                        <p>
                                            <span class="green-text"><i class="material-icons tiny">check_circle</i> Identité Disponible.</span>
                                            <br>
                                            <small>Une fois choisi, <b>votre identité ne pourra plus être changé</b>.</small>
                                        </p>
                                        <button :disabled="loading" @click.prevent="choose()" class="btn green waves-effect">Continuer</button>
                                    </div>

                                    <div v-cloak v-if="nameChecked && taken">
                                        <br>
                                        <p>
                                            <span class="red-text">La combinaison nom / prénom n'est pas disponible.</span>
                                            <br>
                                            <small>Merci de modifier votre identité.</small>
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
                      <b>Règlement sur les identités</b>
                      <p>
                          Identité réaliste, approprié et correcte exigé.
                          <br>
                      </p>
                    </div>
                </div>
                <div class="col hide-on-med-and-down m6">
                    <div id="dni">
                        <img src="/img/dni2.png" alt="">
                        <b v-model="name" id="dni_name">@{{ fullnameDNI }}</b>
                        <b id="dni_id">{{ $user->dni }}</b>
                    </div>
                </div>
                <div v-cloak v-if="success" class="col l6 s12 ">
                    <div class="card-panel green white-text">
                        <h5>Félicitation !</h5>
                        <p>Vous avez choisi <b>@{{ fullname }}</b>.</p>
                        <p><small>L'identité sera vérifié par nos opérateurs.
                                <br>Nous vous notifierons lors de son acceptation finale.</small></p>
                        <a href="{{ route('home') }}" class="btn white green-text waves-effect"><i class="material-icons left">home</i> Retour</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('head')
    <style>
        #dni {
            position: relative;
        }
        #dni_name {
            font-family: Helvetica;
            position:absolute;
            top: 56px;
            left: 164px
        }
        #dni_id {
            font-size: 85%;
            font-family: Helvetica;
            position:absolute;
            top: 105px;
            left: 164px
        }
    </style>
@endsection
@section('js')
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
                rulesClosed: false,
                changed: false,
                success: false,
                maleNames: [
                    'Antonio',
                    'Jose',
                    'Manuel',
                    'Francisco',
                    'Juan',
                    'David',
                    'Javier',
                    'Jesús',
                    'Daniel',
                    'Carlos',
                    'Miguel',
                    'Alejandro',
                    'Rafael',
                    'Ángel',
                    'Fernando',
                    'Pablo',
                    'Luis',
                    'Sergio',
                    'Jorge',
                    'Alberto',
                    'Álvaro',
                    'Diego',
                    'Adrián',
                    'Raúl',
                    'Enrique',
                    'Ramón',
                    'Vicente',
                    'Iván',
                    'Rubén',
                    'Óscar',
                    'Andrés',
                    'Joaquín',
                    'Santiago',
                    'Eduardo',
                    'Víctor',
                    'Roberto',
                    'Jaime',
                    'Mario',
                    'Ignacio',
                    'Alfonso',
                    'Salvador',
                    'Ricardo',
                    'Marcos',
                    'Jordi',
                    'Emilio',
                    'Julián',
                    'Julio',
                    'Guillermo',
                    'Gabriel',
                    'Tomás',
                    'Agustín',
                    'Marc',
                    'Gonzalo',
                    'Félix',
                    'Hugo',
                    'Ismael',
                    'Cristian',
                    'Mariano',
                    'Josep',
                    'Domingo',
                    'Aitor',
                    'Martín',
                    'Alfredo',
                    'Felipe',
                    'Héctor',
                    'César',
                    'Iker',
                    'Gregorio',
                    'Alex',
                    'Rodrigo',
                    'Albert',
                    'Xavier',
                    'Lorenzo'
                ],
                femaleNames: [
                    'María',
                    'Carmen',
                    'Isabel',
                    'Ana',
                    'Laura',
                    'Cristina',
                    'Antonia',
                    'Marta',
                    'Dolores',
                    'Lucía',
                    'Pilar',
                    'Elena',
                    'Sara',
                    'Paula',
                    'Mercedes',
                    'Raquel',
                    'Beatriz',
                    'Nuria',
                    'Silvia',
                    'Julia',
                    'Patricia',
                    'Irene',
                    'Andrea',
                    'Rocío',
                    'Mónica',
                    'Rocío',
                    'Alba',
                    'Ángela',
                    'Sonia',
                    'Alicia',
                    'Sandra',
                    'Susana',
                    'Marina',
                    'Yolanda',
                    'Natalia',
                    'Eva',
                    'Noelia',
                    'Claudia',
                    'Verónica',
                    'Amparo',
                    'Carolina',
                    'Carla',
                    'Nerea',
                    'Lorena',
                    'Sofía'
                ],
                lastNames: [
                    'García',
                    'López',
                    'Pérez',
                    'González',
                    'Sánchez',
                    'Martínez',
                    'Rodríguez',
                    'Fernández',
                    'Gómez',
                    'Martín',
                    'Hernández',
                    'Ruiz',
                    'Díaz',
                    'Álvarez',
                    'Moreno',
                    'Muñoz',
                    'Alonso',
                    'Gutiérrez',
                    'Sanz',
                    'Torres',
                    'Suárez',
                    'Ramírez',
                    'Vázquez',
                    'Navarro',
                    'Domínguez',
                    'Ramos',
                    'Castro',
                    'Gil',
                    'Flores',
                    'Morales',
                    'Blanco',
                    'Serrano',
                    'Molina',
                    'Ortiz',
                    'Santos',
                    'Ortega',
                    'Morrell',
                    'Delgado',
                    'Méndez',
                    'Castillo',
                    'Márquez',
                    'Cruz',
                    'Medina',
                    'Herrera',
                    'Marín',
                    'Núñez',
                    'Vega',
                    'Iglesias',
                    'Rojas',
                    'Reyes',
                    'Luna',
                    'Campos',
                    'Rubio',
                    'Peña',
                    'Ferrer',
                    'Lozano',
                    'Garrido',
                    'León',
                    'Aguilar',
                    'Cano',
                    'Arias',
                    'Herrero',
                    'Giménez',
                    'Fuentes',
                    'Díez',

                ]
            },
            methods: {
                dismissRules: function() {
                    this.rulesClosed = true;
                },
                shuffle: function(gender) {
                    app.loading = true;
                    console.log('suffle ' + gender);
                    if(gender) {
                        console.log(Math.round(Math.random() * this.maleNames.length));
                        this.firstName = this.maleNames[Math.round(Math.random() * this.maleNames.length)];
                    } else {
                        this.firstName = this.femaleNames[Math.round(Math.random() * this.femaleNames.length)];
                    }
                    this.lastName = this.lastNames[Math.round(Math.random() * this.lastNames.length)];
                    app.loading = false;
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
                        axios.post('{{ route('compte-namechange-check') }}', {
                            firstName: this.firstName,
                            lastName: this.lastName
                        })
                            .then(function(response) {
                                app.loading = false;
                                if(response.data === "taken") {
                                    app.taken = true;
                                    app.nameChecked = true;
                                } else if(response.data === "OK") {
                                    app.taken = false;
                                    app.nameChecked = true;
                                }
                            }).catch(function(error) {
                            Materialize.toast(error.response.data, 4000);
                            app.loading = false;
                        });
                },
                choose: function() {
                    this.loading = true;
                    axios.post('{{ route('compte-namechange') }}',{
                        firstName: this.firstName,
                        lastName: this.lastName
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
                }
            }
        });
    </script>
@endsection
