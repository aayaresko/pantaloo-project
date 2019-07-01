<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'locale' => 'ru',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'privacy_policy_cookie',
                'text' => 'Мы используем файлы cookie, чтобы сделать ваше пребывание на сайте CasinoBit.io более комфортным. Продолжая пользоваться сайтом, вы автоматически принимаете нашу .',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'privacy_policy_cookie',
                'text' => 'We use cookie to make CasinoBit.io more comfortable for you. By continuing to use the website, you automatically agree with our .',
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
                'locale' => 'ru',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'privacy_policy_link',
                'text' => 'Политику конфиденциальности',
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
                'locale' => 'ru',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'cookie_ok',
                'text' => 'Да',
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
