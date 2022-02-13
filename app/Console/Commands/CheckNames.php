<?php

namespace App\Console\Commands;

use App\Arma\Player;
use App\User;
use Illuminate\Console\Command;

class CheckNames extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'names:check';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Vérification des identités';

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
        $players = Player::with('user')->get();

        $bar = $this->output->createProgressBar(count($players));

        foreach ($players as $player) {
            if (is_null($player->user)) {
                continue;
            }
            if (! is_null($player->user->name)) {
                continue;
            }
            $name = $player->user->getActiveName();
            if (! is_null($name) && $name != $player->name) {
                $this->info($player->name.' > > '.$name);
                $player->name = $name;
                $player->save();
            }
            $bar->advance();
        }

        $bar->finish();
    }
}
