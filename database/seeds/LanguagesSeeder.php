<?php

use Helpers\GeneralHelper;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        DB::table('translator_languages')->truncate();
        DB::statement("SET foreign_key_checks=1");

        $date = new \DateTime();
        $getLanguages = GeneralHelper::getListLanguage('files');
        $insertDate = [];
        foreach ($getLanguages as $getLanguage) {
            array_push($insertDate,
                [
                    'locale' => $getLanguage,
                    'name' => $getLanguage,
                    'created_at' => $date,
                    'updated_at' => $date,
                    ]);
        }

        DB::table('translator_languages')->insert($insertDate);
    }
}
