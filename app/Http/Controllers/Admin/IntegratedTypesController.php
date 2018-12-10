<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

/**
 * Class IntegratedGamesController
 * @package App\Http\Controllers\Admin
 */
class IntegratedTypesController extends Controller
{

    /**
     * @var array
     */
    protected $fields;
    /**
     * @var array
     */
    protected $relatedFields;

    public function __construct()
    {
        $this->fields = ['id', 'code', 'name', 'image', 'active', 'rating', 'created_at', 'updated_at'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $fields = $this->fields;
//        $configIntegratedGames = config('integratedGames.common');
//        $dummyPicture = $configIntegratedGames['dummyPicture'];
//        View::share('dummyPicture', $dummyPicture);
        $gamesTypes = GamesType::select($fields)->get();
        return view('admin.integrated_types')->with(['gamesTypes' => $gamesTypes]);
    }

    public function edit(Request $request)
    {
        $fields = $this->fields;
        $type = GamesType::where('id', $request->id)->select($fields)->first();
        return view('admin.integrated_type')->with([
            'item' => $type,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        //to do validate image
        $this->validate($request, [
            'name' => 'string|min:3|max:100',
            'rating' => 'integer',
            'image' => 'image|max:1000|mimes:jpeg,png',//to do this to config file if will DRY
        ]);

        DB::beginTransaction();
        try {
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id . '.' . $image->getClientOriginalExtension();
                $pathImage = "/typesPictures/{$nameImage}";
                Storage::put('public' . $pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['image'] = '/storage' . $pathImage;
            }

            $active = $request->input('active');
            if (!is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            }

            unset($updatedGame['_token']);

            GamesType::where('id', $request->id)->update($updatedGame);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();
        return redirect()->route('admin.integratedType', $request->id)->with('msg', 'Type was edited');
    }
}
