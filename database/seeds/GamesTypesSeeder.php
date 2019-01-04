<?php

use App\Models\GamesType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTypesSeeder extends Seeder
{
    private $types;

    public function __construct()
    {
        $this->types = config('appAdditional.defaultTypes');
    }

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
        /* main act */
        foreach ($params as $item) {
            GamesType::updateOrCreate(['id' => $item['id']], $item);
        }
        /* end main act */
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        $currentDate = new DateTime();

        foreach ($this->types as &$type) {
            $type['created_at'] = $currentDate;
            $type['updated_at'] = $currentDate;
            $type['default_name'] = $type['name'];
            $type['active'] = 0;
        }

        return $this->types;
    }
}
