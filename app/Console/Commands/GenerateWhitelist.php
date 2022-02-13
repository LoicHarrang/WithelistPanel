<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GenerateWhitelist extends Command
{
    /**
     * Nom et signature de la commande.
     *
     * @var string
     */
    protected $signature = 'whitelist:generate';

    /**
     *  Description de la commande.
     *
     * @var string
     */
    protected $description = 'Génération du fichier de withelist';

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
        $whitelist = null;

        $users = User::where('disabled', false)
            ->where('birth_checked', 1)
            ->whereNotNull('country')
            ->whereNotNull('timezone')
            //->whereNotNull('ipb_id')
            ->orWhereNotNull('name')->get();
        $count = 0;
        $bar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            if (! $user->hasFinishedSetup() && is_null($user->name)) {
                continue;
            }
            if (is_null($whitelist)) {
                $whitelist = $user->guid.' '.$user->id;
            } else {
                $whitelist = $whitelist."\n".$user->guid.' '.$user->id;
            }
            ++$count;
            if (! request()->has('noupdate') && is_null($user->whitelist)) {
                $user->whitelist_at = Carbon::now();
                $user->save();
            }
            $bar->advance();
        }
        $bar->finish();

        Cache::forget('whitelist');
        Cache::forever('whitelist', $whitelist);

        $this->info("\nWhitelist effectués: ".$count);
    }
}
