<?php

namespace App\Http\Controllers\Mod;

use App\Exam;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\GradeExam;
use App\User;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Auth::user()->hasPermission(['mod-search'])) {
            abort(403);
        }

        $results = Exam::query();

        $results->orderBy('updated_at', 'desc');
        $results->with('user');
        $results = $results->paginate(15);

        foreach($results as $exam) {
            $reussite = true;
            foreach($exam->structure as $key => $group) {
                $c = count($group['questions'])-1;
                 foreach($group['questions'] as $key => $question) {
                        $questionModel = \App\Question::find($question['id']);
                        $answer = \App\Answer::find($question['answer_id']);

                        if (is_null($answer) && $key == $c) {
                            $reussite = false;
                        } elseif (is_null($answer) && $key != $c) {
                            $reussite = true;
                        }
                 }
            }

            if (!$reussite) {
                $exam->passed_at = Carbon::now()->subMinutes(1);
                $exam->passed = 0;
                $exam->save();
            }
        }

        return view('mod.operateur.exams.index')->with('results', $results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Exam $exam
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        if (! Auth::user()->hasPermission(['mod-search'])) {
            abort(403);
        }

        $exam->load('answers', 'user');

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

         $user = User::findOrFail($exam->user_id);
        return view('mod.operateur.exams.show')->with('exam', $exam)->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Exam $exam
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Exam                $exam
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Exam $exam
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        abort(404);
    }
}
