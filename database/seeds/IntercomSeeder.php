<?php

use Illuminate\Database\Seeder;

class IntercomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->getData();

        foreach ($data as $email=>$v) {

            DB::table('intercom')->updateOrInsert(
                ['email' => $email],
                ['key' => $v['key'], 'token' => $v['token'], 'appId' => $v['appId']]
            );
        }
    }

    private function getData()
    {
        return [
            'support@casinobit.io' => [
                'appId' => 'ebzyh5ul', // https://app.intercom.com/a/apps/ebzyh5ul/settings/web
                'key' => '7nbhJKiLIa4IBaguD0xlsUKb0yQyDxuY0laqDMSJ', // https://app.intercom.com/a/apps/ebzyh5ul/settings/identity-verification/web
                'token' => 'dG9rOjg1MWRlNTJjXzc3NGZfNDNlOF9hMjcxX2U4OWVjYWRkN2ZlOToxOjA=' // https://app.intercom.com/a/apps/ebzyh5ul/developer-hub/app-packages/35085/oauth
            ],
            'support.h@casinobit.io' => [
                'appId' => 'd2o9yezb',
                'key' => '40v8mqqN9EQjp_UwZlkvoWoXaEccxNI1CXnr2WyA',
                'token' => 'dG9rOmVmMzc0OTA5XzVjZGFfNGUzNF9iMGE0X2QwMzc0N2YyZTAxMToxOjA=',
            ]
        ];
    }
}
