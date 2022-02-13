<?php

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('posts')->insert([
            'id'              => 1,
            'slug'            => 'raw',
            'title'           => 'Ceci est un titre',
            'body'            => 'ceci est une nouvelle ou je peut écrire du texte',
            'user_id'         => 1,
            'created_at'      => '2019-05-26 11:39:01',
        ]);
    }
}
