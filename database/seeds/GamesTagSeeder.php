<?php

use App\Models\GamesTag;
use Illuminate\Database\Seeder;

class GamesTagSeeder extends Seeder
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
        GamesTag::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            GamesTag::insert($item);
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

        return [
            [
                'id' => 1,
                'code' => 'tag1',
                'name' => 'Tag1',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'id' => 2,
                'code' => 'tag2',
                'name' => 'Tag2',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];
    }
}
