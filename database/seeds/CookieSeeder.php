<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// TODO Lior - what CookieSeeder is used for?
class CookieSeeder extends Seeder
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
                'item' => 'privacy_policy_cookie',
                'text' => 'We use cookies to make Casinobit.io more comfortable for you. By continuing to use the website, you automatically agree with our ',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'privacy_policy_link',
                'text' => 'Privacy Policy',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'cookie_ok',
                'text' => 'Ok',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'cookie_and',
                'text' => 'and',
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
