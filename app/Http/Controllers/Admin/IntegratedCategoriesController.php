<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Country;
use App\Models\GamesList;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Models\RestrictionCategoriesCountry;

/**
 * Class IntegratedCategoriesController.
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
        $this->fields = ['id', 'code', 'name', 'default_name', 'image', 'active', 'rating', 'created_at', 'updated_at'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $fields = $this->fields;
        $gamesTypes = GamesCategory::select($fields)->get()->all();

        return view('admin.integrated_categories')->with(['gamesTypes' => $gamesTypes]);
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

        $type = GamesCategory::where('id', $request->id)->select($fields)->first();

        //work with country - to one request
        $categoryId = $request->id;
        $countries = Country::all();
        $markConfig = config('appAdditional.restrictionMark');
        $allowGameCountry = RestrictionCategoriesCountry::where('category_id', $categoryId)
            ->where('mark', $markConfig['enable'])
            ->pluck('code_country')->toArray();

        $banGameCountry = RestrictionCategoriesCountry::where('category_id', $categoryId)
            ->where('mark', $markConfig['disable'])
            ->pluck('code_country')->toArray();
        $categoryCountries = [
            'allow' => $allowGameCountry,
            'ban' => $banGameCountry,
        ];

        return view('admin.integrated_category')->with([
            'item' => $type,
            'countries' => $countries,
            'categoryCountries' => $categoryCountries,
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
            'ratingItems' => 'integer|nullable',
            'allowCountryCategories_codes.*' => 'exists:countries,code',
            'banCountryCategories_codes.*' => 'exists:countries,code',
            'image' => "image|max:{$imageConfig['maxSize']}|mimes:".implode(',', $imageConfig['mimes']),
        ]);

        DB::beginTransaction();

        try {
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id.time().'.'.$image->getClientOriginalExtension();
                $pathImage = "/categotyPictures/{$nameImage}";
                Storage::put('public'.$pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['image'] = '/storage'.$pathImage;
            }

            $active = $request->input('active');
            if (! is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            } else {
                $updatedGame['active'] = 0;
            }

            if (!is_null($request->ratingItems)) {
                GamesList::where('category_id', $request->id)->update(['rating' => $request->ratingItems]);
            }

            unset($updatedGame['ratingItems']);
            unset($updatedGame['_token']);
            unset($updatedGame['banCountryCategories_codes']);
            unset($updatedGame['allowCountryCategories_codes']);
            GamesCategory::where('id', $request->id)->update($updatedGame);

            /* work with restriction */
            $categoryId = $request->id;
            $markConfig = config('appAdditional.restrictionMark');
            $currentDate = new \DateTime();
            //ALLOW
            if ($request->filled('allowCountryCategories_codes')) {
                $restrictionAllowItems = [];

                RestrictionCategoriesCountry::where('category_id', $categoryId)
                    ->where('mark', $markConfig['enable'])->delete();

                $allowCountryCategories = $request['allowCountryCategories_codes'];

                foreach ($allowCountryCategories as $allowCode) {
                    array_push($restrictionAllowItems, [
                        'category_id' => $categoryId,
                        'code_country' => $allowCode,
                        'mark' => $markConfig['enable'],
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
                RestrictionCategoriesCountry::insert($restrictionAllowItems);
            } else {
                //clear
                RestrictionCategoriesCountry::where('category_id', $categoryId)
                    ->where('mark', $markConfig['enable'])->delete();
            }

            //BAN
            if ($request->filled('banCountryCategories_codes')) {
                $restrictionBanItems = [];

                RestrictionCategoriesCountry::where('category_id', $categoryId)
                    ->where('mark', $markConfig['disable'])->delete();

                $banCountryCategories = $request['banCountryCategories_codes'];

                foreach ($banCountryCategories as $banCode) {
                    array_push($restrictionBanItems, [
                        'category_id' => $categoryId,
                        'code_country' => $banCode,
                        'mark' => $markConfig['disable'],
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
                RestrictionCategoriesCountry::insert($restrictionBanItems);
            } else {
                //clear
                RestrictionCategoriesCountry::where('category_id', $categoryId)
                    ->where('mark', $markConfig['disable'])->delete();
            }
            /* end work with restriction */
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();

        return redirect()->route('admin.integratedCategory', $request->id)->with('msg', 'Type was edited');
    }
}
