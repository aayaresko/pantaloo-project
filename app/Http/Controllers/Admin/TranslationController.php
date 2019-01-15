<?php

namespace App\Http\Controllers\Admin;

use DB;
use File;
use App\Http\Requests;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class TranslationController extends Controller
{
    /**
     * @var array
     */
    private $params;

    /**
     * TranslationController constructor.
     */
    public function __construct()
    {
        $this->params = [];
        $this->params['defaultLang'] = 'en';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $languages = GeneralHelper::getListLanguage();
        return view('admin.translation_list', ['langs' => $languages]);
    }

    /**
     * @param $lang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeTranslation($lang)
    {
        $defaultLang = $this->params['defaultLang'];
        $translationsCurrent = $this->getTranslation($lang);
        $translationsDefault = $this->getTranslation($defaultLang);
        return view('admin.translation_lang', [
            'defaultLang' => $defaultLang,
            'translationsCurrent' => $translationsCurrent,
            'translationsDefault' => $translationsDefault,
            'currentLang' => $lang
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $this->validate($request, [
            'pk' => 'required|string',
            'value' => 'required|string',
            'name' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $currentLanguage = $request->name;
            $currentTranslation = $this->getTranslation($currentLanguage);
            $keyChangeValue = $request->pk;
            if (!isset($currentTranslation[$keyChangeValue])) {
                throw new \Exception('Translation not found');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        DB::commit();
        return response()->json(['success' => true]);
    }

    /**
     * @param $code
     * @return mixed
     */
    private function getTranslation($code)
    {
        $translations = File::getRequire(base_path() . "/resources/lang/{$code}/casino.php");
        return $translations;
    }
}
