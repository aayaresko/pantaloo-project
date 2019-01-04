<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\GamesList;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesListExtra;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Class IntegratedTypesController
 * @package App\Http\Controllers\Admin
 */
class IntegratedTypesController extends Controller
{

    protected $params;
    /**
     * @var array
     */
    protected $fields;
    /**
     * @var array
     */
    protected $relatedFields;

    /**
     * IntegratedTypesController constructor.
     */
    public function __construct()
    {
        $this->params = [];
        $this->params['updateItems'] = 100;
        $this->fields = ['id', 'code', 'name', 'default_name', 'image', 'active', 'rating', 'created_at', 'updated_at'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $fields = $this->fields;
        $gamesTypes = GamesType::select($fields)->get();
        return view('admin.integrated_types')->with(['gamesTypes' => $gamesTypes]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $fields = $this->fields;

        $adminConfig = config('adminPanel');
        $imageConfig = $adminConfig['image'];
        View::share('maxSizeImage', $imageConfig['maxSize']);
        View::share('typesImage', $imageConfig['mimes']);

        $typesDefault = config('appAdditional.defaultTypes');
        $typesDefaultId = array_column($typesDefault, 'id');
        $gamesTypes = GamesType::select(['id', 'name'])->whereIn('id', $typesDefaultId)->get();

        $type = GamesType::where('id', $request->id)->select($fields)->first();
        return view('admin.integrated_type')->with([
            'item' => $type,
            'defaultItems' => $gamesTypes
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $adminConfig = config('adminPanel');
        $imageConfig = $adminConfig['image'];

        $this->validate($request, [
            'name' => 'string|min:3|max:100',
            'rating' => 'integer',
            'ratingItems' => 'integer',
            'image' => "image|max:{$imageConfig['maxSize']}|mimes:" . implode(',', $imageConfig['mimes']),
            'toType_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id . time() . '.' . $image->getClientOriginalExtension();
                $pathImage = "/typesPictures/{$nameImage}";
                //Storage::delete('public' . $pathImage);
                Storage::put('public' . $pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['image'] = '/storage' . $pathImage;
            }

            $active = $request->input('active');
            $defaultAll = $request->input('defaultAll');


            if (!is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            } else {
                $updatedGame['active'] = 0;
            }

            if (isset($request->ratingItems)) {
                if ($request->ratingItems != '') {
                    GamesList::where('type_id', $request->id)->update(['rating' => $request->ratingItems]);
                }
                unset($updatedGame['ratingItems']);
            }

            if ($request->toType_id != 0) {
                GamesListExtra::where('type_id', $request->id)->update(['type_id' => $request->toType_id]);
            }

            if (!is_null($defaultAll)) {
                if ($defaultAll === 'on') {
                    DB::table('games_list')->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                        ->where('games_list.type_id', $request->id)
                        ->update(['games_list_extra.type_id' => DB::raw('games_list.type_id')]);
                    unset($updatedGame['defaultAll']);
                }
            }

            if (isset($request->ratingItems)) {
                if ($request->ratingItems != '') {
                    GamesList::where('type_id', $request->id)->update(['rating' => $request->ratingItems]);
                }
                unset($updatedGame['ratingItems']);
            }

            unset($updatedGame['_token']);
            unset($updatedGame['toType_id']);
            GamesType::where('id', $request->id)->update($updatedGame);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();
        return redirect()->route('admin.integratedType', $request->id)->with('msg', 'Type was edited');
    }
}
