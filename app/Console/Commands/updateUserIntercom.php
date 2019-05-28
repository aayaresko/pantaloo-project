<?php

namespace App\Console\Commands;

use App\Events\AccountStatusEvent;
use App\Events\BonusDepositEvent;
use App\Events\BonusGameEvent;
use App\Events\CloseBonusEvent;
use App\Events\DepositEvent;
use App\Events\DepositWagerDoneEvent;
use App\Events\OpenBonusEvent;
use App\Events\WagerDoneEvent;
use App\Events\WithdrawalApprovedEvent;
use App\Events\WithdrawalFrozenEvent;
use App\Events\WithdrawalRequestedEvent;
use App\Providers\Intercom\Intercom;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class updateUserIntercom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intercom:user';

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

        $path = getcwd() . '/resources/lang';
        $langs = array_diff(scandir($path), ['.', '..']);
        foreach ($langs as $lang) {
            $cpath = $path . DIRECTORY_SEPARATOR . $lang;
            //$files = array_diff(scandir($cpath), ['.', '..']);
            $files = ['casino.php'];
            foreach ($files as $file){
                $data = File::getRequire($cpath . DIRECTORY_SEPARATOR . $file);
                $datafile = preg_replace("/\.php$/", ".data", 'lang' .DIRECTORY_SEPARATOR . $lang.DIRECTORY_SEPARATOR.$file);
                Storage::put($datafile, serialize($data));
            }
        }
//        $user = User::findOrFail(146);
////
////        event(new AccountStatusEvent($user, 'old_status', 'new_status'));
////        event(new BonusDepositEvent($user, 100500));
////        event(new BonusGameEvent($user, 'game name'));
////        event(new CloseBonusEvent($user, 'bonus name'));
////        event(new DepositEvent($user, 1000));
////        event(new DepositWagerDoneEvent($user));
////        event(new OpenBonusEvent($user, 'bonus name'));
////        event(new WagerDoneEvent($user));
////        event(new WithdrawalApprovedEvent($user));
////        event(new WithdrawalFrozenEvent($user, 'comment'));
////        event(new WithdrawalRequestedEvent($user));


    }
}
