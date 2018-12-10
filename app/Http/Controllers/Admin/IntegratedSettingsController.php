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
        $settings = GamesListSettings::all();
        return view('admin.integrated_settings')->with(['settings' => $settings]);
    }

    public function update(Request $request)
    {

    }
}
