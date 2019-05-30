<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Helpers\GeneralHelper;


class Translate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:addkey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new key for translateion array';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newKey = $this->ask('Type new key and press enter');

        $langs= GeneralHelper::getListLanguage();

        foreach ($langs as $lang) {
            $files = ['casino.php'];
            foreach ($files as $file) {
                $datafile = preg_replace("/\.php$/", ".data", 'lang' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $file);
                $data = unserialize(Storage::get($datafile));
                $data[$newKey] = isset($data[$newKey]) ? $data[$newKey] : $newKey;
                Storage::put($datafile, serialize($data));
            }
        }
    }
}
