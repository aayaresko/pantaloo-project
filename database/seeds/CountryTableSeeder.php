<?php

use App\Country;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
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
        Country::truncate();
        /* end clear */
        /* main act */
        foreach ($params as $item) {
            Country::insert($item);
        }
        /* end main act */
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        $urlCountries = config('appAdditional.getCountries');
        $client = new Client([
            'verify' => false,
        ]);

        try {
            $counties = [];
            $response = $client->get($urlCountries, [
                'headers' =>[]
            ]);

            $responseJson = json_decode($response->getBody()->getContents());

            $currentDate = new DateTime();
            foreach ($responseJson as $country) {
                $counties[] = [
                    'code' => $country->alpha2Code,
                    'name' => $country->name,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ];
            }
            return $counties;
        }
        catch (\Exception $e) {
            dd($e->getMessage() . $e->getCode());
        }
    }
}
