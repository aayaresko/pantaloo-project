<?php


namespace App\Providers\Intercom;


use App\Bonus;
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
use App\Jobs\IntercomCreateUpdateUser;
use App\Jobs\IntercomSendEvent;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Intercom\IntercomClient;

class IntercomEventHandler
{
    public function onOpenBonus(OpenBonusEvent $event)
    {
        $name = "open '{$event->bonusName}'";
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onCloseBonus(CloseBonusEvent $event)
    {
        $name = "close '{$event->bonusName}'";
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onDeposit(DepositEvent $event){
        $name = "внесены средства";
        $this->sendEvent($event->user->email, $name, [
            'value' => $event->value
        ]);
    }

    public function onBonusDeposit(BonusDepositEvent $event){
        $name = "начислены бонусы";
        $this->sendEvent($event->user->email, $name, [
            'value' => $event->value
        ]);
    }

    public function onWagerDone(WagerDoneEvent $event){
        $name = "wager done";
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onDepositWagerDone(DepositWagerDoneEvent $event){
        $name = "deposit wager done";
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onBonusGame(BonusGameEvent $event){
        $name = "50 free spin in {$event->gameName}";
        $this->sendEvent($event->user->email, $name, []);
    }

    // onWithdrawalRequested
    // onWithdrawalApproved
    // onWithdrawalFrozen
    // onAccountStatus

    public function onWithdrawalRequested(WithdrawalRequestedEvent $event){
        $name = "withdrawal requested";
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onWithdrawalApproved(WithdrawalApprovedEvent $event){
        $name = "withdrawal approved";
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onWithdrawalFrozen(WithdrawalFrozenEvent $event){
        $name = "withdrawal frozen";
        $this->sendEvent($event->user->email, $name, [
            'comment' => $event->comment
        ]);
    }

    public function onAccountStatus(AccountStatusEvent $event){
        $name = "account status change";
        $this->sendEvent($event->user->email, $name, [
            'old_status' => $event->old_status,
            'new_status' => $event->new_status,
        ]);
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('App\Events\OpenBonusEvent', 'App\Providers\Intercom\IntercomEventHandler@onOpenBonus');
        $events->listen('App\Events\CloseBonusEvent', 'App\Providers\Intercom\IntercomEventHandler@onCloseBonus');
        $events->listen('App\Events\DepositEvent', 'App\Providers\Intercom\IntercomEventHandler@onDeposit');
        $events->listen('App\Events\BonusDepositEvent', 'App\Providers\Intercom\IntercomEventHandler@onBonusDeposit');
        $events->listen('App\Events\WagerDoneEvent', 'App\Providers\Intercom\IntercomEventHandler@onWagerDone');
        $events->listen('App\Events\DepositWagerDoneEvent', 'App\Providers\Intercom\IntercomEventHandler@onDepositWagerDone');
        $events->listen('App\Events\BonusGameEvent', 'App\Providers\Intercom\IntercomEventHandler@onBonusGame');

        $events->listen('App\Events\WithdrawalRequestedEvent', 'App\Providers\Intercom\IntercomEventHandler@onWithdrawalRequested');
        $events->listen('App\Events\WithdrawalApprovedEvent', 'App\Providers\Intercom\IntercomEventHandler@onWithdrawalApproved');
        $events->listen('App\Events\WithdrawalFrozenEvent', 'App\Providers\Intercom\IntercomEventHandler@onWithdrawalFrozen');
        $events->listen('App\Events\AccountStatusEvent', 'App\Providers\Intercom\IntercomEventHandler@onAccountStatus');

    }



    private function sendEvent($email, $name, $data=[])
    {


        dispatch(new IntercomCreateUpdateUser(User::where('email', $email)->first()   ));

        $timestamp = time();
        $dt = Carbon::createFromTimestamp($timestamp);

        Carbon::setToStringFormat('d-m-y H:i');

        $data = [
            'created_at' => $timestamp,
            'email' => $email,
            'event_name' => $name,
            "metadata" => $data
        ];

        Log::info('Add job send event "' . $data['event_name'] . '"');

        dispatch(new IntercomSendEvent($data));
    }


}