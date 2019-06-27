<?php

use Helpers\GeneralHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SitemapHelper
{
    const FREQ_ALWAYS = 'always';
    const FREQ_HOURLY = 'hourly';
    const FREQ_DAILY = 'daily';
    const FREQ_WEEKLY = 'weekly';
    const FREQ_MONTHLY = 'monthly';
    const FREQ_YEARLY = 'yearly';
    const FREQ_NEVER = 'never';

    const DATE_FORMAT = 'Y-m-dTH:i:sP';

    const MAIN_LANG = 'en';

    private static function prepare_url($template, $lang = self::MAIN_LANG)
    {
        return URL::to(str_replace('{lang}', $lang, $template));
    }

    private static function prepare_alternate($template, $langs)
    {
        $result = array_map(function ($v) use ($template) {
            return [
                'language' => $v,
                'url' => self::prepare_url($template, $v)
            ];
        }, $langs);

        return $result;
    }

    public static function gen()
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // add item with translations (url, date, priority, freq, images, title, translations)

        $languages = array_diff(GeneralHelper::getListLanguage(), [self::MAIN_LANG]);

        $relations = [
            ['array' => 'translations', 'urlPart' => ''],
            ['array' => 'translationsGames', 'urlPart' => 'games'],
            ['array' => 'translationsFaq', 'urlPart' => 'faq'],
            ['array' => 'translationsBonuses', 'urlPart' => 'bonuses'],
            ['array' => 'translationsPasswordForgot', 'urlPart' => 'password/forgot'],
            ['array' => 'translationsPasswordEmail', 'urlPart' => 'password/email'],
        ];

        $data = [
            '/{lang}' => [
                'priority' => 1,
                'freq' => self::FREQ_ALWAYS,
            ],
            '{lang}/games' => [
                'priority' => 0.7,
                'freq' => self::FREQ_DAILY,
            ],
        ];

        $date = date(self::DATE_FORMAT, time());

        foreach ($data as $urlTemplate => $extra) {

            dd(self::prepare_alternate($urlTemplate, $languages));

            $mainUrl = self::prepare_url($urlTemplate);

            $alternateUrls = [];

            foreach ($languages as $language) {
                $alternateUrls[] = [
                    'language' => $language,
                    'url' => self::prepare_url($urlTemplate, $language)
                ];
            }

            $sitemap->add($mainUrl, $date, $extra['priority'], $extra['freq'], [], null, $alternateUrls);
        }

//        foreach ($relations as $relation) {
//            ${$relation['array']} = [];
//            foreach ($languages as $language) {
//                array_push(${$relation['array']},
//                    ['language' => $language, 'url' => URL::to("/{$language}/" . $relation['urlPart'])]);
//            }
//        }
//
//        $getCategories = DB::table('games_types')
//            ->where('active', 1)->orderBy('id', 'desc')->get();
//
//        foreach ($getCategories as $category) {
//            $category_name = $category->default_name;
//            $category_name = preg_replace('/\s/', '-', $category_name);
//            $updated_at = $category->updated_at;
//
//            $translationsCategory = [];
//            foreach ($languages as $language) {
//                array_push($translationsCategory, ['language' => $language, 'url' => URL::to("/{$language}/games/" . $category_name)]);
//            }
//
//            $sitemap->add(URL::to('/en/games/' . $category_name), $updated_at, '0.7', 'daily', [], null, $translationsCategory);
//        }
//
//        $getGames = DB::table('games_list')->orderBy('system_id', 'desc')->get();
//
//        foreach ($getGames as $game) {
//            $game_id = $game->system_id;
//            $provider_id = $game->provider_id;
//            $updated_at = $game->updated_at;
//            $sitemap->add(URL::to('/integratedGameLink/provider/' . $provider_id . '/game/' . $game_id), $updated_at, '0.5', 'weekly');
//        }

        //dd($translations);

        //$sitemap->add(URL::to('/en'), date($dateFormat, time()), '1', 'always', [], null, $translations);
        //$sitemap->add(URL::to('/en/games'), date($dateFormat, time()), '0.7', 'daily', [], null, $translationsGames);
        //$sitemap->add(URL::to('/en/faq'), date($dateFormat, time()), '0.3', 'monthly', [], null, $translationsFaq);
        //$sitemap->add(URL::to('/en/bonuses'), date($dateFormat, time()), '0.3', 'monthly', [], null, $translationsBonuses);

        return $sitemap->render('xml');
    }
}

