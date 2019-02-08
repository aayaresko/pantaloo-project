<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Domain;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $file_name = uniqid() . '.' . $extension;
        $path = storage_path() . '/app/public/promo/';

        $url = url('/') . '/storage/promo/' . $file_name;
        $image = request()->image;
        Storage::put('public/promo/' . $file_name, file_get_contents($image->getRealPath()));

        $size = getimagesize($path . $file_name);

        $banner = new Banner();
        $banner->path = $path . $file_name;
        $banner->url = $url;
        $banner->type = $extension;
        $banner->size = $size[0] . ' x ' . $size[1];
        $banner->save();

        return redirect()->route('banners.create')->with('msg', 'Banner was created!');
    }

    public function delete(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners')->with('msg', 'Banner was deleted!');
    }
}
