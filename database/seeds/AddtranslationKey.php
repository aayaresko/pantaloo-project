<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddtranslationKey extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = new DateTime();

        $data = [
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'type_other',
                'text' => 'new game',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];

        foreach ($data as $item) {
            DB::table('translator_translations')->where('item', $item['item'])->delete();
            DB::table('translator_translations')->insert([$item]);
        }
    }
}
