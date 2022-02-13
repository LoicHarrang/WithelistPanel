<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeEmails extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'purge:emails';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Purge des email non validés';

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
        $this->info('Purge des emails en cours...');
        // Utilisateurs dont le code de vérification date de plus de 24 heures.
        $users = User::whereNotNull('email_verified_token_at')
            ->where('email_verified_token_at', '<=', Carbon::now()->subDay())
            ->get();
        $count = 0;
        foreach ($users as $user) {
            $user->email = null; // Juste au cas où nous l'effacons
            $user->email_verified = false;
            $user->email_verified_token = null;
            $user->email_verified_token_at = null;
            $user->email_verified_at = null;
            $user->email_enabled = false;
            $user->email_prevent = false;
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            ++$count;
        }
        $this->info('Nous avons purgé '.$count.' emails non validés.');
    }
}
