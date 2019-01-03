<?php

use App\Models\GamesTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
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
        GamesTag::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            GamesTag::insert($item);
        }
        /* end main act */
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        $currentDate = new DateTime();
        return [
            [
                'id' => 1,
                'code' => 'slots',
                'name' => 'slots',
                'default_name' => 'slots',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'code' => 'blackjack',
                'name' => 'blackjack',
                'default_name' => 'blackjack',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'code' => 'roulette',
                'name' => 'roulette',
                'default_name' => 'roulette',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 4,
                'code' => 'baccarat',
                'name' => 'baccarat',
                'default_name' => 'baccarat',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 5,
                'code' => 'bet_on_numbers',
                'name' => 'bet on numbers',
                'default_name' => 'bet on numbers',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 6,
                'code' => 'keno',
                'name' => 'keno',
                'default_name' => 'keno',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 7,
                'code' => 'poker',
                'name' => 'poker',
                'default_name' => 'poker',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ]
        ];
    }
}
