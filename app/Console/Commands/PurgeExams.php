<?php

namespace App\Console\Commands;

use App\Exam;
use App\Notifications\ExamExpired;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeExams extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'purge:exams';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Purge des examens';

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
        $this->info('Purge des examens en attente et dépassés...');
        $exams = Exam::whereNull('interview_passed')
            ->whereNull('interview_user_id')
            ->where('passed', true)
            ->where('expires_at', '<', Carbon::now())
            ->get();
        $count = 0;
        foreach ($exams as $exam) {
            $exam->user->notify(new ExamExpired($exam));
            $exam->delete();
            ++$count;
        }
        $this->info('Nous avons purgé '.$count.' examens expirés.');
    }
}
