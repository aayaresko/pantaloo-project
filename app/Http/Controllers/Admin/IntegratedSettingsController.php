<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\GamesListSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Class IntegratedSettingsController
 * @package App\Http\Controllers\Admin
 */
class IntegratedSettingsController extends Controller
{

    /**
     * IntegratedSettingsController constructor.
     */
    public function __construct()
    {
        //to do field
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::all();
        return view('admin.integrated_settings')->with([
            'definitionSettings' => $definitionSettings,
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        //to do validate image
        $configIntegratedGames = config('integratedGames.common');
        $definitionSettings = $configIntegratedGames['listSettings'];
        dump(implode(',', array_keys($definitionSettings)));

        $this->validate($request, [
            'games' => 'integer|in:' . implode(',', array_keys($definitionSettings)),
            'categories' => 'integer|in:' . implode(',', array_keys($definitionSettings)),
            'types' => 'integer|in:' . implode(',', array_keys($definitionSettings)),
        ]);

        DB::beginTransaction();
        try {
            if ($request->has('games')) {
                GamesListSettings::where('code','games')->update(['valufde' => $request->games]);
            }
            if ($request->has('categories')) {
                GamesListSettings::where('code','categories')->update(['value' => $request->categories]);
            }

            if ($request->has('types')) {
                GamesListSettings::where('code','types')->update(['value' => $request->types]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();
        return redirect()->route('admin.integratedSettings')->with('msg', 'Settings was edited');
    }
}
