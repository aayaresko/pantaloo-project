<?php

use Helpers\GeneralHelper;
use  \Illuminate\Support\Facades\Request;

if (!function_exists('LangAlternatePageUrl')) {

    function LangAlternatePageUrl($language, $path = false)
    {
        $path = $path ? $path : Request::path();

        $path = '/' == $path ? 'en' : $path;

        $placeholder = '::lang::';
        $langPattern = implode('|', GeneralHelper::getListLanguage());
        
        $relAlternateTemplate =  preg_replace("~^($langPattern)(?=/|$)~",$placeholder, $path);

        $alternateUrl =  str_replace($placeholder, $language, $relAlternateTemplate);

        return url($alternateUrl);
    }
}
