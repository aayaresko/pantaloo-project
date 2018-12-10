<?php

use App\Models\GamesListSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesSettingSeeder extends Seeder
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
        DB::statement("SET foreign_key_checks=0");
        /* start clear */
        GamesListSettings::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            GamesListSettings::insert($item);
        }
        /* end main act */
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * @return array
     */
    protected function getParams()
    {;
        $currentDate = new DateTime();
        return [
            [
                'id' => 1,
                'code' => 'games',
                'name' => 'games',
                'value' => 1,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'code' => 'categories',
                'name' => 'categories',
                'value' => 1,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'code' => 'types',
                'name' => 'types',
                'value' => 1,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }
}
