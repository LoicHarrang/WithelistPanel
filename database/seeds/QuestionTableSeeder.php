<?php

use Illuminate\Database\Seeder;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1
        DB::table('questions')->insert([
            'type'       => 'text',
            'question'   => 'Inventer une histoire pour votre personnage.',
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'enabled'    => true,
        ]);

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Question';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Option A',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Option B',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Option C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Vrai ou faux ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Vrai',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Faux',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Quelle option est correcte ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Toutes',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Aucune',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Option C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Question sur le règlement.';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Vrai',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Faux',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Autre question.';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Toutes',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Aucune',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Option C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Peut on voler un policier ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Oui',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Non',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Le métagaming est-il autorisé ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Oui',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Non',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Comment s\'appelle mon chien ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Chien',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Gérard',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Minou',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Quelle option est correcte ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Toutes',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Aucune',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Option A',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Oui ou non ?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Oui',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Non',
                'correct' => false,
            ],
        ];
        $question->save();
    }
}
