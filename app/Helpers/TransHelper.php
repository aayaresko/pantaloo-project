<?php

if (! function_exists('translate')) {

    /**
     * description.
     *
     * @param
     * @return
     */
    function translate($eng, $messages = [])
    {
        $lang = \Illuminate\Support\Facades\Config::get('lang');

        $translation = \App\Translation::where('eng', $eng)->first();

        if (! $translation) {
            $translation = new \App\Translation();
            $translation->eng = $eng;
            $translation->save();

            return msg($eng, $messages);
        } else {
            if ($lang == 'ru') {
                if ($translation->status == 1) {
                    return msg($translation->rus, $messages);
                } else {
                    return msg($eng, $messages);
                }
            } else {
                return msg($eng, $messages);
            }
        }
    }

    function msg($str, $messages)
    {
        foreach ($messages as $message) {
            $str = sprintf($str, $message);
        }

        return $str;
    }
}
