<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusesTableSeeder extends Seeder
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
        DB::table('bonuses')->truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            DB::table('bonuses')->insert($item);
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
                'min_sum' => 1,
                'max_sum' => 500,
                'procent' => 100,
                'play_factor' => 25,
                'public' => 1,
                'name' => '200% bonus / First Deposit',
                'descr' => 'Minimum sum 300 mBTC, maximum 600 mBTC',
                'rating' => 2,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'min_sum' => 1000,
                'max_sum' => 2000,
                'procent' => 150,
                'play_factor' => 35,
                'public' => 1,
                'name' => '150% bonus / Second Deposit',
                'descr' => 'Minimum sum 150 mBTC, Maximum 300 mBTC',
                'rating' => 1,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'public' => 1,
                'name' => '50 free spins!',
                'descr' => 'Available only before deposit',
                'rating' => 3,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }

}
