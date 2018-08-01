<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Domain;
use Illuminate\Http\Request;

use App\Http\Requests;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();

        return view('admin.banners', ['banners' => $banners]);
    }

    public function view()
    {
        $banners = Banner::all();

        return view('agent.banners', ['banners' => $banners]);
    }

    public function create()
    {
        return view('admin.banners_new');
    }

    public function store(Request $request)
    {
        $this->validate($request, ['image' => 'required|image']);

        $parts = explode('/', $request->file('image')->getMimeType());
        $extension = $parts[1];

        $domains = Domain::all();

        $file_name = uniqid() . '.' . $extension;
        $path = public_path() . '/promo/';

        $url = 'http://' . $domains[0]->domain . '/promo/' . $file_name;

        $request->file('image')->move($path, $file_name);

        $size = getimagesize($path . $file_name);

        $banner = new Banner();
        $banner->path = $path . $file_name;
        $banner->url = $url;
        $banner->type = $extension;
        $banner->size = $size[0] . ' x ' . $size[1];
        $banner->save();

        return redirect()->route('admin.banners')->with('msg', 'Banner was created!');
    }

    public function delete(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners')->with('msg', 'Banner was deleted!');
    }
}
