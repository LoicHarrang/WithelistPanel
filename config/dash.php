<?php

return [
    'version' => 'v1.5.7',

    /*
     * Execute scheduled tasks
     */
    'enable_schedule' => env('DASH_ENABLE_SCHEDULE', false),

    /*
     * Whether or not register new users on login.
     */
    'registrations_enabled' => env('DASH_REGISTRATIONS_ENABLED', true),

    'exam.enabled' => env('EXAM_ENABLED', false),

    /*
     * S'il est activé, il ne vous demande pas de lier votre compte de forum.
     * Conçu pour les cas où le forum est temporairement hors service, pour pouvoir continuer à fonctionner.
     * Ensuite, lorsqu'il sera activé, ceux qui n'ont pas le compte lié devront le lier.
     */
    'forum_skip' => env('DASH_FORUM_SKIP', true),

    'disabled_reasons' => [
        '@pegui'     => 'Vous n\'avez pas l\'age pour nous rejoindre.',
        '@tries'     => 'Vous avez utilisé toutes vos chances pour passer l\'entretient.',
        '@nametries' => 'Vous avez utilisé toutes vos chances pour choisir une identité.',
        '@disabled' => 'Vous avez été banni.',
    ],

    'name_reasons' => [
        '@pop4' => 'Changement de version.',
    ],

    'pop_opening' => env('DASH_OPENING', null),
    'pop_opened'  => env('DASH_OPENED', false),

    'imported_name_changes_allow' => env('DASH_IMPORTED_NAME_CHANGES_ALLOW', false),

    'token_discord' => env('TOKEN_DISCORD_BOT', 'NDgyMjQ0NzMxNzk2MDYyMjE4.XlP9vA._QlVMwX4wA8kHTg7nuakySTDvwg'),

    'ts3_link' => env('TS3_LINK', ''),

    'altis_enabled' => env('ALTIS_ENABLED', false),
    'altis_ip'      => env('ALTIS_IP', null),
    'altis_forum'   => env('ALTIS_FORUM', null),
    'altis_rules'   => env('ALTIS_RULES', null),

    'analytics' => env('ANALYTICS_ID', null),

    /*
     * IDENTITE
     */

    'nombres' => [
        // Prénom
        'Raul'      => 'Raúl',
        'Oscar'     => 'Óscar',
        'Alvaro'    => 'Álvaro',
        'Andres'    => 'Andrés',
        'Angel'     => 'Ángel',
        'Jesus'     => 'Jesús',
        'Adrian'    => 'Adrián',
        'Guzman'    => 'Guzmán',
        'Ivan'      => 'Iván',
        'Sebastian' => 'Sebastián',
        'Ruben'     => 'Rubén',
        'Julian'    => 'Julián',
        'Fermin'    => 'Fermín',
        'Cesar'     => 'César',
        'Matias'    => 'Matías',
        'Agustin'   => 'Agustín',
        'Joaquin'   => 'Joaquín',
        'Martin'    => 'Martín',
        'Tobias'    => 'Tobías',
        // Nom
        'Rodriguez' => 'Rodríguez',
        'Hernandez' => 'Hernández',
        'Fernandez' => 'Fernández',
        'Martinez'  => 'Martínez',
        'Gonzalez'  => 'González',
        'Gonzales'  => 'González',
        'Garcia'    => 'García',
        'Casarin'   => 'Casarín',
        'Benitez'   => 'Benítez',
        'Gomez'     => 'Gómez',
        'Sanchez'   => 'Sánchez',
        'Lopez'     => 'López',
        'Perez'     => 'Pérez',
        'Marquez'   => 'Márquez',
        'Gutierrez' => 'Gutiérrez',
        'Diaz'      => 'Díaz',
        'Avila'     => 'Ávila',
        'Suarez'    => 'Suárez',
        'Ramirez'   => 'Ramírez',
        'Beltran'   => 'Beltrán',
        'Ibañez'    => 'Ibáñez',
        'Vazquez'   => 'Vázquez',
        'Millan'    => 'Millán',
        'Lazaro'    => 'Lázaro',
        'Cardenas'  => 'Cárdenas',

        // Troll
        'Yesus'   => 'Jesús',
        'Yisus'   => 'Jesús',
        'Jesulín' => 'Jesús',
    ],

    'except' => [
        'Ivánov'  => 'Ivanov',
        'Ivánero' => 'Ivanero',
    ],

    'whitelist_key' => env('WHITELIST_KEY'),

    'enable_integration' => env('DASH_ENABLE_INTEGRATION', false),

    'discourse_secret' => env('DISCOURSE_SECRET', null),
    'discourse_sso_url' => env('DISCOURSE_SSO_URL', null),

    'start_year' => env('DASH_START_YEAR', '2020'),

    'landing_text' => env('DASH_LANDING_TEXT', 'Ligne1.</br>Ligne2...'),

    'url_forum' => env('DASH_URL_FORUM', null),
    'url_tos' => env('DASH_URL_TOS', '/tos'),
    'url_privacy' => env('DASH_URL_PRIVACY', '/privacy'),

];
