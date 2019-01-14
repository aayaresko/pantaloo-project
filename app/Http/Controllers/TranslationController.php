<?php

namespace App\Http\Controllers;

use App\Translation;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TranslationController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLanguage(Request $request, $lang)
    {
        $timeKeepLang = config('appAdditional.keepLanguage');
        Cookie::queue('lang', $lang, $timeKeepLang);
        return redirect()->back();
    }

    public function index()
    {
        $translations = Translation::all();

        return view('admin.translations', ['translations' => $translations]);
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'pk' => 'required',
            //'value' => 'required'
        ]);

        $translation = Translation::find($request->input('pk'));

        if(!$translation) return redirect()->back()->withErrors(['Translation not found']);

        $translation->rus  = $request->input('value');
        $translation->status = 1;
        $translation->save();

        return response()->json(['success' => 1]);
    }

    public function delete(Request $request)
    {
        $translation = Translation::find($request->input('id'));

        if($translation)
            $translation->delete();

        return response()->json(['success' => 1]);
    }
}
