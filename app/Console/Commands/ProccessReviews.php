<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProccessReviews extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'process:name';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    }
}
