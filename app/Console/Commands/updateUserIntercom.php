<?php

namespace App\Console\Commands;

use App\User;
use App\Events\DepositEvent;
use App\Events\BonusGameEvent;
use App\Events\OpenBonusEvent;
use App\Events\WagerDoneEvent;
use App\Events\CloseBonusEvent;
use Illuminate\Console\Command;
use App\Events\BonusDepositEvent;
use App\Events\AccountStatusEvent;
use App\Providers\Intercom\Intercom;
use Illuminate\Support\Facades\File;
use App\Events\DepositWagerDoneEvent;
use App\Events\WithdrawalFrozenEvent;
use App\Events\WithdrawalApprovedEvent;
use Illuminate\Support\Facades\Storage;
use App\Events\WithdrawalRequestedEvent;

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
        $user = User::findOrFail(146);

        event(new AccountStatusEvent($user, 'old_status', 'new_status'));
        event(new BonusDepositEvent($user, 100500));
        event(new BonusGameEvent($user, 'game name'));
        event(new CloseBonusEvent($user, 'bonus name'));
        event(new DepositEvent($user, 1000));
        event(new DepositWagerDoneEvent($user));
        event(new OpenBonusEvent($user, 'bonus name'));
        event(new WagerDoneEvent($user));
        event(new WithdrawalApprovedEvent($user));
        event(new WithdrawalFrozenEvent($user, 'comment'));
        event(new WithdrawalRequestedEvent($user));
    }
}
