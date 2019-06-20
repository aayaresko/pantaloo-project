<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginTransTableSeeder extends Seeder
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
                'item' => 'not_allowed_title',
                'text' => 'It\'s not about you...',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'not_allowed_subtitle',
                'text' => 'CasinoBit.io isn\'t avaible in your country.We\'re so sorry!If you think that we were mistaken with your location, please contact us with support',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'affiliate_info',
                'text' => 'You can became an affiliate thought. Just send an email to affiliates@casinobit.io',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'dont_have_account',
                'text' => 'Don’t have an account?',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'have_account',
                'text' => 'Have an account?',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'create_account',
                'text' => 'Create',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'enter_account',
                'text' => 'Sign in',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'input_title',
                'text' => 'Please, fill out this field',
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
