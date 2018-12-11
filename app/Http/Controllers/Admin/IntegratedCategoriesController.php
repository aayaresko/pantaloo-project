<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\GamesList;
use App\Models\GamesCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

/**
 * Class IntegratedCategoriesController
 * @package App\Http\Controllers\Admin
 */
class IntegratedCategoriesController extends Controller
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
     * IntegratedTypesController constructor.
     */
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
        $gamesTypes = GamesCategory::select($fields)->get();
        return view('admin.integrated_categories')->with(['gamesTypes' => $gamesTypes]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $fields = $this->fields;
        $type = GamesCategory::where('id', $request->id)->select($fields)->first();
        return view('admin.integrated_category')->with([
            'item' => $type,
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
        ]);

        DB::beginTransaction();
        try {
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id . time() . '.' . $image->getClientOriginalExtension();
                $pathImage = "/categotyPictures/{$nameImage}";
                Storage::put('public' . $pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['image'] = '/storage' . $pathImage;
            }

            $active = $request->input('active');
            if (!is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            }

            if (isset($request->ratingItems)) {
                if ($request->ratingItems != '') {
                    GamesList::where('category_id', $request->id)->update(['rating' => $request->ratingItems]);
                }
                unset($updatedGame['ratingItems']);
            }

            unset($updatedGame['_token']);
            GamesCategory::where('id', $request->id)->update($updatedGame);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();
        return redirect()->route('admin.integratedCategory', $request->id)->with('msg', 'Type was edited');
    }
}
