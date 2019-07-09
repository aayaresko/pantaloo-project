<?php

use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
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
                'item' => 'contact_us_header',
                'text' => 'contact us',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_text',
                'text' => 'Our support team agents are glad to help you 24/7. Email us at <a href="mailto: support@casinobit.io">support@casinobit.io</a>  or use the form below.  We will contact you back within 24 hours. 
                Please, fill the information below.',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_email',
                'text' => 'Your e-mail',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_message',
                'text' => 'Message',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_file',
                'text' => 'Attach files (.jpeg .jpg or .png)',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_remove_file',
                'text' => 'Remove',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_success',
                'text' => 'Thank you, your message was successfully sent',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_max_file_count',
                'text' => 'You can upload up to 5 files.',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_not_valid_ext',
                'text' => 'Images should be in .jpeg .jpg or .png format.',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_max_file_size',
                'text' => 'The size limit for each file is 1 MB.',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_send',
                'text' => 'send',
                'unstable' => '0',
                'locked' => '0',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'locale' => 'en',
                'namespace' => '*',
                'group' => 'casino',
                'item' => 'contact_us_cant_send',
                'text' => 'Sorry, something seems to have gone wrong, please try again later',
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
