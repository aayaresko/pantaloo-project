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
                'code' => 'empty',
                'name' => 'empty',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'code' => 'net-ent',
                'name' => 'net-ent',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'code' => 'Habanero',
                'name' => 'Habanero',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 4,
                'code' => 'laifacai',
                'name' => 'laifacai',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 5,
                'code' => 'evolution',
                'name' => 'evolution',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 6,
                'code' => 'fugaso',
                'name' => 'fugaso',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 7,
                'code' => 'ezugi',
                'name' => 'ezugi',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 8,
                'code' => 'Endorphina',
                'name' => 'Endorphina',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 9,
                'code' => 'Evoplay',
                'name' => 'Evoplay',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 10,
                'code' => 'microgaming',
                'name' => 'microgaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 11,
                'code' => 'xplosive',
                'name' => 'xplosive',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 12,
                'code' => 'gameart',
                'name' => 'gameart',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 13,
                'code' => 'SlotMotion',
                'name' => 'SlotMotion',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 14,
                'code' => 'reeltime',
                'name' => 'reeltime',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 15,
                'code' => 'lucky',
                'name' => 'lucky',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 16,
                'code' => 'PariPlay',
                'name' => 'PariPlay',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 17,
                'code' => '1X2 Gaming',
                'name' => '1X2 Gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 18,
                'code' => 'TomHorn',
                'name' => 'TomHorn',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 19,
                'code' => 'betsoft',
                'name' => 'betsoft',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 20,
                'code' => 'Spinomenal',
                'name' => 'Spinomenal',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 21,
                'code' => 'pragmatic play',
                'name' => 'pragmatic play',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 22,
                'code' => 'playngo',
                'name' => 'playngo',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 23,
                'code' => 'oryx',
                'name' => 'oryx',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 24,
                'code' => 'gamomat',
                'name' => 'gamomat',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 25,
                'code' => 'DelaSport',
                'name' => 'DelaSport',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 26,
                'code' => 'isoftbet',
                'name' => 'isoftbet',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 27,
                'code' => 'Iron Dog',
                'name' => 'Iron Dog',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 28,
                'code' => 'quickspin',
                'name' => 'quickspin',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 29,
                'code' => 'jftw',
                'name' => 'jftw',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 30,
                'code' => 'foxium',
                'name' => 'foxium',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 31,
                'code' => 'Booming games',
                'name' => 'Booming games',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 32,
                'code' => 'Push Gaming',
                'name' => 'Push Gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 33,
                'code' => 'Relax Gaming',
                'name' => 'Relax Gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 34,
                'code' => 'Felt Gaming',
                'name' => 'Felt Gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 35,
                'code' => 'YggDrasil',
                'name' => 'YggDrasil',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 36,
                'code' => 'Nolimit',
                'name' => 'Nolimit',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 37,
                'code' => 'Spadegaming',
                'name' => 'Spadegaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 38,
                'code' => 'Ganapati',
                'name' => 'Ganapati',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 39,
                'code' => 'Leap',
                'name' => 'Leap',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }
}
