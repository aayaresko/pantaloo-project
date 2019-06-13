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
        $this->call(GamesCategorySeeder::class);
        $this->call(GamesTypesSeeder::class);
        $this->call(GamesTagSeeder::class);
        $this->call(GamesSettingSeeder::class);
        $this->call(BonusesTableSeeder::class);
        $this->call(LanguagesSeeder::class);
    }
}
