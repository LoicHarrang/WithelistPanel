<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Exam;
use App\Name;
use App\Sanctions;
use App\SaveBeta;
use App\Arma\Identite;
use App\Jobs\GradeReview;
use App\Notifications\AbuseSuspension;
use App\Notifications\InterviewFailed;
use App\Notifications\InterviewPassed;
use App\Notifications\NameApproved;
use App\Notifications\NameRejected;
use App\Question;
use App\Review;
use App\User;
use App\DB;
use App\Charts\UserChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter;
use RestCord\DiscordClient;

class ModController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required']);
    }

    public function home()
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-review*', 'mod-interview', 'mod-supervise*'])) {
            abort(403);
        }

        $opening = Carbon::parse(config('dash.pop_opening'));

        return view('mod.home')->with('user', Auth::user())->with('opening', $opening); //A
    }

    public function operateur_dashboard()
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-review*', 'mod-interview', 'mod-supervise*'])) {
            abort(403);
        }
        Cache::forget('mod.dashboard.'.\auth()->user()->id.'count');
        $count = Cache::remember('mod.dashboard.'.\auth()->user()->id.'count', 10, function () {
            $count = 0;
            if (Auth::user()->hasPermission('mod-review-names')) {
                $count = $count + Name::reviewable()->count();
            }
            return $count;
        });

        return view('mod.operateur.dashboard')
            ->with('count', $count);
    }

    public function reviewPage()
    {
        if (! Auth::user()->hasPermission(['mod-review-answers', 'mod-review-names'])) {
            abort(403);
        }

        return view('mod.operateur.review');
    }

    public function reviewGet()
    {
        if (! Auth::user()->hasPermission(['mod-review-answers', 'mod-review-names'])) {
            abort(403);
        }

        $options = [];
        if (Auth::user()->hasPermission('mod-review-answers')) {
            $exists = Answer::reviewable()->count() > 0;
            if ($exists) {
                $options[] = \App\Answer::class;
            }
        }
        if (Auth::user()->hasPermission('mod-review-names')) {
            $exists = Name::reviewable()->count() > 0;
            if ($exists) {
                $options[] = \App\Name::class;
            }
        }

        // S'il y a quelque chose à vérifier
        if (sizeof($options) > 0) {
            // Nous en choisissons un au hasard
            $chosen = $options[rand(0, sizeof($options) - 1)];

            // App\Answer
            if (\App\Answer::class == $chosen) {
                $answer = Answer::reviewable()
//                    ->random()
//                    ->orderByRaw("RAND()")
                    ->with('question')
                    ->take(10)
                    ->first();
                if (is_null($answer)) {
                    return [];
                }

                return ['answer' => $answer->makeHidden([
                    'question_id',
                    'exam_id',
                    'user_problem_message',
                    'needs_supervisor',
                    'needs_supervisor_reason',
                    'supervisor_at',
                    'supervisor_action',
                    'supervisor_id',
                    'created_at',
                    'updated_at',
                    'answer_id',
                    'score',
                ])->toArray()];
            }
            if (\App\Name::class == $chosen) {
                $name = Name::reviewable()
//                    ->random()
//                    ->orderByRaw("RAND()")
                    ->take(100)
                    ->first();
                if (is_null($name)) {
                    return [];
                }

                return ['name' => $name->makeHidden(['user_id', 'created_at', 'updated_at', 'deleted_at', 'active_at', 'end_at', 'needs_review', 'invalid', 'type'])->toArray()];
            }
        }

        // Si rien a vérifier
        return [];
    }

    /**
     * Ajouter une révision à une réponse.
     *
     * @param Request $request
     *
     * @return string
     */
    public function review(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-review-answers', 'mod-review-names'])) {
            abort(403);
        }
        // Valider qu'aucun petit malin ne se trompera jamais pour prouver
        $this->validate($request, [
            'type'         => 'required',
            'id'           => 'required|integer|min:1',
            'score'        => 'required|integer|min:0|max:100',
            'abuse'        => 'required|boolean',
            'abuseMessage' => 'nullable|max:200',
            'abuseId'      => 'nullable|max:200',
        ]);

        $user = Auth::user();

        $type = null;
        $id = 0;
        // App\Answer
        if ('answer' == $request->input('type')) {
            if (! Auth::user()->hasPermission(['mod-review-answers'])) {
                abort(403);
            }
            $type = \App\Answer::class;
            // Nous avons trouvé la réponse avec l'identifiant
            $answer = Answer::findOrFail($request->input('id'));
            $id = $answer->id;
        }

        // App\Name
        if ('name' == $request->input('type')) {
            if (! Auth::user()->hasPermission(['mod-review-names'])) {
                abort(403);
            }
            $type = \App\Name::class;
            $name = Name::findOrFail($request->input('id'));
            $id = $name->id;

            // Si aucune vérification necessaire
            if (!$name->needs_review) {
                abort(403);
            }

            if ($name->reviews->count() >= 1) {
                $scores = $request->input('score');
                $count = 1;
                foreach($name->reviews as $review) {
                    $scores += $review->score;
                    $count++;
                }

                $result = intval($scores / $count);

                if ($result > 50) {
                    foreach ($name->user->names()->whereNotNull('active_at')->whereNull('end_at')->where('invalid', false)->get() as $item) {
                        $item->end_at = Carbon::now();
                        $item->save();
                    }
                    $name->invalid = false;
                    $name->active_at = Carbon::now();
                    $name->end_at = null;
                    $name->needs_review = false;
                    $name->save();

                    $otherUser = Auth::user()->getOtherUserInfos($name->user_id);

                    $natio = null;
                    if ($otherUser->country == "FR") {
                        $natio = 0;
                    } else {
                        $natio = 1;
                    };
                    
                    if (is_null($natio)) {
                        abort(403);
                    }

                    $date = str_replace (",","-", $name->birthday);
                    $birth = Carbon::parse($date);

                    $nameF = preg_split("/[\s,]+/", $name->name);

                    if (!is_null(Auth::user()->identite)) {
                        Auth::user()->identite->where('pid', '=', $otherUser->steamid)->delete();
                    }

                    
                	$identity = new Identite();
                    $identity->pid = $otherUser->steamid;
                    $identity->prenom = $nameF[0];
                    $identity->nom = $nameF[1];
                    $identity->nationalite = $natio;
                    $identity->sexe = ($name->sexe)-1;
                    $identity->lieu_naissance = $name->lieu;
                    $identity->date_naissance = $birth->format('d/m/Y');
                    $identity->taille = intval($name->taille);
                    $identity->save();
                   

                    $discord = new DiscordClient(['token' => config('dash.token_discord')]);

                    $roleID = "";
                    if ($natio == 0) {
                        $roleID = "607953366487662592"; //fr
                    } else {
                        $roleID = "607953344756973588"; //belge
                    }

                    //delete older roles
                    $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id)]);
                    foreach ($user_discord->roles as $key => $value) {
                        if ($value == 607953344756973588) {
                            $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953344756973588']);
                        } elseif ($value == 607953366487662592) {
                            $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953366487662592']);
                        }
                    }

                    //adding roles
                    $discord->guild->addGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => $roleID]);

                    $otherUser->notify(new NameApproved($name));

                    Cache::forget('user.'.$name->user->id.'.getSetupStep');
                } elseif ($name->reviews->count() >= 2) {
                    $name->invalid = true;
                    $name->active_at = null;
                    $name->needs_review = false;
                    $name->end_at = Carbon::now();
                    $name->save();

                    $otherUser = Auth::user()->getOtherUserInfos($name->user_id);
                    $otherUser->notify(new NameRejected($name));
                    $discord = new DiscordClient(['token' => config('dash.token_discord')]);
                    //delete older roles
                    $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id)]);
                    foreach ($user_discord->roles as $key => $value) {
                        if ($value == 607953344756973588) {
                            $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953344756973588']);
                        } elseif ($value == 607953366487662592) {
                            $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953366487662592']);
                        }
                    }

                    if (!is_null(Auth::user()->identite)) {
                        Auth::user()->identite->where('pid', '=', $otherUser->steamid)->delete();
                    }

                    Cache::forget('user.'.$name->user->id.'.getSetupStep');
                }
            }
        }

        if (Review::where('user_id', $user->id)->where('reviewable_type', $type)->where('reviewable_id', $id)->count() > 0
            || Review::where('reviewable_type', $type)->where('reviewable_id', $id)->count() >= 3) {
            abort(403);
        }
        if (is_null($type)) {
            abort(422);
        }

        // Création et assignation de la vérification
        $review = new Review();
        $review->user_id = $user->id;
        $review->reviewable_type = $type;
        $review->reviewable_id = $id;
        if ($request->input('abuse')) { // S'il y a des abus, on met un 0 dessus et on regarde le message
            $review->score = 0;
            if (100 == $request->input('abuseId')) {
                $review->abuse_message = 'Otro: "'.$request->input('abuseMessage').'"';
            } else {
                $review->abuse_message = $request->input('abuseId');
            }
        } else {
            $review->score = $request->input('score');
        }
        $review->abuse = $request->input('abuse');
        $review->save();
        dispatch(new GradeReview($review));

        // Quoi que nous retournions, la page se comportera de la même manière
        return 'ok';
    }

    public function searchPage(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-interview'])) {
            abort(403);
        }
        $results = User::query();
        if ($request->has('q')) {
            $results->whereHas('names', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->input('q').'%');
            });
            $results->orWhere('steamid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('guid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('name', 'LIKE', '%'.$request->input('q').'%');
        }

        $results = $results->orderBy('updated_at', 'desc');
        $results = $results->paginate(15);

        return view('mod.operateur.search')->with('results', $results);
    }

    public function userPage($id)
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-interview'])) {
            abort(403);
        }
        $user = User::findOrFail($id);

        foreach($user->exams()->latest()->get() as $exam) {
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
        }

        return view('mod.operateur.user')->with('user', $user);
    }

    public function revealBirthDate($id)
    {
        if (! Auth::user()->hasPermission('mod-reveal-birthdate')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        if ($user->hasPermission('protection-level-1') && ! Auth::user()->hasPermission('protection-level-1-bypass')) {
            abort(403);
        }

        return $user->birth_date->format('d/m/Y').' ('.$user->birth_date->age.' años)';
    }

    public function revealEmail($id)
    {
        if (! Auth::user()->hasPermission('mod-reveal-email')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        if ($user->hasPermission('protection-level-1') && ! Auth::user()->hasPermission('protection-level-1-bypass')) {
            abort(403);
        }

        return $user->email;
    }

    public function interviewPage(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        if (is_null($exam->interview_at) || is_null($exam->interview_user_id)) {
            abort(403, 'L\'entretien n\'a pas encore commencé');
        }
        if (! $exam->interviewer->is($request->user())) {
            abort(403, 'Un autre opérateur est en train de réaliser cet entretien');
        }
        if (! is_null($exam->interview_passed)) {
            abort(403, 'Entretien terminé');
        }

        return view('mod.operateur.interview')->with('exam', $exam);
    }

    public function interview(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        if (! is_null($exam->interview_at) || ! is_null($exam->interview_user_id)) {
            if (! $exam->interviewer->is($request->user())) {
                abort(403, 'Cette entretien est déja assigné');
            }

            return redirect(route('mod-interview', $exam));
        }
        $exam->interview_at = Carbon::now();
        $exam->interview_user_id = Auth::user()->id;
        $exam->interview_code = Str::random(32);
        $exam->save();

        return redirect(route('mod-interview', $exam))->with('status', 'Entretien commencé');
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function interviewCode(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        // Vérifiez si c'est lui qui fait l'entretien
        if (! $request->user()->is($exam->interviewer)) {
            abort(403, 'Vous n\'êtes pas le responsable de l\'entretien');
        }
        // Vérification si l'entretien a déja commencé
        if (! is_null($exam->interview_code_at) && ! is_null($exam->interview_passed)) {
            return redirect(route('mod-interview', $exam))->with('status', 'Vous avez déjà saisi le code....');
        }
        $this->validate($request, [
           'code' => 'required|min:32|max:32',
        ]);

        if ($request->input('code') != $exam->interview_code) {
            return redirect(route('mod-interview', $exam))->withErrors(['code' => 'Le code fourni est incorrect.']);
        }

        // Correct, nous la sauvons et la déplaçons.
        $exam->interview_code_at = Carbon::now();
        $exam->save();

        return redirect(route('mod-interview', $exam))->with('status', 'Code correct');
    }

    public function interviewCancel(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        // Vérifiez si c'est lui qui fait l'entretien
        if (! $request->user()->is($exam->interviewer)) {
            abort(403, 'Vous n\'êtes pas le responsable de l\'entretien');
        }
        if (! is_null($exam->interview_passed)) {
            return redirect(route('mod-user', $exam->user))->with('status', 'Entretien terminé');
        }
        $exam->interview_at = null;
        $exam->interview_code = null;
        $exam->interview_code_at = null;
        $exam->interview_user_id = null;
        $exam->save();

        return redirect(route('mod-user', $exam->user))->with('status', 'Entretien annulé');
    }

    public function interviewGrade(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        // Vérifiez si c'est lui qui fait l'entretien
        if (! $request->user()->is($exam->interviewer)) {
            abort(403, 'Vous n\'êtes pas le responsable de l\'entretien');
        }
        if (! is_null($exam->interview_passed)) {
            return 'OK...';
        }
        $this->validate($request, [
            'pass' => 'required|boolean',
            'pegi' => 'required|boolean',
        ]);
        if ($request->input('pegi')) {
            $exam->interview_passed = false;
            $exam->interview_end_at = Carbon::now();
            $exam->save();
            // Désactivation de l'utilisateur
            $user = $exam->user;
            // Bloquage instantané du compte, avec l'allias @pegi
            $user->disabled = true;
            $user->disabled_reason = '@pegi'; // Motif qui indique au systeme de charger la page Pegi
            $user->disabled_at = Carbon::now();
            $user->save();
            Cache::forget('user.'.$user->id.'.getSetupStep');

            return 'OK';
        }

        if ($request->input('pass')) { //si accepter
            $exam->interview_passed = true;
            $exam->withelist = true;


            	/*DB::on('a3f')->table('identite')->insertOrIgnore([
            		['pid' => $exam->user->steamid],
            	]);*/
            
            	//on add a la whitelist
	            $params=[
	                'key' => '6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714',
	                'id' => 10820, 
	                'guid' => $exam->user->steamid,
	                'mode' => 'change',
	            ];

	            $defaults = array(
	                CURLOPT_URL => 'https://armaremoteadmin.com/api/extern/v1/IWhitelist/ChangeWhitelist.ashx',
	                CURLOPT_POST => true,
	                CURLOPT_POSTFIELDS => http_build_query($params)."&settings=[true,false,false,'".$exam->user->username($exam->user)."']",
	            );

	            $ch = curl_init();
	            curl_setopt_array($ch, $defaults);
	            $whitelist = curl_exec($ch);

	            curl_close($ch);
            

            $exam->user->notify(new InterviewPassed($exam));
        } else {
            $exam->interview_passed = false;
            //Si plus de chances, on disable
            if ($exam->user->getExamTriesRemaining() == 0) {
                $user = $exam->user;
                $user->disabled = 1;
                $user->disabled_reason = '@tries';
                $user->disabled_at = Carbon::now();
                $user->save();
                Cache::forget('user.'.$user->id.'.getSetupStep');
            }

            //on remove WL au cas ou
            $params=[
                'key' => '6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714',
                'id' => 10820, 
                'guid' => $exam->user->steamid,
                'mode' => 'remove'
            ];

            $defaults = array(
                CURLOPT_URL => 'https://armaremoteadmin.com/api/extern/v1/IWhitelist/ChangeWhitelist.ashx',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $params,
            );

            $ch = curl_init();
            curl_setopt_array($ch, $defaults);
            curl_exec($ch);

            curl_close($ch);

            $exam->user->notify(new InterviewFailed($exam));
        }


        $exam->interview_end_at = Carbon::now();
        $exam->save();
        Cache::forget('user.'.$exam->user->id.'.getSetupStep');

        return 'OK';
    }

    public function disableName(Request $request, $id)
    {
        if (! Auth::user()->hasPermission('mod-name-reject')) {
            abort(403);
        }
        $this->validate($request, [
            'nameid' => 'required|integer',
        ]);
        $name = Name::findOrFail($request->input('nameid'));
        if (is_null($name->active_at)) {
            abort(403);
        }
        if ($name->user->hasPermission('protection-level-1') && ! Auth::user()->hasPermission('protection-level-1-bypass')) {
            abort(403);
        }

        if ($name->user == Auth::user()) {
            abort(403,'Vous ne pouvez pas vous désactiver vous même !');
        }

        $name->invalid = true;
        $name->active_at = null;
        $name->needs_review = false;
        $name->end_at = Carbon::now();
        $name->save();

        $otherUser = Auth::user()->getOtherUserInfos($name->user_id);
        $discord = new DiscordClient(['token' => config('dash.token_discord')]);
        //delete older roles
        $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id)]);
        foreach ($user_discord->roles as $key => $value) {
            if ($value == 607953344756973588) {
                $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953344756973588']);
            } elseif ($value == 607953366487662592) {
                $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953366487662592']);
            }
        }

        if (!is_null(Auth::user()->identite)) {
            Auth::user()->identite->where('pid', '=', $otherUser->steamid)->delete();
        }

        Cache::forget('user.'.$name->user->id.'.getSetupStep');

        return redirect('mod/names/'.$name->id);
    }

    public function enableName(Request $request, $id)
    {
        if (! Auth::user()->hasPermission('mod-name-accept')) {
            abort(403);
        }
        $this->validate($request, [
            'nameid' => 'required|integer',
        ]);

        $name = Name::findOrFail($request->input('nameid'));

        if (! ($name->invalid || ! is_null($name->end_at) || $name->needs_review)) {
            abort(403);
        }

        foreach ($name->user->names()->whereNotNull('active_at')->whereNull('end_at')->where('invalid', false)->get() as $item) {
            $item->end_at = Carbon::now();
            $item->save();
        }

        $name->invalid = false;
        $name->active_at = Carbon::now();
        $name->end_at = null;
        $name->needs_review = false;
        $name->save();

        $otherUser = Auth::user()->getOtherUserInfos($name->user_id);

        $natio = null;
        if ($otherUser->country == "FR") {
            $natio = 0;
        } else {
            $natio = 1;
        };
        
        if (is_null($natio)) {
            abort(403);
        }

        $date = str_replace (",","-", $name->birthday);
        $birth = Carbon::parse($date);

        $nameF = preg_split("/[\s,]+/", $name->name);

        if (!is_null(Auth::user()->identite)) {
            Auth::user()->identite->where('pid', '=', $otherUser->steamid)->delete();
        }

       
	    	$identity = new Identite();
	        $identity->pid = $otherUser->steamid;
	        $identity->prenom = $nameF[0];
	        $identity->nom = $nameF[1];
	        $identity->nationalite = $natio;
	        $identity->sexe = ($name->sexe)-1;
	        $identity->lieu_naissance = $name->lieu;
	        $identity->date_naissance = $birth->format('d/m/Y');
	        $identity->taille = intval($name->taille);
	        $identity->save();


        $discord = new DiscordClient(['token' => config('dash.token_discord')]);

        $roleID = "";
        if ($natio == 0) {
            $roleID = "607953366487662592"; //fr
        } else {
            $roleID = "607953344756973588"; //belge
        }

        //delete older roles
        $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id)]);
        foreach ($user_discord->roles as $key => $value) {
            if ($value == 607953344756973588) {
                $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953344756973588']);
            } elseif ($value == 607953366487662592) {
                $discord->guild->removeGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => '607953366487662592']);
            }
        }

        //adding roles
        $discord->guild->addGuildMemberRole(['guild.id' => 605812975848128540,'user.id' => intval($otherUser->discord_id),'role.id' => $roleID]);

        Cache::forget('user.'.$name->user->id.'.getSetupStep');

        return redirect('mod/names/'.$name->id);
    }

    //SUPPORT
    public function support_dashboard()
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-review*', 'mod-interview', 'mod-supervise*'])) {
            abort(403);
        }

        $datas = [['20/03/20',18,7],['21/03/20',20,8],['22/03/20',7,20],['23/03/20',70,20]];
        $keys_civ = [];
        $keys_fac = [];
        $labels = [];
        
        foreach ($datas as $key => $value) {
            array_push($labels, $value[0]);
            array_push($keys_civ, $value[1]);
            array_push($keys_fac, $value[2]);
        }

        $chart = new UserChart;
        $chart->labels($labels);
        $chart->dataset('Civils', 'line', $keys_civ)
            ->fill(false)
            ->linetension(0.3)
            ->color("rgb(35,201,225)");
        $chart->dataset('Factions', 'line', $keys_fac)
            ->fill(false)
            ->linetension(0.3)
            ->color("rgb(225,33,33)");

        return view('mod.support.dashboard')->with('user', Auth::user())->with('chart',$chart); //A
    }
    public function disableSanction(Request $request, $id)
    {
        $this->validate($request, [
            'userid' => 'required|integer',
        ]);

        $sanction = Sanctions::findOrFail($id);
        $user = User::findOrFail($request->input('userid'));

        //Ont vérifie si la sanction à lieu d'être levé
        if ($sanction->end_at > Carbon::now() && $sanction->active_at < Carbon::now() && $sanction->active == 1) {
            //On la désactive en DB et ont save
            $sanction->active = null;
            $sanction->end_at = Carbon::now();
            $sanction->save();
            //Si c'est un  ban, ont envoie à Maverick la requete
            if ($sanction->type == 1 && !is_null($sanction->ban_id)) {
                $ban_list = Auth::user()->getBanList()["Response"]["Bans"];
                $find = false;
                foreach ($ban_list as $key => $value) {
                    $id = (string)$value["Id"];

                    if ($id == $sanction->ban_id){
                        $find = $value;
                        break;
                    }
                }

                if ($find != false){
                    $params = ['key'=>'6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714', 'id'=>10820, 'ban_id'=> (int)$sanction->ban_id];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://armaremoteadmin.com/api/extern/v1/IServer/RCon/UnbanPlayer.ashx");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                    $whitelist = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
        return redirect('mod/sanctions/'.$sanction->id);
    }
}
