<?php

use Illuminate\Database\Seeder;

class RestrictionTablesSeeder extends Seeder
{
    /**
     *
     * Run the database seeds.
     *
     *
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
        DB::statement("SET foreign_key_checks=0");
        /* start clear */
        DB::table('restriction_games_by_country')->truncate();
        DB::table('restriction_categories_by_country')->truncate();
        /* end clear */
        /* main act */
        foreach ($params['games'] as $item) {
            DB::table('restriction_games_by_country')->insert($item);
        }

        foreach ($params['categories'] as $item) {
            DB::table('restriction_categories_by_country')->insert($item);
        }
        /* end main act */
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getParams()
    {
        $currentDate = new DateTime();
        return [
            'games' => [
                [
                    'game_id' => 1,
                    'code_country' => 'UA',
                    'mark' => 0,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ],
                [
                    'game_id' => 2,
                    'code_country' => 'UA',
                    'mark' => 1,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]
            ],
            'categories' => [
                [
                    'category_id' => 1,
                    'code_country' => 'UA',
                    'mark' => 0,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ],
                [
                    'category_id' => 2,
                    'code_country' => 'UA',
                    'mark' => 1,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]
            ],
        ];
    }
}
