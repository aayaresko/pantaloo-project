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

    private static $alternateLangs, $date, $sitemap, $entrys;

    public static function build()
    {
        self::init();

        self::add_entry_template('{lang?}', 1, self::FREQ_ALWAYS);
        self::add_entry_template('/{lang}/games', 0.7, self::FREQ_DAILY);
        self::add_entry_template('/{lang}/faq', 0.3, self::FREQ_MONTHLY);
        self::add_entry_template('/{lang}/bonuses', 0.3, self::FREQ_MONTHLY);

        self::add_categories_template();

        foreach (self::$entrys as $urlTemplate => $extra) {
            self::add_entry($urlTemplate, $extra);
        }

        return self::$sitemap->render('xml');
    }

    private static function init()
    {
        self::$alternateLangs = array_diff(GeneralHelper::getListLanguage(), [self::MAIN_LANG]);
        self::$date = date(self::DATE_FORMAT, time());
        self::$sitemap = App::make('sitemap');
    }

    private static function add_entry_template($urlTemplate, $priority, $freq, $extra = [])
    {
        $_extra['priority'] = $priority;
        $_extra['freq'] = $freq;

        self::$entrys[$urlTemplate] = array_merge($extra, $_extra);
    }

    private static function add_categories_template()
    {
        $getCategories = DB::table('games_types')->where('active', 1)->get();

        foreach ($getCategories as $category) {
            $urlTemplate = '{lang}/games/' . preg_replace('/\s/', '-', $category->default_name);
            self::add_entry_template($urlTemplate, 0.7, self::FREQ_DAILY, ['date' => $category->updated_at]);
        }
    }

    private static function prepare_url($template, $lang)
    {
        return URL::to(preg_replace('/{lang\??}/', $lang, $template));
    }

    private static function prepare_alternate($template)
    {
        $result = array_map(function ($v) use ($template) {
            return [
                'language' => $v,
                'url' => self::prepare_url($template, $v)
            ];
        }, self::$alternateLangs);

        return $result;
    }

    private static function add_entry($urlTemplate, $extra)
    {
        $ommitDefLang = false !== strpos($urlTemplate, "{lang?}");
        $mainUrl = self::prepare_url($urlTemplate, $ommitDefLang ? '' : self::MAIN_LANG);

        $priority = $extra['priority'];
        $freq = $extra['freq'];

        $date = isset($extra['date']) ? $extra['date'] : self::$date;

        $alternates = self::prepare_alternate($urlTemplate);

        self::$sitemap->add($mainUrl, $date, $priority, $freq, [], null, $alternates);
    }
}

