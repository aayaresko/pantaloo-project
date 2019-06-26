<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivacyPolicySeeder extends Seeder
{

    /**
     * @throws Exception
     */
    public function run()
    {
        $currentDate = new DateTime();

        $data = [
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'privacy_policy',
                'text' => 'privacy_policy',
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
