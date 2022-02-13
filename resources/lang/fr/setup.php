<?php

return [

    // -- BREADCRUMBS
    // resources/views/setup/breadcrumb.blade.php
    'breadcrumb.steps.data' => 'Nationalité',
    'breadcrumb.steps.email' => 'Email',
    'breadcrumb.steps.name' => 'Identité',
    'breadcrumb.steps.rules' => 'Règlement',
    'breadcrumb.steps.test' => 'Examen',
    'breadcrumb.steps.forum' => 'Forum',
    'breadcrumb.steps.interview' => 'Entretien',

    // setup/welcome.blade.php
    'welcome.title' => 'Bienvenue',
    'welcome.heading' => 'Bonjour !',
    'welcome.regular.heading' => 'Bienvenue sur notre plateforme d\'inscription',
    'welcome.regular.subtitle' => 'Version 1.0',
    'welcome.applications.open' => 'Places disponibles',
    'welcome.applications.open.explanation' => 'Des places sont encore disponibles.',
    'welcome.applications.open.subjecttochange' => 'Cela peut changer pendant la procédure d\'inscription.',
    'welcome.start' => 'Commencer',
    'welcome.ligne1' => 'Vous allez devoir passer un processus appelé <b>"inscription"</b>.',
    'welcome.ligne2' => 'Nous allons commencer par vérifier que vous possédez le jeu Arma 3, ensuite vous aurez accès aux différentes étapes d\'inscription.',
    'welcome.ligne3' => 'Pour continuer, vous serez convoqué pour un entretien oral.',
    'welcome.ligne4' => 'Attention, durant la période de bêta fermé, seul les bêta-testeurs ont accès au dashboard.',
    'welcome.ligne5' => 'Cela peut sembler compliqué et long, mais nous avons essayé de le rendre simple et supportable pour tous.',

    // setup/checkgame.blade.php
    'checkgame.title' => 'Vérification de votre jeu',
    'checkgame.heading' => 'Vérification de votre jeu',
    'checkgame.subtitle' => 'Avant de continuer, nous allons vérifier que vous possedez le jeu Arma 3.',
    'checkgame.error.unknown' => 'Nous avons rencontré une erreur. Merci de réésayer.',
    'checkgame.loading' => 'Vérification...',
    'checkgame.private.heading' => 'Votre profil est insaccessible',
    'checkgame.private.subtitle' => 'Afin que notre système vérifie votre jeu, vous devez rendre la visibilité de celui-ci public, seulement le temps de la vérification.',
    'checkgame.private.reveal' => 'Comment faire ?',
    'checkgame.private.howto.heading' => 'Instructions:',
    'checkgame.private.howto.1.title' => '1. Accedez à vos options',
    'checkgame.private.howto.1.link' => 'Rendez-vous sur vos <a target=\'_blank\' href=\'http://steamcommunity.com/profiles/:steamid/edit/settings\'>paramètres de compte</a>.',
    'checkgame.private.howto.2.title' => '2. Changer votre visibilité de jeu en public',
    'checkgame.private.howto.tip' => 'Une fois effectué, veuillez attendre quelques instant, et ré-ésayer.',
    'checkgame.buy' => '<p>Pour prétendre à nous rejoindre, <b>vous devez posseder le jeu Arma 3</b>.</p>
                        <p>Nous n\'avons pas trouvé votre jeu. Nous vous laissons un widget, afin de vous le procurer.</p>',
    'checkgame.oswarning' => '<b><i class="material-icons tiny">warning</i> :name seulement disponible sur Windows</b>
                            <p>Pour le moment, aucun autre systeme d\'exploitation est pris en charge.</p>
                            <small>Ceci est définitif.</small>',
    'checkgame.recheck' => 'Réesayer',
    'checkgame.success.text' => '<h5><i class="material-icons left">check_circle</i> Vous possédez Arma 3</h5>
                        <p>Nous avons correctement vérifié votre jeu.</p>',
    'checkgame.success.continue' => 'Continuer',

    // -- SETUP INFO

    // setup/info.blade.php
    'info.title' => 'Informations',
    'info.heading' => 'Informations personnelles (HRP)',
    'info.birth.label' => 'Date de naissance',
    'info.birth.placeholder' => 'Cliquez pour choisir votre date',
    'info.birth.help' => 'Format dd/mm/aaa - Ex.: 13/06/2002',
    'info.birth.minage' => 'Les moins de 18 ans ne peuvent pas s\'inscrire. La date de naissance est gardé, afin de vérifier votre âge.',
    'info.country.label' => 'Pays de naissance',
    'info.country.placeholder' => 'Séléctionner une option',
    'info.timezone.label' => 'Zone horaire',
    'info.timezone.help' => 'Elle est utilisé afin de régler le panel dans votre zone horaire.',
    'info.continue' => 'Continuer',

    // SetupController.php
    'info.validation.birth_date.required' => 'La date de naissance est obligatoire.',
    'info.validation.birth_date.date_format' => 'Le format de la date de naissance est invalide. (dd/mm/aaa)',
    'info.validation.birth_date.future' => 'Vous avez indiqué une date de naissance dans le futur.',
    'info.validation.country.required' => 'Le pays est obligatoire.',
    'info.validation.country.cca2' => 'Le pays n\'est pas valide.',
    'info.validation.timezone.required' => 'La zone horaire est obligatoire.',
    'info.validation.timezone.timezone' => 'La zone horaire n\'est pas valide.',

    // -- SETUP E-MAIL
    // setup/email.blade.php
    'email.title' => 'Adresse mail',
    'email.heading' => 'Adresse mail',
    'email.subtitle' => 'Validation de votre moyen de contact',
    'email.enable.label' => 'Adresse Email',
    'email.enable.button' => 'Vérifier mon adresse',
    'email.enable.advantages' => 'Avantages:',
    'email.enable.advantages.forum_access' => 'Être notifié des avancées de votre inscription',
    'email.enable.advantages.certification_progress' => 'Être notifié des annonces importantes du serveur (mises à jour majeures, annonces, ect)',
    'email.enable.advantages.notifications_away' => 'Pouvoir participé aux différents concours potentiels',
    'email.enable.advantages.account_changes' => 'Recevoir des notifications lorsque des changements de compte sont effectué',
    'email.enable.info.privacy' => 'Nous ne partagerons pas le courrier avec des tiers.',
    'email.enable.info.easy_disable' => 'Vous pouvez vous retirez de ce service quand vous le souhaitez.',
    'email.verification.heading' => 'Adresse Email:',
    'email.verification.sent' => 'Nous vous avons envoyé un email de vérification.<br><b>Cliquez sur le lien du message</b> pour continuer.',
    'email.pass.info' => 'Si vous le souhaitez, vous pouvez ne pas activer les emails.',
    'email.pass.tip' => 'Vous pouvez activer ce service depuis vos options, à n\'importe quel moment.',
    'email.pass.button' => 'Ne pas activer pour le moment',
    'email.pass.warning' => 'Ne pas rentrer d\'email limitera les actions de votre compte.',

    'email.notification.mail.subject' => 'Vérification de votre Email',
    'email.notification.mail.heading' => 'Vérification de votre Email',
    'email.notification.mail.paragraph' => 'Bonjour ! Merci de cliquez sur le bouton pour vérifier votre email:',
    'email.notification.mail.button' => 'Vérifier mon Email',
    'email.notification.mail.expiration' => 'Par mesure de sécurité, si vous ne validez pas votre email dans les prochaines 24 heures, le lien expira.',
    'email.notification.mail.footer' => 'Cordialement,<br>L\'équipe de :name',

    // SetupController.php
    'email.verified' => 'Adresse Email vérifié',

    // -- SETUP NAME
    // setup/name.blade.php
    'name.title' => 'Déclaration d\'identité',
    'name.heading' => 'Déclaration d\'identité',
    'name.subtitle' => 'Veuillez déclarer votre identité',
    'name.rules.heading' => 'Règlement d\'identification',
    'name.rules.accept' => 'Accepter',
    'name.form.name.label' => 'Prénom',
    'name.form.name.placeholder' => 'Prénom',
    'name.form.lastname.label' => 'Nom',
    'name.form.lastname.placeholder' => 'Nom',
    'name.form.charlimit' => 'La limite de caractère est de 17. Votre identité en contient:',
    'name.form.check' => 'Vérifier l\'identité',
    'name.form.generate' => 'Générer une identité:',
    'name.form.available' => 'L\'identité est disponible',
    'name.form.available.warning' => 'Une fois validée, <b>votre identité ne pourra pas être changée</b>.',
    'name.form.available.request' => 'Continuer',
    'name.form.available.edit' => 'Si vous avez fait une erreur, veuillez éditer votre déclaration.',
    'name.form.taken' => 'La combinaison Nom/Prénom est inutilisable',
    'name.form.taken.edit' => 'Merci de vérifier votre identité',
    'name.success.heading' => 'Déclaration envoyée',
    'name.success.name' => 'Votre déclaration a été envoyée, nom du dossier:',
    'name.success.approval' => 'L\'identité sera vérifiée par nos recruteurs.<br>Nous vous informerons de leur prise de décision.',
    'name.success.continue' => 'Continuer',

    // SetupController.php
    'name.validation.specialchars' => 'L\'identité doit être écrite sans caractères spéciaux et sans apostrophe.',

    // -- SETUP RULES
    // resources/views/rules.blade.php
    'rules.title' => 'Règlement',
    'rules.heading' => 'Règlement Interne',
    'rules.subtitle' => 'Règlement interne des Sapeurs-Pompiers.',
    'rules.unavailable.title' => 'Le règlement n\'est pas disponible pour le moment',
    'rules.unavailable.check_later' => 'Merci de retenter plus tard.',
    'rules.countdown.before' => 'Merci de lire le règlement et ',
    'rules.countdown.after' => 'vous pouvez continuer.',
    'rules.countdown.tip' => 'Profitez de ce temps pour comprendre le règlement.',
    'rules.continue.title' => 'Quand vous êtes pret, vous pouvez continuer.',
    'rules.continue.tip' => 'Vous aller acceder à l\'examen en ligne',
    'rules.continue.button' => 'Continuer',
    'rules.countdown.over' => 'Dès que vous êtes pret, vous pouvez continuer!',

    // -- SETUP EXAM
    // resources/views/setup/exam/new.blade.php
    'exam.new.title' => 'Confirmation de début d\'examen',
    'exam.new.heading' => 'Examen',
    'exam.new.subtitle' => 'Merci de prendre en compte ces quelques consignes',
    'exam.new.tips.duration' => 'L\'examen a une <b>durée maximale</b> de :duration minutes.',
    'exam.new.tips.countdown_start' => 'Une fois commencé, <b>il n\'y a pas de pause possible</b>.',
    'exam.new.tips.wrong_answers' => 'Les mauvaises réponses <b>retire des points</b>. Si vous ne savez pas répondre à une question, laissez la question sans réponse.',
    'exam.new.tips.one_way' => 'Une fois une question validée, <b>vous ne pouvez plus la modifier</b>.',
    'exam.new.tips.limited_attempts' => 'Vous avez un nombre de tentatives limitée. Si vous ne vous sentez pas pret, <b>ne commencer pas l\'examen</b>',
    'exam.new.tips.wrong_answers_insist' => 'Vous n\'avez que <b>trois tentatives d\'examen d\'entrée</b>, alors choisissez vos réponses avec serieux',
    'exam.new.imported.title' => 'Suite à vos antécédent sur :name, <b>vous devez répéter le processus de certification</b>.',
    'exam.new.imported.report_errors' => 'Si vous pensez qu\'il s\'agit d\'une erreur, contacter un administrateur.',
    'exam.new.unavailable.title' => 'Les examens ne sont pas activé pour le moment.',
    'exam.new.unavailable.subtitle' => 'Merci de retenter plus tard.',
    'exam.new.unavailable.back' => 'Retour au règlement',
    'exam.new.cooldown.title' => 'Vous avez échoué votre précédent examen.',
    'exam.new.cooldown.subtitle' => 'Afin de maximiser vos chances de nous rejoindre, nous vous avons imposé un temps d\'attente avant de repasser un autre examen',
    'exam.new.cooldown.remaining' => '<b>Vous pourrez vous présenter à nouveau le :time.</b> <small>(Heure selon :timezone)</small>',
    'exam.new.cooldown.tip' => 'Nous vous conseillons de revoir le règlement avant de retenter l\'examen.',
    'exam.new.cooldown.tries_remaining' => '{1} Il vous reste 1 tentatives.|[2,*] Il vous reste :value tentatives.',
    'exam.new.back' => 'Retour au règlement',
    'exam.new.start' => 'Commencer l\'examen',
    'exam.new.faq.title' => 'Questions fréquentes',
    'exam.new.faq' => '
            <p>
                <b>Que se passe-t-il si ma connexion se coupe ou si mon navigateur se ferme ?</b>
                <br>
                Rien, le temps continuera à s\'écouler. Ouvrez-le à nouveau ou reconnectez-vous pour continuer.
            </p>
            <p>
                <b>Et si j\'échoue ?</b>
                <br>
                Si vous échouez, vous devrez répéter l\'examen après un certain temps d\'attente.<br>
                Quand vous échouez trois fois à l\'examen, vous ne pouvez plus prétendre a votre inscription.
            </p>
            <p>
                <b>Combien de tentatives me reste-t-il ?</b>
                <br>
                :tries sur 3 tentatives, en comptant l\'examen que vous allez commencer.
            </p>
    ',

];
