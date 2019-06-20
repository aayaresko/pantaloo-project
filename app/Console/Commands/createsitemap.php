<?php

namespace App\Console\Commands;

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
            $translations = [
                ['language' => 'de', 'url' => URL::to('/de')],
                ['language' => 'en', 'url' => URL::to('/en')],
                ['language' => 'fr', 'url' => URL::to('/fr')],
                ['language' => 'it', 'url' => URL::to('/it')],
                ['language' => 'jp', 'url' => URL::to('/jp')],
                ['language' => 'ru', 'url' => URL::to('/ru')],
                ['language' => 'th', 'url' => URL::to('/th')],
                ['language' => 'vn', 'url' => URL::to('/vn')],
            ];
            $translationsGames = [
                ['language' => 'de', 'url' => URL::to('/de/games')],
                ['language' => 'en', 'url' => URL::to('/en/games')],
                ['language' => 'fr', 'url' => URL::to('/fr/games')],
                ['language' => 'it', 'url' => URL::to('/it/games')],
                ['language' => 'jp', 'url' => URL::to('/jp/games')],
                ['language' => 'ru', 'url' => URL::to('/ru/games')],
                ['language' => 'th', 'url' => URL::to('/th/games')],
                ['language' => 'vn', 'url' => URL::to('/vn/games')],
            ];
            $translationsFaq = [
                ['language' => 'de', 'url' => URL::to('/de/faq')],
                ['language' => 'en', 'url' => URL::to('/en/faq')],
                ['language' => 'fr', 'url' => URL::to('/fr/faq')],
                ['language' => 'it', 'url' => URL::to('/it/faq')],
                ['language' => 'jp', 'url' => URL::to('/jp/faq')],
                ['language' => 'ru', 'url' => URL::to('/ru/faq')],
                ['language' => 'th', 'url' => URL::to('/th/faq')],
                ['language' => 'vn', 'url' => URL::to('/vn/faq')],
            ];
            $translationsBonuses = [
                ['language' => 'de', 'url' => URL::to('/de/bonuses')],
                ['language' => 'en', 'url' => URL::to('/en/bonuses')],
                ['language' => 'fr', 'url' => URL::to('/fr/bonuses')],
                ['language' => 'it', 'url' => URL::to('/it/bonuses')],
                ['language' => 'jp', 'url' => URL::to('/jp/bonuses')],
                ['language' => 'ru', 'url' => URL::to('/ru/bonuses')],
                ['language' => 'th', 'url' => URL::to('/th/bonuses')],
                ['language' => 'vn', 'url' => URL::to('/vn/bonuses')],
            ];
            $translationsPasswordForgot = [
                ['language' => 'de', 'url' => URL::to('/de/password/forgot')],
                ['language' => 'en', 'url' => URL::to('/en/password/forgot')],
                ['language' => 'fr', 'url' => URL::to('/fr/password/forgot')],
                ['language' => 'it', 'url' => URL::to('/it/password/forgot')],
                ['language' => 'jp', 'url' => URL::to('/jp/password/forgot')],
                ['language' => 'ru', 'url' => URL::to('/ru/password/forgot')],
                ['language' => 'th', 'url' => URL::to('/th/password/forgot')],
                ['language' => 'vn', 'url' => URL::to('/vn/password/forgot')],
            ];
            $translationsPasswordEmail = [
                ['language' => 'de', 'url' => URL::to('/de/password/email')],
                ['language' => 'en', 'url' => URL::to('/en/password/email')],
                ['language' => 'fr', 'url' => URL::to('/fr/password/email')],
                ['language' => 'it', 'url' => URL::to('/it/password/email')],
                ['language' => 'jp', 'url' => URL::to('/jp/password/email')],
                ['language' => 'ru', 'url' => URL::to('/ru/password/email')],
                ['language' => 'th', 'url' => URL::to('/th/password/email')],
                ['language' => 'vn', 'url' => URL::to('/vn/password/email')],
            ];

            $getCategories = DB::table('games_types')->where('active',1)->orderBy('id', 'desc')->get();

            foreach ($getCategories as $category) {
                $category_name = $category->default_name;
                $category_name = preg_replace('/\s/','-',$category_name);
                $updated_at = $category->updated_at;

                $translationsCategory = [
                    ['language' => 'de', 'url' => URL::to('/de/games/'.$category_name)],
                    ['language' => 'en', 'url' => URL::to('/en/games/'.$category_name)],
                    ['language' => 'fr', 'url' => URL::to('/fr/games/'.$category_name)],
                    ['language' => 'it', 'url' => URL::to('/it/games/'.$category_name)],
                    ['language' => 'jp', 'url' => URL::to('/jp/games/'.$category_name)],
                    ['language' => 'ru', 'url' => URL::to('/ru/games/'.$category_name)],
                    ['language' => 'th', 'url' => URL::to('/th/games/'.$category_name)],
                    ['language' => 'vn', 'url' => URL::to('/vn/games/'.$category_name)],
                ];
                $sitemap->add(URL::to('/en/games/'.$category_name), $updated_at, '0.7', 'daily', [], null, $translationsCategory);
            }

            $getgames = DB::table('games_list')->orderBy('system_id', 'desc')->get();

            foreach ($getgames as $game) {
                $game_id = $game->system_id;
                $provider_id = $game->provider_id;
                $updated_at = $game->updated_at;
                $sitemap->add(URL::to('/integratedGameLink/provider/'.$provider_id.'/game/'.$game_id), $updated_at, '0.5', 'weekly');
            }
            $sitemap->add(URL::to('/en'), date('Y-m-dTH:i:sP', time()), '1', 'always', [], null, $translations);
            $sitemap->add(URL::to('/en/games'), date('Y-m-dTH:i:sP', time()), '0.7', 'daily', [], null, $translationsGames);
            $sitemap->add(URL::to('/en/faq'), date('Y-m-dTH:i:sP', time()),'0.3', 'monthly', [], null, $translationsFaq);
            $sitemap->add(URL::to('/en/bonuses'), date('Y-m-dTH:i:sP', time()), '0.3', 'monthly', [], null, $translationsBonuses);
            $sitemap->add(URL::to('/en/password/forgot'), date('Y-m-dTH:i:sP', time()), '0.3', 'monthly', [], null, $translationsPasswordForgot);
            $sitemap->add(URL::to('/en/password/email'), date('Y-m-dTH:i:sP', time()), '0.3', 'monthly', [], null, $translationsPasswordEmail);
        }

         $sitemap->store('xml', 'sitemap');
    }
}
