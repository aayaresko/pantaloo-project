<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('contact_us');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required',
            'files.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);
        $documents = $request->file('files');
        $paths = [];
        //Check count uploaded file
        if ($documents) {
            $documentsCount = count($documents);
            if ($documentsCount > 5) {
                $errorsArr = [
                    "message" => "The given data was invalid.",
                    "errors" => "max_file_count",
                ];
                return response()->json($errorsArr);
            }
            foreach ($documents as $document) {
                $fileName = sha1($document->getFilename() . time()) . '.' . $document->getClientOriginalExtension();
                if (!Storage::disk('local')->exists(storage_path('app/mailImages'))) {
                    Storage::makeDirectory(storage_path('app/mailImages'));
                }
                $paths[] = $document->storeAs('mailImages', $fileName);
            }
        }
        $email = $_POST['email'];
        $mess = $_POST['message'];
        Mail::raw("Message: $mess. From: $email", function ($message) use ($paths) {
            foreach ($paths as $path) {
                $message->to('support@casinobit.io ');
                $message->attach(storage_path('app/') . $path);
            }
        });
        if (count(Mail::failures()) > 0) {
            return response()->json([
                "message" => "Email",
                "errors" => "email_not_delivery",
            ]);
        }
        foreach ($paths as $path) {
            \File::delete(storage_path('app/' . $path));
        }
        return response()->json(["message" => "success"]);

    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
