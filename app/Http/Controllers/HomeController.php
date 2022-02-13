<?php

namespace App\Http\Controllers;

use App\Page;
use App\User;
use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required'])->except(['tos', 'monetization', 'privacy']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(['roles', 'permissions', 'exams', 'names'])->findOrFail(Auth::user()->id);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://armaremoteadmin.com/api/extern/v1/IWhitelist/GetWhitelist.ashx?key=6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714&id=10820");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $whitelist = curl_exec($ch);
        curl_close($ch);

        $posts = Cache::remember('home.posts', 5, function () {
            return Post::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        });
        $opening = Carbon::parse(config('dash.pop_opening'));
        $updated = [];
        $updated['rules'] = Cache::remember('home.updated.rules', 5, function () {
            $page = Page::where('slug', 'normas')->first();
            if (is_null($page)) {
                return Carbon::now()->subDays(100);
            }

            return $page->updated_at >= Carbon::now()->subDay();
        });
        $updated['download'] = Cache::remember('home.updated.download', 5, function () {
            $page = Page::where('slug', 'descargas')->first();
            if (is_null($page)) {
                return Carbon::now()->subDays(100);
            }

            return $page->updated_at >= Carbon::now()->subDay();
        });

        return view('home')
            ->with('user', $user)
            ->with('player', Auth::user()->player)
            ->with('opening', $opening)
            ->with('posts', $posts)
            ->with('updated', $updated)
            ->with('admin', Auth::user()->admin) //ajouté pour le systeme BETA
            ->with('whitelist', $whitelist);

    }

    public function rules()
    {
        $rules = Page::where('slug', 'normas')->first();
        return view('setup.rules')->with('rules', $rules);
    }

    public function tos()
    {
        return view('policy.tos');
    }

    public function monetization()
    {
        return view('policy.monetization');
    }

    public function privacy()
    {
        return view('policy.privacy');
    }

    public function about()
    {
        return view('policy.about');
    }
}
