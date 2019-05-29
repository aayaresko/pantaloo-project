<?php

namespace App\Http\Controllers\Admin;

use DB;
use File;
use App\Models\Language;
use App\Models\Translation;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        file_put_contents(base_path() . "/resources/lang/{$code}/casino.php",
            '<?php' . PHP_EOL . 'return ' . var_export($data, true) . PHP_EOL . '?>');
        return true;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions(Request $request)
    {
        $param['columns'] = [];
        $param['columnsAlias'] = [];

        $param['currentLang'] = $request->currentLang;
        $param['defaultLang'] = $request->defaultLang;

        $param['whereCompare'] = [
            ['translator_translations.locale', '=', $param['defaultLang']]
        ];

        /* ACT */
        $whereCompare = $param['whereCompare'];

        $countSum = Translation::select([DB::raw('COUNT(*) as `count`')])
            ->where($whereCompare)
            ->first()->toArray();

        $totalData = $countSum->count;
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $order = $param['columns'][$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            /* SORT */
            $items = Translation::leftJoin('translator_translations', 'translator_translations.item', '=', 'games_list.id')
                ->leftJoin('translator_translations', function ($join) use ($param) {
                    $join->on('translator_translations.item', '=', 'translator_translations.item')
                        ->where('translator_translations.locale', '=', $param['currentLang']);
                })
                ->where($whereCompare)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select($param['columnsAlias'])->get();
            dd($items);
        } else {
            /* SEARCH */
            $search = $request->input('search.value');

            if (is_numeric($search)) {
                array_push($whereCompare, [$param['columns'][0], 'LIKE', "%{$search}%"]);
            } else {
                array_push($whereCompare, [$param['columns'][1], 'LIKE', "%{$search}%"]);
            }

            $items = GamesTypeGame::select([DB::raw('COUNT(*) as `count`')])
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->where($whereCompare)
                ->groupBy('games_types_games.game_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select($param['columnsAlias'])->get();

            $countSum = GamesTypeGame::select([DB::raw('COUNT(*) as `count`')])
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->where($whereCompare)
                ->groupBy('games_types_games.game_id')
                ->get()->toArray();

            $totalFiltered = count($countSum);
        }
        /* END */

        /* TO VIEW */
        $data = $items;
        $configIntegratedGames = config('integratedGames.common');
        $param['providers'] = $configIntegratedGames['providers'];

        $data->map(function ($item, $key) use ($param) {
            $idProvider = $item->provider;
            $item->provider = $param['providers'][$idProvider]['code'];
            $item->edit = view('admin.parts.buttons', ['id' => $item->id])->render();
            $item->image = view('admin.parts.imageTable', ['image' => $item->image])->render();
            $item->mobile = view('admin.parts.switch', ['switch' => $item->mobile])->render();
            $item->active = view('admin.parts.switch', ['switch' => $item->active])->render();

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
}
