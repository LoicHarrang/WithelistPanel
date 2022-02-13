<?php

namespace App\Console\Commands;

use App\Answer;
use App\Name;
use App\Notifications\NameApproved;
use App\Notifications\NameChangeAvailable;
use App\Notifications\NameRejected;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ProcessReviews extends Command
{
    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'reviews:process';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Vérifie les modèles avec les examens terminés';

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
        // Identité
        $this->info('Vérification des identités...');
        $names = Name::where('needs_review', true)->has('reviews', '>=', 3)->get();
        $names->each(function ($name) {
            $this->info('#'.$name->id.' '.$name->name);
            $reviews = $name->reviews()->get();
            $total = 0;
            foreach ($reviews as $review) {
                $total = $total + $review->score;
                $this->info('Vérification #'.$review->id.': '.$review->score);
            }
            $score = round($total / $reviews->count());
            $this->info('Score: '.$total.'/'.$reviews->count().' = '.$score);
            if ($score >= 51) { // Majorité absolue : la moitié + 1 pour approuver
                $name->needs_review = false;
                $name->invalid = false;
                $name->active_at = Carbon::now();

                // Avant de sauvegarder, nous terminons les autres noms
                foreach ($name->user->names()->whereNotNull('active_at')->whereNull('end_at')->where('invalid', false)->get() as $item) {
                    $item->end_at = Carbon::now();
                    $item->save();
                }

                $name->save();
                $this->info('Identité #'.$name->id.' APPROUVE');
                $name->user->notify(new NameApproved($name));
                if ('imported' == $name->type && config('dash.imported_name_changes_allow')) {
                    $user = $name->user;
                    $user->name_changes_remaining = 1;
                    $user->name_changes_reason = '@pop4';
                    $user->save();
                    $user->notify(new NameChangeAvailable());
                    Cache::forget('user.'.$user->id.'.getSetupStep');
                } else {
                    // S'il ne s'agit pas d'un nom importé, nous retirons à l'utilisateur l'autorisation de modifier le nom.
                    $user = $name->user;
                    $user->name_changes_remaining = 0;
                    $user->save();
                    Cache::forget('user.'.$user->id.'.getSetupStep');
                }
            } else {
                $name->needs_review = false;
                $name->invalid = true;
                $name->save();
                $this->info('Identité #'.$name->id.' SUSPENDU');
                $name->user->notify(new NameRejected($name));
                Cache::forget('user.'.$name->user->id.'.getSetupStep');
            }
        });
        // Réponses aux questions textuelles
        $this->info('Vérification questions à texte...');
        $answers = Answer::whereNull('score') // Pas de score
            ->where('needs_supervisor', false) // Pas de vérification necessaire
            ->whereHas('question', function ($query) {
                // Une question existe
                $query->where('type', 'text');
            })
            ->has('reviews', '>=', 3) // Qui possède 3 vérification ou plus
            ->get();
        $answers->each(function ($answer) {
            $this->info('#'.$answer->id);
            $reviews = $answer->reviews()->get();
            $total = 0;
            $abuse = false;
            foreach ($reviews as $review) {
                // Si elle est marquée comme un abus, nous répondons qu'elle nécessite une vérification plus en détail
                if ($review->abuse) {
                    $abuse = true;
                }
                $total = $total + $review->score;
                $this->info('Vérification #'.$review->id.': '.$review->score);
            }
            if ($abuse) {
                $this->info('#'.$answer->id.' VERIFICATION NECESSAIRE');
                $answer->needs_supervisor = true;
                $answer->needs_supervisor_reason = 'abuse';
            } else {
                $score = round($total / $reviews->count());
                $this->info('Score: '.$total.'/'.$reviews->count().' = '.$score);
                if ($score >= 50) { // La moitié suffit pour qu'on lui dise.
                    $answer->score = $score;
                    $this->info('#'.$answer->id.' APPROUVE');
                } else {
                    $answer->score = 0;
                    $this->info('#'.$answer->id.' SUSPENDU <50');
                }
            }
            $answer->save();
        });
    }
}
