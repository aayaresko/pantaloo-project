<?php

use App\Models\GamesCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* set params */
        $current_params = $this->getParams();
        /* clear table */
        /* fill table */
        $this->insertToDB($current_params);
    }

    /**
     * @param $params
     */
    protected function insertToDB($params)
    {
        /* for foreign key */
        DB::statement('SET foreign_key_checks=0');
        /* start clear */
        GamesCategory::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            GamesCategory::insert($item);
        }
        /* end main act */
        DB::statement('SET foreign_key_checks=1');
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        $currentDate = new DateTime();

        return [];
    }
}
