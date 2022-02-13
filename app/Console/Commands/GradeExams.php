<?php

namespace App\Console\Commands;

use App\Exam;
use App\Jobs\GradeExam;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GradeExams extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'exams:grade';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Noter les examens disponibles';

    /**
     * Creation de la nouvelle instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execution de la commande
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Correction des examens...');
        $exams = Exam::whereNull('passed')
            ->where(function ($query) {
                $query->where('end_at', '<', Carbon::now())
                    ->orWhere('finished', true);
            })
            ->get();
        foreach ($exams as $exam) {
            $this->info('Correction examen #'.$exam->id);
            dispatch(new GradeExam($exam));
        }
        if (0 == $exams->count()) {
            $this->info('Aucun examen à corriger.');
        }
    }
}
