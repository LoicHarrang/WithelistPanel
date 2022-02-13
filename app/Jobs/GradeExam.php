<?php

namespace App\Jobs;

use App\Answer;
use App\Exam;
use App\Notifications\ExamFailed;
use App\Notifications\ExamPassed;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GradeExam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $exam;

    /**
     * Create a new job instance.
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::debug('Correction examen #'.$this->exam->id);
        $structure = $this->exam->structure;
        $total = 0;
        $graded = 0;
        $if_examinate = false;
        // Passez en revue l'ensemble du test, question par question, en comptant le score
        foreach ($structure as $group) {
            foreach ($group['questions'] as $question) {
                if (! is_null($question['answer_id'])) {
                    // Si la question vaut plus que zéro point, nous examinons la réponse
                    if ($question['value'] > 0) {
                        // Nous vérifions que la réponse existe. Sinon, zéro point.
                        $answer = Answer::find($question['answer_id']);
                        if (! is_null($answer)) {
                            // Si elle a déjà été corrigée, nous l'examinerons
                            if (! is_null($answer->score)) {
                                $computedScore = round($answer->score / 100);
                                $total = $total + ($computedScore * $question['value']);
                                Log::debug('#'.$answer->id.' '.$computedScore.' * '.$question['value'].' = '.$computedScore * $question['value']);
                                $graded = $graded + 1;
                            } else {
                                Log::debug('#'.$answer->id.' corrigé '.$answer->question->type);
                                if ('text' != $answer->question->type) {
                                    dispatch(new GradeAnswer($answer));
                                    $if_examinate = true;
                                }
                            }
                        } else {
                            Log::debug('Question à valeur 0');
                        }
                    }
                } else {
                    Log::debug('Pas de réponse, donc zéro.');
                    $graded = $graded + 1;
                }
            }
        }

        if ($if_examinate) {
            return dispatch(new GradeExam($this->exam));
        }

        //Si toutes les réponses ont été corrigé
        if ($graded >= $this->exam->getQuestionCount()) {
            // Nous vérifions s'il est même possible d'approuver par l'utilisateur avec la note dont il dispose
            // et le nombre de réponses corrigées.
            // (total des points - points corrigés) + note
            if ($total * 20 / $this->exam->getTotalQuestionValue() >= 10) {
                // L'utilisateur a la possibilité d'approuver.
                // Toutes les questions sont corrigées et la moitié ou plus.
                // Approuvé
                Log::debug('++ APPROUVE '.$total.'/'.$this->exam->getTotalQuestionValue());
                $this->exam->passed = true;
                $this->exam->score = $total * 20 / $this->exam->getTotalQuestionValue();
                $this->exam->passed_at = Carbon::now();
                $this->exam->expires_at = $this->exam->expires_at->addDays(3); // nous lui donnons trois jours de plus que ce qu'il avait
                $this->exam->save();
                $this->exam->user->notify(new ExamPassed($this->exam));
                Cache::forget('user.'.$this->exam->user->id.'.getSetupStep');
            } else {
                // Il n'y a rien à faire.
                // L'utilisateur n'obtiendrait plus les points ou toutes les réponses à partir de maintenant...
                // Suspendu
                // Nous attendons que toutes les réponses soient corrigées.
                Log::debug('-- Suspendu p.pos:'.(($this->exam->getTotalQuestionValue() - $graded) + $total).' '.$total.'/'.$this->exam->getTotalQuestionValue());
                $this->exam->passed = false;
                $this->exam->score = $total * 20 / $this->exam->getTotalQuestionValue();
                $this->exam->passed_at = Carbon::now();
                $this->exam->save();
                $this->exam->user->notify(new ExamFailed($this->exam));
                if (0 == $this->exam->user->getExamTriesRemaining()) {
                    $user = $this->exam->user;
                    $user->disabled = 1;
                    $user->disabled_reason = '@tries';
                    $user->disabled_at = Carbon::now();
                    $user->save();
                    Cache::forget('user.'.$user->id.'.getSetupStep');
                }
            }
        }
    }
}
