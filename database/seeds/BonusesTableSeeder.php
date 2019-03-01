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
                'public' => 1,
                'name' => '50 free spins!',
                'descr' => 'Available only registration',
                'rating' => 4,
                'play_factor' => 33,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'min_sum' => 3,
                'max_sum' => 0,
                'procent' => 100,
                'play_factor' => 33,
                'public' => 1,
                'name' => '200% bonus / First Deposit',
                'descr' => '200% bonus / First Deposit',
                'rating' => 3,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 3,
                'min_sum' => 3,
                'max_sum' => 0,
                'procent' => 150,
                'play_factor' => 33,
                'public' => 1,
                'name' => '150% bonus / Second Deposit',
                'descr' => '150% bonus / Second Deposit',
                'rating' => 2,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 4,
                'min_sum' => 3,
                'max_sum' => 0,
                'procent' => 100,
                'play_factor' => 33,
                'public' => 1,
                'name' => '100% bonus / Third Deposit',
                'descr' => '100% bonus / Third Deposit',
                'rating' => 1,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }
}
