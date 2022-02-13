<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        /*
         * Permissions des utilisateurs
         */
        DB::table('permissions')->insert([
            'name'         => 'user-abilities-view',
            'display_name' => 'Voir ses permissions',
            'description'  => 'Permet de voir ses permissions et groupes.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        /*
         * Modération
         */
        DB::table('permissions')->insert([
            'name'         => 'mod-search',
            'display_name' => 'Mod: recherche d\'utilisateurs',
            'description'  => 'Permet d\'acceder au panel MOD et de recherche des utilisateurs.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-review-answers',
            'display_name' => 'Vérifications Exams',
            'description'  => 'Permet de vérifier les réponses des examens.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-review-names',
            'display_name' => 'Vérifications Identités',
            'description'  => 'Permet de vérifier les identités.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-interview',
            'display_name' => 'Opérateur',
            'description'  => 'Permet de faire des entretiens aux utilisateurs.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-intervier',
            'display_name' => 'Opérateur - MOD',
            'description'  => 'Marquer l\'utilisateur comme opérateur et modifier son emploi du temps',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-reveal-birthdate',
            'display_name' => 'Date de naissance',
            'description'  => 'Permet de voir la date de naissance d\'un utilisateur',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-reveal-email',
            'display_name' => 'Adresse Email',
            'description'  => 'Permet de voir l\'adresse email d\'un utilisateur.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('permissions')->insert([
            'name'         => 'mod-supervise-answers',
            'display_name' => 'Supervision Exams',
            'description'  => 'Permet d\'acceder au réponses d\'examens signalées.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-supervise-names',
            'display_name' => 'Supervision Identités',
            'description'  => 'Permet d\'acceder aux identités signalées.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-name-reject',
            'display_name' => 'Identité Invalide',
            'description'  => 'Marquer une identitée comme invalide.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-name-accept',
            'display_name' => 'Identité Valide',
            'description'  => 'Marquer une identité comme valide.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-name-reviewers',
            'display_name' => 'Voir l\'opérateur - Identité',
            'description'  => 'Voir en détail les actions d\'un opérateur - Identité.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-answers',
            'display_name' => 'Réponse d\'examen',
            'description'  => 'Voir en détail l\'examen d\'un utilisateur.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-answers-reviews',
            'display_name' => 'Voir l\'opérateur - Examens',
            'description'  => 'Voir en détail les actions d\'un opérateur - Examens.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-suspend',
            'display_name' => 'Suspendre un examen',
            'description'  => 'Suspendre un examen.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-extend',
            'display_name' => 'Etendre la validité d\'un examen',
            'description'  => 'Permet l\'extension de validité d\'un examen.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'mod-exam-interview-cancel',
            'display_name' => 'Annuler un entretien',
            'description'  => 'Permet l\'annulation d\'un entretien.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        /*
         * Système de protection des utilisateurs
         */
        DB::table('permissions')->insert([
            'name'         => 'protection-level-1',
            'display_name' => 'Protection de niveau 1',
            'description'  => 'Protection de niveau 1.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('permissions')->insert([
            'name'         => 'protection-level-1-bypass',
            'display_name' => 'Permissions de niveau 1',
            'description'  => 'Permissions de niveau 1.',
            'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
