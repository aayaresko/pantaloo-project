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
        DB::statement("SET foreign_key_checks=0");
        /* start clear */
        GamesCategory::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            GamesCategory::insert($item);
        }
        /* end main act */
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        $currentDate =new DateTime();
        return [
            [
                'id' => 1,
                'code' => 'video-slots',
                'name' => 'video-slots',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'code' => 'livecasino',
                'name' => 'livecasino',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'code' => 'poker',
                'name' => 'poker',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 4,
                'code' => 'table-games',
                'name' => 'table-games',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 5,
                'code' => 'video-poker',
                'name' => 'video-poker',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 6,
                'code' => 'virtual-sports',
                'name' => 'virtual-sports',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 7,
                'code' => 'live-casino-table',
                'name' => 'live-casino-table',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 8,
                'code' => 'scratch-cards',
                'name' => 'scratch-cards',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 9,
                'code' => 'video-bingo',
                'name' => 'video-bingo',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 10,
                'code' => 'sportsbook',
                'name' => 'sportsbook',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 11,
                'code' => 'virtual-games',
                'name' => 'virtual-games',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }
}
