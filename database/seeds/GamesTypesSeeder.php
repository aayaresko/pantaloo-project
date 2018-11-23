<?php

use App\Models\GamesType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTypesSeeder extends Seeder
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
        GamesType::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            GamesType::insert($item);
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
                'code' => '_empty',
                'name' => 'empty',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'code' => '_netent',
                'name' => 'netent',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'code' => '_Habanero',
                'name' => 'Habanero',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 4,
                'code' => '_netent_live',
                'name' => 'netent_live',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 5,
                'code' => '_laifacai',
                'name' => 'laifacai',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 6,
                'code' => '_evolution',
                'name' => 'evolution',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 7,
                'code' => '_fugaso',
                'name' => 'fugaso',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 8,
                'code' => '_BetGames',
                'name' => 'BetGames',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 9,
                'code' => '_ezugi_live_casino',
                'name' => 'ezugi_live_casino',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 10,
                'code' => '_VirtualGeneration',
                'name' => 'VirtualGeneration',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 11,
                'code' => '_playngo',
                'name' => 'playngo',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 12,
                'code' => '_Endorphina',
                'name' => 'Endorphina',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 13,
                'code' => '_gamehub',
                'name' => 'gamehub',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 14,
                'code' => '_evoplay',
                'name' => 'evoplay',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 15,
                'code' => '_ggl_livecasino',
                'name' => 'ggl_livecasino',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 16,
                'code' => '_microgaming',
                'name' => 'microgaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 17,
                'code' => '_hollywood_tv',
                'name' => 'hollywood_tv',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 18,
                'code' => '_xplosive',
                'name' => 'xplosive',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 19,
                'code' => '_gameart',
                'name' => 'gameart',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 20,
                'code' => '_slotmotion',
                'name' => 'slotmotion',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 21,
                'code' => '_reeltime',
                'name' => 'reeltime',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 22,
                'code' => '_lucky',
                'name' => 'lucky',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 23,
                'code' => '_PariPlay',
                'name' => 'PariPlay',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 24,
                'code' => '_1X2_gaming',
                'name' => '1X2_gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 25,
                'code' => '_tom_horn',
                'name' => 'tom_horn',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 26,
                'code' => '_betsoft',
                'name' => 'betsoft',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 27,
                'code' => '_spinomenal',
                'name' => 'spinomenal',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 28,
                'code' => '_pragmaticplay',
                'name' => 'pragmaticplay',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 29,
                'code' => '_oryx',
                'name' => 'oryx',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 30,
                'code' => '_kiron',
                'name' => 'kiron',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 31,
                'code' => '_gamomat',
                'name' => 'gamomat',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 32,
                'code' => '_blueoceangaming',
                'name' => 'blueoceangaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 33,
                'code' => '_delasport',
                'name' => 'delasport',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 34,
                'code' => '_isoftbet',
                'name' => 'isoftbet',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 35,
                'code' => '_iron_dog',
                'name' => '_iron_dog',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 36,
                'code' => '_quickspin',
                'name' => 'quickspin',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 37,
                'code' => '_jftw',
                'name' => 'jftw',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 38,
                'code' => '_onetouch',
                'name' => 'onetouch',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 39,
                'code' => '_foxium',
                'name' => 'foxium',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 40,
                'code' => '_boominggames',
                'name' => 'boominggames',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 41,
                'code' => '_push_gaming',
                'name' => 'push_gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 42,
                'code' => '_relax_gaming',
                'name' => 'relax_gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 43,
                'code' => '_felt_gaming',
                'name' => '_felt_gaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 44,
                'code' => '_booming_games',
                'name' => 'booming_games',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 45,
                'code' => '_yggDrasil',
                'name' => 'yggDrasil',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 46,
                'code' => '_nolimit',
                'name' => 'nolimit',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 47,
                'code' => '_spadegaming',
                'name' => 'spadegaming',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 48,
                'code' => '_Ganapati',
                'name' => 'Ganapati',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 49,
                'code' => '_leap',
                'name' => 'leap',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],

        ];
    }
}
