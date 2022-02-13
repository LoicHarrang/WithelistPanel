<?php

namespace App\Http\Controllers;

use App\Page;
use App\Role;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($slug)
    {
        $page = Cache::remember('pages.'.$slug, 1, function () use ($slug) {
            return Page::where('slug', $slug)->first();
        });

        if (! $page || $page->disabled) {
            abort(404, 'Page non rencontré');
        }

        var_dump($page);
        return;

        if ($page->step > 0) {
            if (auth()->user()->getSetupStep() < $page->step) {
                \Session::flash('status', 'Suivez les étapes avant d\'accéder à cette page');

                return redirect(route('setup-exam'));
            }
        }

        $this->data['title'] = $page->title;
        $this->data['page'] = $page;

        return view('pages.'.$page->template, $this->data);
    }

    public function listPages()
    {
        $pages = Page::all();

        return view('admin.pages.list')->with('pages', $pages);
    }

    public function createPage()
    {
        $roles = Role::all();

        return view('admin.pages.create')->with('roles', $roles);
    }
}
