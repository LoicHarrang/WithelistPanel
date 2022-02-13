<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneAudits extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'prune:audits';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Éliminer les anciens audits';

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
        $date = Carbon::now()->subDays(30);
        $audits = \OwenIt\Auditing\Models\Audit::where('created_at', '<', $date)->delete();
    }
}
