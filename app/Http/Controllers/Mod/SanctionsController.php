<?php

namespace App\Http\Controllers\Mod;

use App\Http\Controllers\Controller;
use App\Name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Sanctions;

class SanctionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-search'])) {
            abort(403);
        }

        $user_query = User::query();
        $results_s = Sanctions::query();
        $results_s->where('active', '=', 1);

        if ($request->has('type')) {
            $results_s->where('type', 'LIKE', '%'.$request->input('type').'%')->get();
        } else {
            $results_s->get();
        }
       
        $results_s->orderBy('active_at', 'desc');
        $sanctions = $results_s->paginate(15);

        return view('mod.support.sanctions.index')->with('user_query', $user_query)->with('sanctions', $sanctions);
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
     * @param \App\Sanctions $sanction
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Sanctions $sanction)
    {
        if (! Auth::user()->hasPermission(['mod-search'])) {
            abort(403);
        }

        $user = User::findOrFail($sanction->user_id);

        return view('mod.support.sanctions.show')->with('user', $user)->with('sanction',$sanction);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Name $name
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Name $name)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Name                $name
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Name $name)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Name $name
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Name $name)
    {
        abort(404);
    }
}
