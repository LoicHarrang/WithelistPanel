<?php
/**
 * Copyright (c) 2020. Arma 3 Frontière
 * Tout droit réservés
 * Par Loic Shmit et Sharywan
 */

namespace App\Http\Controllers;

use App\Answer;
use App\Exam;
use App\Jobs\GradeExam;
use App\Mail\VerifyEmail;
use App\Name;
use App\Page;
use App\Question;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Syntax\SteamApi\Facades\SteamApi;
use RestCord\DiscordClient;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required']); // Tous ces itinéraires nécessitent le setup
    }

    public function welcome()
    {
        $user = Auth::user();

        $user_discord = NULL;
        $error_interne= NULL;

        if (!is_null($user->discord_id)) {
            $discord = new DiscordClient(['token' => config('dash.token_discord')]); // Token is required
   	 		try {
			    $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($user->discord_id)]);
			    foreach ($user_discord->roles as $key => $value) {
	                if (($value == 654412768148979732) || ($value == 642731221817884683) || ($value == 607940362094247946) || ($value == 607941367406002196) && is_null($user->is_beta)) {
	                    $user->is_beta = 1;
	                    $user->save();
	                }
            	}
			} catch (\GuzzleHttp\Command\Exception\CommandClientException $e) {
			    if (strpos($e->getMessage(), "resulted in a `404 Not Found`")) {
			    	$error_interne = "compte introuvable sur notre discord";
			    }
			}
        }

        if (isset($_GET["error"])) {
            return view('setup.welcome')->with('user', Auth::user())->with('error', $_GET["error"]);
        } elseif (!is_null($error_interne)) {
        	return view('setup.welcome')->with('user', Auth::user())->with('error', $error_interne);
    	} else {
            return view('setup.welcome')->with('user', $user)->with('user_discord', $user_discord);
        }
    }

    public function discord_login() {
        session_start();
        //Déclaration du provider (le passe droit quoi)
        $provider = new \Wohali\OAuth2\Client\Provider\Discord([
            'clientId' => '482244731796062218',
            'clientSecret' => 'iezlrLqnPdDP_mjrTU-lfXT9i60nO4XF',
            'redirectUri' => 'https://dash.arma3frontiere.fr/setup/discord_receive'
        ]);

        //Si ont reçoit une reponse ont passe a la reception, sinon ont continu
        if (!isset($_GET['code'])) {
            //Si ont a une valeur dans error, ont switch sur les pages d'erreurs, sinon ont passe a la connexion
            if (isset($_GET["error"])) {
                     return redirect()->action('SetupController@welcome', ['error' => $_GET["error"]]);
            } else {
                $authUrl = $provider->getAuthorizationUrl();
                $_SESSION['oauth2state'] = $provider->getState();
                return redirect($authUrl);
            }
        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            // Step 2. Get an access token using the provided authorization code
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
            // Step 3. (Optional) Look up the user's profile with the provided token
            try {
                $user = Auth::user();

                $responseU = $provider->getResourceOwner($token);
                $responseU = $responseU->toArray();

                if (User::where('discord_id', 'LIKE', $responseU['id'])->count() > 0) {
                    // On retourne qu'elle est déjà prise afin que la page informe l'utilisateur
                    return redirect()->action('SetupController@welcome', ['error' => "Ce compte est déjà associé"]);
                } else {
                    $user->discord_id = $responseU['id'];
                    $user->save();

                    $discord = new DiscordClient(['token' => config('dash.token_discord')]); // Token is required
                    try {
                        $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($user->discord_id)]);
                        foreach ($user_discord->roles as $key => $value) {
                            if (($value == 654412768148979732) || ($value == 642731221817884683) || ($value == 607940362094247946) || ($value == 607941367406002196) && is_null($user->is_beta)) {
                                $user->is_beta = 1;
                                $user->save();
                            }
                        }
                    } catch (\GuzzleHttp\Command\Exception\CommandClientException $e) {}

                    return redirect()->action('SetupController@welcome');
                }
                
            } catch (Exception $e) {
                // Failed to get user details
                exit('Oh dear...');
            }
        }
    }

    public function checkGamePage()
    {
        return view('setup.checkgame');
    }

    public function checkGame()
    {
        $user = Auth::user();
        // Si nous savons déjà que l'utilisateur possède le jeu acheté, pourquoi devons-nous le vérifier ?
        if ($user->has_game) {
            return 'true';
        }

        $game = Cache::remember('setup.'.$user->id.'checkGame.game', 1, function () use ($user) {
            return sizeof(SteamApi::player($user->steamid)->getOwnedGames(false, false, [107410]));
        });
        // Vérification si il possede le jeu
        if (0 == $game) {
            return 'false';
        }

        $sharing = Cache::remember('setup.'.$user->id.'checkGame.sharing', 1, function () use ($user) {
            return SteamApi::player($user->steamid)->IsPlayingSharedGame(107410);
        });
        // Vérification si il possede le partage famille
        if (1 == $sharing) {
            return 'false';
        }

        // S'il passe les filtres, il l'a. Nous l'avons mis à jour sur la db.
        $user->has_game = true;
        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
        Cache::forget('user.'.$user->id.'.getSetupStep');

        return 'true';
    }

    public function infoPage()
    {
        return view('setup.info');
    }

    public function info(Request $request)
    {
        $this->validate($request, [
            'country'    => 'nullable|cca2'
        ], [
            'country.required'       => __('setup.info.validation.country.required'),
            'country.cca2'           => __('setup.info.validation.country.cca2'),
        ]);

        $user = Auth::user();
        if (is_null($user->country)) {
            if (!$request->has('country')) {
                return redirect(route('setup-info'))->withErrors(['country' => __('setup.info.validation.country.required')]);
            }
            $user->country = $request->input('country');
        }

        // Sil il possède moins de 16 ans... (ou si vous êtes un gars intelligent et que vous avez fixé une date dans le futur)
       /* if ($birthDate->age < 16 || $birthDate->isFuture()) { // TODO if future fail validation
            // Nous bloquons instantanément votre compte grâce à un allias @pegi
            $user->disabled = true;
            $user->disabled_reason = '@pegi'; // Motif qui affiche la page pegi
            $user->disabled_at = Carbon::now();
            Auth::logout(); // Déconnection de l'utilisateur
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            // Et nous l'avons redirigé, bien sûr, sinon il ne connaîtrait pas son âge
            return redirect(route('pegi'));
        }*/

        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
        Cache::forget('user.'.$user->id.'.getSetupStep');
        Cache::forget('user.'.$user->id.'.attributes.timezone');

        // Vérification de la date de naissance

        return back();
    }

    public function emailPage()
    {
        return view('setup.email');
    }

    public function emailReset()
    {   
        $user = Auth::user();
        $user->email = NULL;
        $user->save();
        return view('setup.email');
    }

    public function email(Request $request)
    {
        $this->validate($request, [
            'email'  => 'required|email|unique:users,email',
            'enable' => 'boolean',
        ],[
            'email.unique' => 'L\'adresse email est déja utilisé.',
        ]);

        if ($request->input('enable')) {
            // L'utilisateur a activé son email
            $user = Auth::user();
            $user->email_enabled = true;
            $user->email = $request->input('email');
            $user->email_verified_token_at = Carbon::now();
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'.$user->id.'.getSetupStep');
            Mail::to($user)->send(new VerifyEmail($user));

            return 'next'; // Nous indiquons à la page que nous devons encore vérifier...
        } else {
            // L'utilisateur n'a pas activé son email
//            $user = Auth::user();
//            $user->email = null; // Por si acaso se lo borramos
//            $user->email_verified = false;
//            $user->email_verified_token = null;
//            $user->email_verified_at = null;
//            $user->email_enabled = false;
//            $user->timestamps = false;
//            $user->save();
//            $user->timestamps = true;
//            Cache::forget('user.'.$user->id.'.getSetupStep');
//
//            return 'next';
        }
    }

//    public function emailCode() {
//        $user = Auth::user();
//        if(is_null($user->email) || is_null($user->email_enabled) || !$user->email_enabled)
//    }

    public function introPage()
    {
        return view('setup.intro');
    }

    public function namePage()
    {
        $user = Auth::user();

        return view('setup.name')
            ->with('user', $user);
    }

    public function nameCheck(Request $request)
    {
        $this->validate($request, [
            'firstName' => 'required|min:3|max:14',
            'lastName'  => 'required|min:3|max:14',
            'sexe'  => 'required|min:1|max:1',
            'date'  => 'required|min:8|max:10',
            'taille' => 'required|min:3|max:3',
            'lieuNaiss' => 'required|min:3|max:14',
        ]);
        $name = trim($request->input('firstName')).' '.trim($request->input('lastName'));
        $sexe = trim($request->input('sexe'));
        $date = trim($request->input('date'));
        $taille = trim($request->input('taille'));
        $lieuNaiss = trim($request->input('lieuNaiss'));

        if (!is_numeric($taille)) {
            abort(412, 'Votre taille ne doit contenir que des chiffres');
        }

        if (! preg_match('/^[ a-záéíóúñ]+$/iu', $name)) {
            abort(412, __('setup.name.validation.specialchars'));
        }

        if (User::where('name', 'LIKE', $name)->count() > 0 || Name::where('name', 'LIKE', $name)->where('invalid', false)->count() > 0) {
            return 'taken';
        }

        $query_string = "";
        if (Auth::user()->country == 'FR') {
            $query_string = 'n=1&nom='.urlencode(trim($request->input('lastName'))).'&prenom='.urlencode(trim($request->input('firstName'))).'&numero='.urlencode(Auth::user()->steamid).'&sexe='.$sexe.'&date='.urlencode($date).'&lieu='.urlencode($lieuNaiss);
        } else {
            $query_string = 'n=2&nom='.urlencode(trim($request->input('lastName'))).'&prenom='.urlencode(trim($request->input('firstName'))).'&numero='.urlencode(Auth::user()->steamid).'&sexe='.$sexe.'&date='.urlencode($date).'&lieu='.urlencode($lieuNaiss);
        }

        return 'http://dev.arma3frontiere.fr/generate_identities_cards.php?'.$query_string;
    }

    /**
     * POST changement d'identité.
     *
     * @param Request $request
     *
     * @return string
     */
    public function name(Request $request)
    {

        // Vérifier que le nom et le prénom ont été envoyés
        $this->validate($request, [
            'firstName' => 'required|min:3|max:14',
            'lastName'  => 'required|min:3|max:14',
            'sexe'  => 'required|min:1|max:1',
            'date'  => 'required|min:8|max:10',
            'taille' => 'required|min:3|max:3',
            'lieuNaiss' => 'required|min:3|max:14',
        ]);


        // Efface les espaces inutiles dans le nom complet et ajoute un espace entre le prénom et le nom de famille
        $fullName = trim($request->input('firstName')).' '.trim($request->input('lastName'));
        $sexe = trim($request->input('sexe'));
        $taille = trim($request->input('taille'));
        $lieuNaiss = trim($request->input('lieuNaiss'));

        $date = trim($request->input('date'));
        $date_Formatted = explode("-", $date);
        $date = implode(",",$date_Formatted);

        // Vérifiez avec la correspondance "loose" LIKE pour voir s'il y a des noms similaires dans la bdd ou
        // en entretien
        if (User::where('name', 'LIKE', $fullName)->count() > 0 || Name::where('name', 'LIKE', $fullName)->where('invalid', false)->count() > 0) {
            // On retourne qu'elle est déjà prise afin que la page informe l'utilisateur
            return 'taken';
        }

        $user = Auth::user();

        $name = new Name();

        $correctedName = rtrim($this->titleCase(str_replace('´', '', $fullName)));
        if ($correctedName != $fullName) {
            $name->original_name = $fullName;
        }

        $name->name = $correctedName;
        $name->type = 'setup';
        $name->needs_review = true;
        $name->sexe = $sexe;
        $name->birthday = $date;
        $name->lieu = $lieuNaiss;
        $name->taille = $taille;
        $user->names()->save($name);
        Cache::forget('user.'.$user->id.'.getSetupStep');
        return 'OK';
    }

    /**
     * Page des règles.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rulesPage()
    {
        $user = Auth::user();

        // Si l'utilisateur n'a pas lu les règles jusqu'à présent, nous mettons dans la base de données la date
        // principalement pour savoir quand le laisser passer le test
        if (is_null($user->rules_seen_at)) {
            $user->rules_seen_at = Carbon::now();
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'.$user->id.'.getSetupStep');
        }

        $rules = Page::where('slug', 'normas')->first();

        return view('setup.rules')->with('rules', $rules);
    }

    public function rulesCheck(Request $request)
    {
        var_dump($request->user()->canSeeRules());
        return;
        if (! $request->user()->canSeeRules()) {
            abort(403, 'Le règlement n\'est pas disponible pour le moment');
        }
    }

    /**
     * Générer un test et rediriger.
     *
     * @param Request $request
     */
    /*public function generateExam(Request $request)
    {
        if (! config('exam.enabled') || $request->user()->hasExamCooldown()) {
            abort(403, 'Vous ne pouvez pas généré d\'autre examen');
        }
        $user = $request->user();
        if (is_null($user->getOngoingExam())) {
            $config = config('exam.structure');
            $structure = $config;
            $groupCount = 0;
            $questionIds = [];
            foreach ($config as $group) {
                $questionCount = 0;
                foreach ($group['questions'] as $question) {
                    if ('question' == $question['type']) {
                        $structure[$groupCount]['questions'][$questionCount]['answer_id'] = null;
                    } elseif ('category' == $question['type']) {
                        $questionModel = Question::where('enabled', true)
                            ->where('category_id', $question['id'])
                            ->orderByRaw('RAND()')
                            ->get()->reject(function ($question) use ($questionIds) {
                                return in_array($question->id, $questionIds); // S'il ne fait pas partie du réseau, nous le laisserons
                            })->first();
                        $questionIds[] = $questionModel->id;
                        $structure[$groupCount]['questions'][$questionCount]['type'] = 'question';
                        $structure[$groupCount]['questions'][$questionCount]['answer_id'] = null;
                        $structure[$groupCount]['questions'][$questionCount]['id'] = $questionModel->id;
                    }
                    ++$questionCount;
                }
                ++$groupCount;
            }
            $exam = new Exam();
            $exam->user_id = $user->id;
            $exam->start_at = Carbon::now();
            $exam->end_at = Carbon::now()->addMinutes(config('exam.duration'));
            $exam->expires_at = Carbon::now()->addDays(7);
            $exam->finished = false;
            $exam->structure = $structure;
            $exam->save();
            Cache::forget('user.'.$user->id.'.getSetupStep');

            return redirect(route('setup-exam'));
        }
        // S'il ne fait pas partie du réseau, nous le laisserons
        abort(403, 'Examen en cours');
    }*/

    /**
    * Passage du test en auto
    *
    * @param Request $request
    */
    public function examPage(Request $request)
    {
        if (! config('exam.enabled') || $request->user()->hasExamCooldown()) {
            abort(403, 'Vous ne pouvez pas généré d\'autre examen');
        }
        $user = $request->user();
        if (is_null($user->getOngoingExam())) {
            $config = config('exam.structure');
            $structure = $config;
            $groupCount = 0;
            $questionIds = [];
            foreach ($config as $group) {
                $questionCount = 0;
                foreach ($group['questions'] as $question) {
                    if ('question' == $question['type']) {
                        $structure[$groupCount]['questions'][$questionCount]['answer_id'] = null;
                    } elseif ('category' == $question['type']) {
                        $questionModel = Question::where('enabled', true)
                            ->where('category_id', $question['id'])
                            ->orderByRaw('RAND()')
                            ->get()->reject(function ($question) use ($questionIds) {
                                return in_array($question->id, $questionIds); // S'il ne fait pas partie du réseau, nous le laisserons
                            })->first();
                        $questionIds[] = 2;
                        $structure[$groupCount]['questions'][$questionCount]['type'] = 'question';
                        $structure[$groupCount]['questions'][$questionCount]['answer_id'] = null;
                        $structure[$groupCount]['questions'][$questionCount]['id'] = 2;
                    }
                    ++$questionCount;
                }
                ++$groupCount;
            }
            $exam = new Exam();
            $exam->user_id = $user->id;
            $exam->start_at = Carbon::now();
            $exam->end_at = Carbon::now()->addMinutes(config('exam.duration'));
            $exam->expires_at = Carbon::now()->addDays(7);
            $exam->finished = 1;
            $exam->structure = $structure;
            $exam->passed_at = Carbon::now()->subMinutes(1);
            $exam->passed = 1;
            $exam->score = 14;
            $exam->save();
            Cache::forget('user.'.$user->id.'.getSetupStep');

            return view('setup.interview');
        }
        // S'il ne fait pas partie du réseau, nous le laisserons
        abort(403, 'Examen en cours');
    }

    /*{
        $user = Auth::user();
        // Si l'utilisateur a un examen en cours
        if (! is_null($user->getOngoingExam())) {
            // Avez-vous commandé une page spécifique ?
            if (! isset($page)) {
                $exam = $user->getOngoingExam();

                // Si vous essayez d'accéder sans page et que vous devriez être sur une page > 1, redirigez vers la bonne
                if ($exam->getCurrentQuestionNumber() > 1) {
                    return redirect(route('setup-exam', ['page' => $exam->getCurrentQuestionNumber()]));
                }

                // Sinon, nous vous montrons la page d'accueil de l'examen
                return view('setup.exam')
                    ->with('exam', $exam)
                    ->with('group', null)
                    ->with('question', null)
                    ->with('type', 'first')
                    ->with('pageNumber', 0);
            } else {
                // L'utilisateur nous a passé une page spécifique

                $exam = $user->getOngoingExam();

                // Si la page que vous avez passée ne correspond pas à celle que vous devriez voir, redirigez-la. (pour les malins)
                if ($exam->getCurrentQuestionNumber() != $page) {
                    return redirect(route('setup-exam', ['id' => $exam->getCurrentQuestionNumber()]));
                }

                // Nous obtenons la structure et la question que vous jouez pour le numéro de la question
                $structure = $exam->structure;
                $pageNumber = $page;
                $count = 1;
                foreach ($structure as $group) {
                    foreach ($group['questions'] as $question) {
                        if ($pageNumber == $count) {
                            // Si la question n'existe pas, nous ferions mieux d'échouer ici
                            // Et nous avertissons l'utilisateur en conséquence, que le fait de montrer
                            // une erreur aléatoire
                            if (is_null(Question::find($question['id']))) {
                                abort(500, 'Erreur dans la question');
                            }

                            return view('setup.exam')
                                ->with('exam', $exam)
                                ->with('group', $group)
                                ->with('question', $question)
                                ->with('type', 'question')
                                ->with('pageNumber', $pageNumber);
                        }
                        ++$count;
                    }
                }

                // Si nous ne trouvons pas de question... TODO change cela
                return view('setup.exam')
                    ->with('exam', $exam)
                    ->with('group', null)
                    ->with('question', null)
                    ->with('type', 'first')
                    ->with('pageNumber', $pageNumber);
            }
        } else {
            // L'utilisateur n'a pas de test en cours.

            // L'utilisateur a des examens non corrigés
            if ($user->exams()->whereNull('passed')->count() > 0) {
                // Nous vous demandons d'être patient et d'attendre aussi longtemps qu'il le faut.
                $exam = $user->exams()->whereNull('passed')->orderByDesc('created_at')->first();

                if (!$exam->finished) {
                    $reussite = true;
                    foreach($exam->structure as $key => $group) {
                        foreach($group['questions'] as $key => $question) {
                            $questionModel = \App\Question::find($question['id']);
                            $answer = \App\Answer::find($question['answer_id']);

                            if (is_null($answer)) {
                                $reussite = false;
                            }
                        }
                    }

                    if (!$reussite) {
                        if (!is_null($exam->passed)) {
                            $exam->passed_at = Carbon::now()->subMinutes(1);
                            $exam->passed = 0;
                            $exam->save();
                        }
                    }
                }

                return view('setup.exam.wait');
            } else {
                // L'utilisateur ne dispose encore d'aucun test.
                // Montrez-lui la page pour en générer un.
                return view('setup.exam.new');
            }
        }
    }*/

    public function exam(Request $request, $page)
    {
        $pageNumber = $page;
        if (0 == $page) {
            abort(405);
        } else {
            $user = Auth::user();
            $exam = $user->exams()->whereNull('passed')->orderByDesc('created_at')->first();
            // Si le test est terminé depuis plus d'une minute ou s'il est déjà terminé
            if ($exam->end_at->addMinutes(1) < Carbon::now() || $exam->finished) {
                return 'next';
            }
            $structure = $exam->structure;
            if ($page != $exam->getCurrentQuestionNumber()) {
                return 'next';
            }
            $count = 1;
            $groupCount = 0;
            foreach ($structure as $group) {
                $questionCount = 0;
                foreach ($group['questions'] as $question) {
                    if ($pageNumber == $count) {
                        if (is_null($question['answer_id'])) {
                            // Ici, si je n'avais pas répondu
                            // Nous créons la réponse
                            // et, grâce au PHP, nous faisons tout cela
                            // Simplement pour remplacer une partie d'un tableau
                            // ...
                            $answer = new Answer();
                            $answer->exam_id = $exam->id;
                            $answer->question_id = $question['id'];
                            $answer->question_text = Question::find($question['id'])->question;
                            $answer->user_problem_message = $request->input('message');
                            // Si elle ne répond pas, nous mettons un zéro comme norme (ou si elle va trop loin)
                            if (is_null($request->input('answer')) || strlen($request->input('answer')) > 500) {
                                $answer->score = 0;
                            } else {
                                $answer->answer = $request->input('answer');
                            }
                            $answer->save();
                            $question['answer_id'] = $answer->id;
                            $structure[$groupCount]['questions'][$questionCount] = $question;
                            $exam->structure = $structure;

                            // Nous avons vérifié si c'était la dernière question
                            if ($exam->getQuestionCount() == $count) {
                                $exam->finished = true;
                                $exam->finished_at = Carbon::now();
                                $exam->save();
                                dispatch(new GradeExam($exam));
                                Cache::forget('user.'.$exam->user->id.'.getSetupStep');

                                return 'next';
                            }

                            $exam->save();
                            Cache::forget('user.'.$exam->user->id.'.getSetupStep');

                            return 'next';
                        } else {
                            // S'il avait déjà répondu, nous lui avons envoyé le suivant aussi.
                            // Au cas où vous seriez un petit malin.
                            return 'next';
                        }
                    }
                    $questionCount++;
                    $count++;
                }
                $groupCount++;
            }
        }
    }

    public function forumPage()
    {
        return view('setup.forum');
    }

    public function forumRedirect()
    {
        return Socialite::with('ipb')->scopes(['user.profile', 'user.email', 'user.groups'])->redirect();
    }

    public function forumCallback(Request $request)
    {
        $user = Auth::user();
        try {
            $oauth = Socialite::driver('ipb')->scopes(['user.profile', 'user.email', 'user.groups'])->user();
        } catch (InvalidStateException $exception) {
            return view('setup.forumerror');
        } catch (ServerException $exception) {
            return view('setup.forumerror');
        }
        if (! is_null($user->ipb_token)) {
            return redirect(route('setup-interview'));
        }
        $user->ipb_token = $oauth->token;
        $user->ipb_refresh = $oauth->refreshToken;
        $user->ipb_id = $oauth->getID();
        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
        Cache::forget('user.'.$user->id.'.getSetupStep');

        return redirect(route('setup-interview'));
    }

    public function interviewPage(Request $request)
    {
        if ($request->user()->hasInterviewOngoing()) {
            $exam = $request->user()->getInterviewExam();

            return view('setup.interview_on')->with('exam', $exam);
        }

        return view('setup.interview');
    }

    public function correctSpelling($name)
    {
        return strtr(strtr($name, config('dash.nombres')), config('dash.except'));
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
