<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    public function get($page_url)
    {
        $page = Page::where('url', $page_url);

        if(Config::get('lang') == 'en') $page = $page->where('parent_id', 0);
        else $page = $page->where('parent_id', '!=', 0);

        $page = $page->first();

        if(!$page) abort(404);

        return view('page', ['page' => $page]);
    }

    public function index()
    {
        return view('admin.pages', ['pages' => Page::where('parent_id', 0)->get()]);
    }

    public function create()
    {
        return view('admin.pages_new');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'short_name' => 'required|alpha',
            'url' => 'required|alpha',
            'title' => 'required',
            'body' => 'required'
        ]);

        $page = new Page();
        $page->fill($request->all());

        if($request->input('is_main')) $page->is_main = 1;
        else $page->is_main = 0;

        $page->save();

        $rus_page = new Page();
        $rus_page->fill($request->all());

        if($request->input('is_main')) $rus_page->is_main = 1;
        else $rus_page->is_main = 0;

        $rus_page->parent_id = $page->id;

        $rus_page->save();


        return redirect()->route('pages')->with('msg', 'Page was created!');
    }

    public function edit(Page $page)
    {
        if($page->parent_id == 0)
        {
            $page_lang = [
                'link' => route('pages.edit', Page::where('parent_id', $page->id)->first()),
                'link_title' => 'Russian version',
                'version' => 'English version'
            ];
        }
        else
        {
            $page_lang = [
                'link' => route('pages.edit', Page::where('id', $page->parent_id)->first()),
                'link_title' => 'English version',
                'version' => 'Russian version'
            ];
        }

        return view('admin.pages_edit', ['page' => $page, 'page_lang' => $page_lang]);
    }

    public function update(Page $page, Request $request)
    {
        $this->validate($request, [
            'short_name' => 'required|alpha',
            'url' => 'required|alpha',
            'title' => 'required',
            'body' => 'required'
        ]);

        $page->fill($request->all());

        if($request->input('is_main')) $page->is_main = 1;
        else $page->is_main = 0;

        $page->save();

        return redirect()->back()->with('msg', 'Page was updated!');
    }

    public function delete(Page $page)
    {
        if($page->parent_id == 0)
        {
            Page::where('parent_id', $page->id)->delete();

            $page->delete();
        }
        else return redirect()->back()->withErrors([
            'Russian version'
        ]);

        return redirect()->route('pages')->with('msg', 'Page was deleted!');
    }
}
