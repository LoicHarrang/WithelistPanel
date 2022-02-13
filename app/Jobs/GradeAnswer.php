<?php

namespace App\Jobs;

use App\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GradeAnswer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $answer;

    /**
     * Create a new job instance.
     */
    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $question = $this->answer->question;
        // Si aucune question... fermer.
        if (is_null($question)) {
            Log::debug($this->answer->id.' n\'existe pas, 0');
            $this->answer->score = 0;
            $this->answer->save();

            return;
        }

        if ('single' == $question->type) {
            foreach ($question->options as $option) {
                // Si bonne réponse..
                if ($option['id'] == $this->answer->answer) {
                    if ($option['correct']) {
                        Log::debug($this->answer->id.' correcte, 100');
                        $this->answer->score = 100;
                        $this->answer->save();

                        return;
                    }
                }
            }
            // Réponse incorrecte
            $this->answer->score = 0; // nous soustrayons la moitié
            $this->answer->save();
            Log::debug($this->answer->id.' incorrecte, 0');

            return;
        }
    }
}
