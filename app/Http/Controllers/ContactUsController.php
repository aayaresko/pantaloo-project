<?php

namespace App\Http\Controllers;

use App\Mail\BaseMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        dd(request()->all());
        $data = $request->validate([
            'email' => 'required|email',
            'message' => 'required',
            'files' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
        $documents = $request->file('files');
        $allowedFileExtension = ['jpeg',
            'png',
            'jpg',
            'gif',
            'svg',
            ];

        //Check uploaded file size and type
        if ($documents->getError() == 1) {
            foreach ($documents as $document) {
                $maxSize = $document->getMaxFileSize() / 1024 / 1024;
                if ($document > $maxSize) {
                    return redirect()->back()->withErrors(['The document size must be less than 1Mb']);
                } else {
                    $extension = $document->getClientOriginalExtension();
                    $check = in_array($extension,$allowedFileExtension);
                    if ($check) {
                        return redirect()->back()->withErrors(['Sorry only upload images']);
                    }
                }
            }
            return redirect()->back()->with('Success');
        }


//        $mail =


//        $mail = new BaseMailable('emails.confirm', ['link' => $link]);
//        $mail->subject('Confirm email');
//        Mail::to('support@casinobit.io ')->send(new BaseMailable($));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
