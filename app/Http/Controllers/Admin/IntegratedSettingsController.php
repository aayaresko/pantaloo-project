<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Models\GamesListSettings;
use App\Http\Controllers\Controller;

/**
 * Class IntegratedSettingsController.
 */
class IntegratedSettingsController extends Controller
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $relatedFields;

    /**
     * IntegratedSettingsController constructor.
     */
    public function __construct()
    {
        $this->fields = [
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'value',
            4 => 'created_at',
            5 => 'updated_at',
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->fields)->get()->all();

        return view('admin.integrated_settings')->with([
            'definitionSettings' => $definitionSettings,
            'settings' => $settings,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $definitionSettings = $configIntegratedGames['listSettings'];
        $this->validate($request, [
            'games' => 'integer|in:'.implode(',', array_keys($definitionSettings)),
            'categories' => 'integer|in:'.implode(',', array_keys($definitionSettings)),
            'types' => 'integer|in:'.implode(',', array_keys($definitionSettings)),
        ]);

        DB::beginTransaction();

        try {
            if ($request->has('games')) {
                GamesListSettings::where('code', 'games')->update(['value' => $request->games]);
            }
            if ($request->has('categories')) {
                GamesListSettings::where('code', 'categories')->update(['value' => $request->categories]);
            }

            if ($request->has('types')) {
                GamesListSettings::where('code', 'types')->update(['value' => $request->types]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();

        return redirect()->route('admin.integratedSettings')->with('msg', 'Settings was edited');
    }
}
