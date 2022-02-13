<?php

return [
    /*
     * Permet de générer des examens ou non
     */
    'enabled' => env('EXAMS_ENABLED', true),

    /*
    * Durée de l'examen en minutes
    */
    'duration' => '10',

    /*
     * Lien TeamSpeak
     */
    'ts_link'          => env('EXAM_TS_LINK', 'ts3server://ts.arma3frontiere.fr'),
    'ts_room_name'     => env('EXAM_TS_ROOM_NAME', ''),
    'ts_room_password' => env('EXAM_TS_ROOM_PASSWORD', ''),
    'ts_address' => env('EXAM_TS_ADDRESS', null),

    /*
     * La structure à générer à partir des examens.
     */
    'structure' => [
        [
            'name'        => 'Examen',
            'description' => 'Questions d\'examen pour vérifier si vous avez compris le règlement',
            'questions'   => [
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 1,
                    'value' => 10,
                ],
                [
                    'type'  => 'category',
                    'id'    => 2,
                    'value' => 10,
                ],
            ],
        ],
    ],
];
