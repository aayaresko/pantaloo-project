<?php

namespace App\Console\Commands;

use Helpers\GeneralHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class createSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createSitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a sitemap for website';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap', 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {

            // add item with translations (url, date, priority, freq, images, title, translations)
            $languages  = GeneralHelper::getListLanguage();

            $relations = [
                ['array' => 'translations', 'urlPart' => ''],
                ['array' => 'translationsGames', 'urlPart' => 'games'],
                ['array' => 'translationsFaq', 'urlPart' => 'faq'],
                ['array' => 'translationsBonuses', 'urlPart' => 'bonuses'],
                ['array' => 'translationsPasswordForgot', 'urlPart' => 'password/forgot'],
                ['array' => 'translationsPasswordEmail', 'urlPart' => 'password/email'],
            ];

            foreach ($relations as $relation) {
                //dd($relation['array']);
                ${$relation['array']} = [];
                foreach ($languages as $language) {
                    array_push(${$relation['array']}, ['language' => $language, 'url' =>  URL::to("/{$language}/" . $relation['urlPart'])]);
                }
            }
//            dd($translationsBonuses);
            $getCategories = DB::table('games_types')->where('active', 1)->orderBy('id', 'desc')->get();

            foreach ($getCategories as $category) {
                $category_name = $category->default_name;
                $category_name = preg_replace('/\s/', '-', $category_name);
                $updated_at = $category->updated_at;

                $translationsCategory = [
                    ['language' => 'de', 'url' => URL::to('/de/games/' . $category_name)],
                    ['language' => 'en', 'url' => URL::to('/en/games/' . $category_name)],
                    ['language' => 'fr', 'url' => URL::to('/fr/games/' . $category_name)],
                    ['language' => 'it', 'url' => URL::to('/it/games/' . $category_name)],
                    ['language' => 'jp', 'url' => URL::to('/jp/games/' . $category_name)],
                    ['language' => 'ru', 'url' => URL::to('/ru/games/' . $category_name)],
                    ['language' => 'th', 'url' => URL::to('/th/games/' . $category_name)],
                    ['language' => 'vn', 'url' => URL::to('/vn/games/' . $category_name)],
                ];
                $sitemap->add(URL::to('/en/games/' . $category_name), $updated_at, '0.7', 'daily', [], null, $translationsCategory);
            }

            $getgames = DB::table('games_list')->orderBy('system_id', 'desc')->get();

            foreach ($getgames as $game) {
                $game_id = $game->system_id;
                $provider_id = $game->provider_id;
                $updated_at = $game->updated_at;
                $sitemap->add(URL::to('/integratedGameLink/provider/' . $provider_id . '/game/' . $game_id), $updated_at, '0.5', 'weekly');
            }
            $sitemap->add(URL::to('/en'), date('Y-m-dTH:i:sP', time()), '1', 'always', [], null);
            $sitemap->add(URL::to('/en/games'), date('Y-m-dTH:i:sP', time()), '0.7', 'daily', [], null);
            $sitemap->add(URL::to('/en/faq'), date('Y-m-dTH:i:sP', time()), '0.3', 'monthly', [], null);
            $sitemap->add(URL::to('/en/bonuses'), date('Y-m-dTH:i:sP', time()), '0.3', 'monthly', [], null);

        }
        $sitemap->store('xml', 'sitemap', storage_path("app/public"));
    }
}
