<?php

namespace App\Http\Controllers\Admin;

use DB;
use File;
use Validator;
use App\Models\Language;
use App\Models\Translation;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

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
     * @throws \Exception
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

        try {
            $currentLanguage = $request->name;
            $currentTranslation = $this->getTranslation($currentLanguage);
            $keyChangeValue = $request->pk;
            if (!isset($currentTranslation[$keyChangeValue])) {
                throw new \Exception('Translation not found');
            }
            $currentTranslation[$keyChangeValue] = $request->value;
            $this->changeFileTranslation($currentLanguage, $currentTranslation);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

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

    private function changeFileTranslation($code, $data)
    {
        $datafile = 'lang' . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR . 'casino.data';
        Storage::put($datafile, serialize($data));
        return true;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions(Request $request)
    {
        $param['columns'] = [];
        $param['columnsAlias'] = [
            'translator_translations.group as group',
            'translator_translations.item as item',
            'translator_translations.text as text',
            'cur_lang.group as cur_group',
            'cur_lang.item as cur_item',
            'cur_lang.text as cur_text',
            DB::raw("CONCAT(translator_translations.group, '', translator_translations.item) as code")
        ];

        $param['defaultLang'] = $request->defaultLang;
        $param['currentLang'] = $request->currentLang;

        $param['whereCompare'] = [
            ['translator_translations.locale', '=', $param['defaultLang']]
        ];

        /* ACT */
        $whereCompare = $param['whereCompare'];

        $countSum = Translation::where($whereCompare)->count();

        $totalData = $countSum;
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $param['start'] = $start;

        if (empty($request->input('search.value'))) {
            /* SORT */
            $items = Translation::leftJoin('translator_translations as cur_lang',
                function ($join) use ($param) {
                    $join->on('translator_translations.item', '=', 'cur_lang.item')
                        ->on('translator_translations.group', '=', 'cur_lang.group')
                        ->where('cur_lang.locale', '=', $param['currentLang']);
                })
                ->where($whereCompare)
                ->offset($start)
                ->limit($limit)
                ->select($param['columnsAlias'])
                ->get();
        } else {
            /* SEARCH */
            $search = $request->input('search.value');
            $preSearch = "%$search%";
            $whereRaw = "(translator_translations.text LIKE ? or cur_lang.text LIKE ?)";

            $items = Translation::leftJoin('translator_translations as cur_lang',
                function ($join) use ($param) {
                    $join->on('translator_translations.item', '=', 'cur_lang.item')
                        ->on('translator_translations.group', '=', 'cur_lang.group')
                        ->where('cur_lang.locale', '=', $param['currentLang']);
                })
                ->where($whereCompare)
                ->whereRaw($whereRaw, [$preSearch, $preSearch])
                ->offset($start)
                ->limit($limit)
                ->select($param['columnsAlias'])
                ->get();

            $countSum = Translation::leftJoin('translator_translations as cur_lang',
                function ($join) use ($param) {
                    $join->on('translator_translations.item', '=', 'cur_lang.item')
                        ->on('translator_translations.group', '=', 'cur_lang.group')
                        ->where('cur_lang.locale', '=', $param['currentLang']);
                })
                ->where($whereCompare)
                ->whereRaw($whereRaw, [$preSearch, $preSearch])
                ->count();

            $totalFiltered = $countSum;
        }
        /* END */

        /* TO VIEW */
        $data = $items;
        $data->map(function ($item, $key) use ($param) {
            $item->cur_text = view('admin.parts.ckeditor_inline',
                [
                    'html' => $item->cur_text,
                    'group' => $item->group,
                    'item' => $item->item,
                    'key' => $key,
                ]
            )->render();

            $item->key = $param['start'] + ($key + 1);
            return $item;
        });

        $jsonData = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return response()->json($jsonData);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexModern()
    {
        return view('admin.translation_list');
    }

    /**
     * @param $lang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeTranslationModern($lang)
    {
        $defaultLang = $this->params['defaultLang'];

        return view('admin.translation_lang_modern', [
            'defaultLang' => $defaultLang,
            'currentLang' => $lang
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function saveModern(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'currentLang' => 'required|string',
                'group' => 'required|string',
                'item' => 'required|string',
                'text' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => $validator->errors()->first()
                ]);
            }

            $request->text = $this->ckeditorFeatureValue($request->text);

            //get value
            $trans = Translation::where([
                ['locale', '=', $request->currentLang],
                ['group', '=', $request->group],
                ['item', '=', $request->item]
            ])->select(['id'])->first();

            if (is_null($trans)) {
                //create
                Translation::create([
                    'locale' => $request->currentLang,
                    'group' => $request->group,
                    'item' => $request->item,
                    'text' => $request->text,
                ]);
            } else {
                //update
                Translation::where('id', $trans->id)->update([
                    'text' => $request->text,
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'msg' => $ex->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Done'
        ]);
    }

    protected function ckeditorFeatureValue($value)
    {
        preg_match("/^<p>[^<>]+<\/p>$/", $value, $regular);

        if (!empty($regular)) {
            $value = strip_tags($value);
        }

        return $value;
    }
}
