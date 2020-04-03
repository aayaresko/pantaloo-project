<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LanguagesSeeder::class);
        $this->call(TranslationsKey::class);
        $this->call(GamesCategorySeeder::class);
        $this->call(GamesTypesSeeder::class);
        $this->call(GamesSettingSeeder::class);
        $this->call(BonusesTableSeeder::class);
        $this->call(IntercomSeeder::class);
        $this->call(IntercomCountrySeeder::class);
        $this->call(MetaTagSeeder::class);
        $this->call(CookieSeeder::class);
        $this->call(ContactUsSeeder::class);
        $this->call(AddtranslationKey::class);
        $this->call(AccountLangTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(LoginTransTableSeeder::class);
        $this->call(PrivacyPolicySeeder::class);
        $this->call(RestrictionTablesSeeder::class);
        $this->call(DepositWithoutTransactionSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(SlotsSeeder::class);
    }
}
