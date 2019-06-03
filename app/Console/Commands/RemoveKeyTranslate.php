<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Helpers\GeneralHelper;

class RemoveKeyTranslate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:removekey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $removeKey = $this->ask('Type key to remove and enter');

        $langs= GeneralHelper::getListLanguage();

        foreach ($langs as $lang) {
            $files = ['casino.php'];
            foreach ($files as $file) {
                $datafile = preg_replace("/\.php$/", ".data", 'lang' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $file);
                $data = unserialize(Storage::get($datafile));
                if (($key = array_search($removeKey, $data)) !== false){
                    unset($data[$key]);
                }
                Storage::put($datafile, serialize($data));
            }
        }
    }
}
