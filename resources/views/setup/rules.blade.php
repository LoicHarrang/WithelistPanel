@extends('layouts.dash')

@section('title', __('setup.rules.title'))

@section('content')
    <div id="app">
        <div class="container">
            <br>
            @if(! Auth::user()->hasFinishedSetup())
	            @include('setup.breadcrumb')
	            <br>
            @endif
            <h5>Règlement</h5>
            <p>Règlement interne du serveur</p>
            @if(Auth::user()->hasFinishedSetup())
            	<a href="{{ route('home') }}" class="btn blue waves-effect">RETOUR <i class="material-icons right">navigate_next</i></a>
            @endif
            <div class="card-panel">
                <h5>1. Règles hors roleplay</h5>
                <ol class="browser-default">
                    <li>
                        Nous ne contrôlons pas l'âge de nos joueurs. Si vous outrepassez le PEGI 16 du jeu ArmA 3, l’équipe A3F se décharge de toutes responsabilités en cas de contenu choquants pour vous.
                    </li><br>
                    <li>
                        Le commerce de ressources quelles qu’elles soient disponible en jeu en échange d’argent réel est formellement interdit et est sanctionné d’un          bannissement permanent pour le “vendeur” et temporaire pour “l’acheteur” en plus de la suppression des ressources obtenues.
                    </li><br>
                    <li>
                        L’accès à A3F est totalement gratuit, aucune transaction ne vous sera demandée.
                    </li><br>
                    <li>
                        L’exploitation de bugs, glitchs ou de tout autres moyens de triches est formellement interdite et sanctionné d’un bannissement temporaire ou            permanent suivant la gravité et les circonstances. Les problèmes que vous rencontrez doivent être rapportés à l’équipe de développement.
                    </li><br>
                    <li>
                       Toute forme de triche telles que le streamstalk, méta-gaming et autres joyeusetées sont formellement interdites et sont sanctionnées d’un bannissement temporaire ou permanent suivant les circonstances.
                    </li><br>
                    <li>
                        L’enregistrement de vos parties avec un logiciel est autorisé et encouragé en cas de litige avec un autre joueur.
                        Les streams et l’enregistrement de vidéos sont également autorisés, nous vous encourageons à en faire part cependant à l’équipe communication.
                    </li><br>
                    <li>
                        Le troll, le carkill volontaire, le freekill (= tuer des joueurs sans raisons valables) et toutes autres joyeusetées de la sorte sont sanctionnées d’un bannissement permanent.
                    </li><br>
                    <li>
                        Le take-down (= couper la route brutalement pour stopper un véhicule en train de rouler), est formellement interdit en raison de la physique approximative de ArmA 3. En cas de take-down volontaire, le joueur l’ayant commis pourra être sanctionné d’un bannissement temporaire ou permanent en fonction des circonstances.
                    </li><br>
                    <li>
                        L’arrêt d’une action (scène roleplay inclus) en cours, peu importe la raison ou la manière est strictement interdit et est sanctionné d’un bannissement temporaire ou permanent.
                    </li><br>
                    <li>
                        Bien que la Belgique ait 3 langues officielles, nous visons une communauté francophone, la langue parlée sur le serveur doit donc être uniquement le Français
                    </li><br>
                    <li>
                        Notre carte n’est pas une île, en conséquence, il est interdit de se déplacer en dehors des bordures.
                    </li>
                </ol>
                <h5>2. Règles roleplay</h5>
                <ol class="browser-default">
                    <li>
                        Qu’est-ce que le roleplay sur le serveur ?<br>
                        Le roleplay (= jeu de rôle) est le fait de se mettre dans la peau d’un personnage fictif en incarnant ses choix, ses réactions face aux différentes situations, son caractère et ses objectifs.<br>
                        Lorsque vous êtes connecté au serveur de jeu, vous ne vous incarnez pas vous même, mais votre personnage, et lorsque vous n’êtes plus connecté au serveur vous n'incarnez plus votre personnage, mais bien vous même.
                    </li><br>
                    <li>
                        Dans le cadre où vous respectez le contexte de roleplay (cf.2.1)<br>
                        les insultes, menaces, etc... sont autorisées.<br>
                        Si vous estimez que celles-ci sont sorties de contextes et orientées à votre encontre, nous vous invitons à en discuter avec le joueur concerné            et en dernier recours d’en faire part à l’équipe de modération.
                    </li><br>
                    <li>
                        Le non-roleplay est le fait de ne pas respecter le rôle de quelqu’un, son propre rôle, utiliser un langage sortant du roleplay (ex: j’ai des lags), ou d’agir de façon irréaliste (ex: ne pas ressentir de peur face à la mort).<br>
                        Le non-roleplay est sanctionné d’un bannissement temporaire ou permanent suivant les circonstances.
                    </li><br>
                    <li>
                        Votre personnage doit correspondre au contexte en place sur le serveur, par exemple, un grand mafieux sicilien n’a pas vraiment sa place en             plein milieu de la campagne ardennaise.
                    </li><br>
                    <li>
                        Etant donné que le non-roleplay est strictement interdit (cf.2.3), vous devez utiliser des phrases discrètes permettant de faire comprendre que vous avez un problème :<br>
                        J’ai mal au crâne ! = Je lag !<br>
                        Il y a bientôt une tempête ! = Il y a bientôt un reboot !<br>
                        Je suis fatigué ! = Je dois bientôt me déconnecter !
                    </li><br>
                    <li>
                        Le braquage de personnes est autorisé uniquement si une action roleplay le précède ou en découle.
                    </li><br>
                    <li>
                        La mort RP est d’application, vous pouvez envoyer votre dossier à un Manager ( cf. “La Mort RP” ).<br>
                        La personne visée par la mort RP peut l’éviter ( de manière roleplay ) en trouvant une porte de sortie à la scène RP, suite à ça, le dossier de mort RP est annulé et doit être reformulé.<br>
                        Pour certaines raisons elle s’applique d’office ( suicide, etc… ).<br>
        
                        *Les staff concernés par une mort RP ne sont jamais membre de la commission venant à valider le dossier les concernant.<br>
                        *Le staff se réserve le droit de refuser un dossier sans donner de raisons.<br>
                        *La mort RP n’est pas active durant la période Beta
                    </li><br>
                    <li>
                        Les sommations n’ont pas leur place sur notre serveur, nous comptons sur le bon sens de chacun pour faire usage de la force dans les cas approprié.<br>Il n’y a pas que les armes qui sont des moyens de force, les menaces, “tatanes”, etc… le sont également à leur échelle.

                    </li><br>
                    <li>
                        L’offroad est à proscrire, en Europe de manière générale vous ne verrez jamais une voiture circuler d’un point A à un point B en coupant à travers champ.
                        <br>
                        L’interdiction d’offroad est à nuancer si vous utilisez un véhicule tout terrain, mais ne reste tout de même pas une raison pour vous déplacer en coupant à travers champ sans cesse.
                    </li><br>
                </ol>
            </div>
            @if(! Auth::user()->hasFinishedSetup())
                <div class="card-panel">
                    <p v-if="! allowExam">
                        <b>@lang('setup.rules.countdown.before') @{{ diff }} @lang('setup.rules.countdown.after')</b>
                        <br>
                        <small>@lang('setup.rules.countdown.tip')</small>
                    </p>
                    <p v-if="allowExam">
                        <b>@lang('setup.rules.continue.title')</b>
                    </p>
                    <a :disabled="!allowExam" href="{{ route('setup-exam') }}" class="btn blue waves-effect">@lang('setup.rules.continue.button') <i class="material-icons right">navigate_next</i></a>
                </div>
            @else
            	<a href="{{ route('home') }}" class="btn blue waves-effect">RETOUR <i class="material-icons right">navigate_next</i></a>
            @endif


        </div>
    </div>
@endsection
@section('js')
    {{--<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.0/vue.min.js"></script>--}}
    {{--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}
    {{--<script src="/js/moment-countdown.min.js"></script>--}}
    <script type="text/x-template" id="item-template">
        <div>
            <b><i class="material-icons" style="vertical-align: middle; padding-right: 8px">@{{ icon }}</i> @{{ title }}</b>
            <p>
                <slot></slot>
            </p>
        </div>
    </script>
    <script>
        Vue.component('rule', {
            template: '#item-template',
            props: {title: {required: true}, icon: {required: true}}
        });
        var app = new Vue({
            el: '#app',
            components: ['rule-item'],
            data: {
                passed: false,
                date: moment.tz('{{ Auth::user()->rules_seen_at }}', '{{ config('app.timezone') }}'),
                dateAllow: moment.tz('{{ Auth::user()->rules_seen_at->addMinutes(5) }}', '{{ config('app.timezone') }}'),
                load: new Date(),
                diff: "",
                now: moment(new Date()),
                notified: false,
            },
            methods: {
                update: function() {
                    var self = this;
                    moment.locale('{{ config('app.locale') }}');
                    this.diff = moment(new Date()).to(this.dateAllow).toString();
                    this.now = moment(new Date());
                    setTimeout(function(){ self.update() }, 1000)
                },
                check: function (first) {
                    var self = this;
                    if(!first) {
                        axios.get('{{ route('setup-rules-check') }}')
                            .then(function (response) {/**/})
                            .catch(function () {
                                location.reload();
                            });
                    }
                    setTimeout(function(){ self.check() }, 60000)
                }
            },
            computed: {
                allowExam: function() {
                    return this.now > this.dateAllow;
                }
            },
            watch: {
              allowExam: function(newValue) {
                  if(newValue && !this.notified) {
                      this.notified = true;
                      Materialize.toast('@lang('setup.rules.countdown.over')', 5000);
                  }
              }
            },
            created: function() {
                this.update();
                this.check(true);
            }
        });
    </script>

@endsection