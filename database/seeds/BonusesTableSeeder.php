<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusesTableSeeder extends Seeder
{
    /**
     * @throws Exception
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
        DB::table('bonuses')->truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            DB::table('bonuses')->insert($item);
        }
        /* end main act */
        DB::statement('SET foreign_key_checks=1');
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getParams()
    {
        $currentDate = new DateTime();

        return [
            [
                'id' => 1,
                'public' => 0,
                'name' => '50 free spins!',
                'descr' => 'Available only after registration',
                'rating' => 4,
                'extra' => json_encode([
                    'mainPicture' => '/assets/images/bonuses/promo-1-bg-min.jpg',
                    'additionalPicture' => '/assets/images/bonuses/bonus-blok-1-box.jpg'
                ]),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'public' => 1,
                'name' => '110% bonus / First Deposit',
                'descr' => '110% bonus / First Deposit',
                'rating' => 3,
                'extra' => json_encode([
                    'mainPicture' => '/assets/images/bonuses/promo-2-bg-min.jpg',
                    'additionalPicture' => '/assets/images/bonuses/bonus-blok-2-box.jpg'
                ]),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'public' => 1,
                'name' => '80% bonus / Second Deposit',
                'descr' => '80% bonus / Second Deposit',
                'rating' => 2,
                'extra' => json_encode([
                    'mainPicture' => '/assets/images/bonuses/promo-3-bg-min.jpg',
                    'additionalPicture' => '/assets/images/bonuses/bonus-blok-3-box.jpg'
                ]),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 4,
                'public' => 1,
                'name' => '55% bonus / Third Deposit',
                'descr' => '55% bonus / Third Deposit',
                'rating' => 1,
                'extra' => json_encode([
                    'mainPicture' => '/assets/images/bonuses/promo-4-bg-min.jpg',
                    'additionalPicture' => '/assets/images/bonuses/bonus-blok-4-box.jpg'
                ]),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }
}
