<?php

namespace App\Console\Commands;

use App\Name;
use App\Notifications\AccountImported;
use App\Notifications\NameApproved;
use App\Notifications\NameChangeAvailable;
use App\Notifications\NameRejected;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPlayers extends Command
{
    private $correct = [
        // Prénom
        'Raul'      => 'Raúl',
        'Oscar'     => 'Óscar',
        'Alvaro'    => 'Álvaro',
        'Andres'    => 'Andrés',
        'Angel'     => 'Ángel',
        'Jesus'     => 'Jesús',
        'Adrian'    => 'Adrián',
        'Guzman'    => 'Guzmán',
        'Ivan'      => 'Iván',
        'Sebastian' => 'Sebastián',
        'Ruben'     => 'Rubén',
        'Julian'    => 'Julián',
        'Fermin'    => 'Fermín',
        'Cesar'     => 'César',
        'Matias'    => 'Matías',
        'Agustin'   => 'Agustín',
        'Joaquin'   => 'Joaquín',
        'Martin'    => 'Martín',
        'Tobias'    => 'Tobías',
        // Nom
        'Rodriguez' => 'Rodríguez',
        'Hernandez' => 'Hernández',
        'Fernandez' => 'Fernández',
        'Martinez'  => 'Martínez',
        'Gonzalez'  => 'González',
        'Gonzales'  => 'González',
        'Garcia'    => 'García',
        'Casarin'   => 'Casarín',
        'Benitez'   => 'Benítez',
        'Gomez'     => 'Gómez',
        'Sanchez'   => 'Sánchez',
        'Lopez'     => 'López',
        'Perez'     => 'Pérez',
        'Marquez'   => 'Márquez',
        'Gutierrez' => 'Gutiérrez',
        'Diaz'      => 'Díaz',
        'Avila'     => 'Ávila',
        'Suarez'    => 'Suárez',
        'Ramirez'   => 'Ramírez',
        'Beltran'   => 'Beltrán',
        'Ibañez'    => 'Ibáñez',
        'Vazquez'   => 'Vázquez',
        'Millan'    => 'Millán',
        'Lazaro'    => 'Lázaro',
        'Cardenas'  => 'Cárdenas',
        // Diminutifs

        // Troll
        'Yesus'   => 'Jesús',
        'Yisus'   => 'Jesús',
        'Jesulín' => 'Jesús',
    ];

    /**
     * Les joueurs qui doivent répéter l'examen'.
     *
     * @var array
     */
    private $repeat = [
        // steamid => motif (enlevé pour cause de confidentialité)
    ];

    private $except = [
        'Ivánov'  => 'Ivanov',
        'Ivánero' => 'Ivanero',
    ];

    /**
     * Nom et signature de la commande
     *
     * @var string
     */
    protected $signature = 'import:players';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Importer les joueurs d\'une version précédente';

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
        $players = DB::table('players')->get();
        $this->info('Nous avons trouvé '.$players->count().' joueurs à importer');
        $bar = $this->output->createProgressBar($players->count());
        $count = 0;
        foreach ($players as $player) {
            $user = new User();
            $user->steamid = $player->playerid;
            $user->has_game = true; // Possède le jeu ArmA 3
            $user->imported = Carbon::now(); // Marquer comme importé
            if (key_exists($user->steamid, $this->repeat)) {
                $user->imported_exam_exempt = false; // doit refaire l'examen
                $user->imported_exam_message = $this->repeat[$user->steamid];
            } else {
                $user->imported_exam_exempt = true; // ne doit pas refaire l'examen
            }
            $user->save();
            // Nous générons un nom et le marquons comme importé
            $name = new Name();
            $correctedName = rtrim($this->correctSpelling($this->titleCase(str_replace('´', '', $player->name))));
            if ($correctedName != $player->name) {
                $name->original_name = $player->name;
            }
            $name->name = $correctedName; // Correcton des fautes d'orthographe
            $name->type = 'imported';
            if (0 == $player->adminlevel) {
                // Si pas d'espace OU si caractère spéciaux = mauvais
                if (0 == substr_count($correctedName, ' ') || substr_count($correctedName, '.') > 0) {
                    $name->needs_review = false;
                    $name->invalid = true;
                    $user->names()->save($name); // la sauvegarder et la rejeter(l'identité), en avertissant l'utilisateur
                    $name->user->notify(new NameRejected($name));
                } else {
                    // Identité correcte. Nous ne faisons rien de spécial. Il sera vérifié par opérateur.
                    $name->needs_review = true;
                    $name->invalid = false;
                }
            } else {
                // S'il s'agit d'un admin, acceptez le nom immédiatement et informer l'utilisateur.
                $name->needs_review = false;
                $name->invalid = false;
                $name->active_at = Carbon::now();
                $user->names()->save($name); // guardarlo
                $name->user->notify(new NameApproved($name));
                $user = $name->user;
                $user->name_changes_remaining = 1;
                $user->name_changes_reason = '@pop4';
                $user->save();
                $user->notify(new NameChangeAvailable());
            }
            // $name->active_at = Carbon::now(); En fin de compte, nous voulons que les noms soient revus.
            $user->names()->save($name); // Sauvegarde

            // Notifier l'utilisateur qu'il a un compte importé
            $user->notify(new AccountImported());

            // GUID
            $guid = $user->guid; // Nous avons généré un GUID pour pouvoir faire des recherches par GUID
            ++$count;
            $bar->advance();
        }
        $bar->finish();
    }

    public function correctSpelling($name)
    {
        return strtr(strtr($name, $this->correct), $this->except);
    }

    /**
     * http://php.net/manual/es/function.ucwords.php#112795.
     *
     * @param $string
     * @param array $delimiters
     * @param array $exceptions
     *
     * @return mixed|string
     */
    public function titleCase($string, $delimiters = [' ', '-', '.', "'", "O'", 'Mc'], $exceptions = ['de', 'da', 'dos', 'das', 'do', 'del', 'I', 'II', 'III', 'IV', 'V', 'VI'])
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = [];
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, 'UTF-8'), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, 'UTF-8');
                } elseif (in_array(mb_strtolower($word, 'UTF-8'), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, 'UTF-8');
                } elseif (! in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        }//foreach
        return $string;
    }
}
