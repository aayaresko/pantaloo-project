<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\GamesList;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesTypeGame;
use App\Models\GamesListExtra;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

/**
 * Class IntegratedTypesController.
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
            'defaultItems' => $gamesTypes,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $adminConfig = config('adminPanel');
        $imageConfig = $adminConfig['image'];

        $this->validate($request, [
            'name' => 'string|min:3|max:100',
            'rating' => 'integer',
            'ratingItems' => 'integer|nullable',
            'image' => "image|max:{$imageConfig['maxSize']}|mimes:".implode(',', $imageConfig['mimes']),
            'toType_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id.time().'.'.$image->getClientOriginalExtension();
                $pathImage = "/typesPictures/{$nameImage}";
                //Storage::delete('public' . $pathImage);
                Storage::put('public'.$pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['image'] = '/storage'.$pathImage;
            }

            $active = $request->input('active');
            $defaultAll = $request->input('defaultAll');

            if (! is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            } else {
                $updatedGame['active'] = 0;
            }

            //act
            if (!is_null($request->ratingItems)) {
                //to do optimize this code
                $gamesToUpdateArrayIds = DB::table('games_types_games')->select(['games_list.id'])
                    ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                    ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                    ->where([
                        ['games_types_games.extra', '=', 1],
                        ['games_types_games.type_id', '=', $request->id],
                    ])
                    ->groupBy('games_types_games.game_id')->get()->pluck('id');

                GamesList::whereIn('id', $gamesToUpdateArrayIds)->update(['rating' => $request->ratingItems]);
            } else {
                unset($updatedGame['ratingItems']);
            }

            //act
            if ($request->toType_id != 0) {
                $gamesToUpdate = DB::table('games_types_games')->select(['games_list.id'])
                    ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                    ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                    ->where([
                        ['games_types_games.extra', '=', 1],
                        ['games_types_games.type_id', '=', $request->id],
                    ])
                    ->groupBy('games_types_games.game_id')->get()->all();

                //to do optimize this
                foreach ($gamesToUpdate as $game) {
                    GamesTypeGame::create([
                        'game_id' => $game->id,
                        'type_id' => $request->toType_id,
                        'extra' => 1,
                    ]);
                }
                unset($game);
            }

            if (! is_null($defaultAll)) {
                if ($defaultAll === 'on') {
                    $gamesUpdate = GamesTypeGame::select(['games_list.id'])
                        ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                        ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                        ->where([
                            ['games_types_games.type_id', '=', $request->id],
                            ['games_types_games.extra', '=', 0],
                        ])
                        ->groupBy('games_types_games.game_id')
                        ->get();

                    $gamesTypes = GamesTypeGame::select(['games_list.id',
                        DB::raw('group_concat(games_types_games.type_id) as type'), ])
                        ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                        ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                        ->where([
                            ['games_types_games.extra', '=', 0],
                        ])
                        ->groupBy('games_types_games.game_id')
                        ->get()->keyBy('id');

                    //delete all types extra
                    $toDelete = [];
                    foreach ($gamesUpdate as $game) {
                        array_push($toDelete, $game->id);
                    }
                    unset($game);
                    //delete
                    GamesTypeGame::where('extra', '=', 1)->whereIn('game_id', $toDelete)->delete();

                    //to do optimize this
                    //add old types extra
                    foreach ($gamesUpdate as $game) {
                        $defaultTypes = $gamesTypes[$game->id]->type;
                        $defaultTypesArray = explode(',', $defaultTypes);
                        foreach ($defaultTypesArray as $defaultTypeArray) {
                            GamesTypeGame::create([
                                'game_id' => $game->id,
                                'type_id' => $defaultTypeArray,
                                'extra' => 1,
                            ]);
                        }
                        unset($type);
                    }
                    unset($game);

                    unset($updatedGame['defaultAll']);
                }
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
