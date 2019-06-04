<?php

namespace App\Providers\Intercom;

use App\User;
use App\Bonus;
use Carbon\Carbon;
use App\Events\DepositEvent;
use Intercom\IntercomClient;
use App\Events\BonusGameEvent;
use App\Events\OpenBonusEvent;
use App\Events\WagerDoneEvent;
use App\Events\CloseBonusEvent;
use App\Jobs\IntercomSendEvent;
use App\Events\BonusCancelEvent;
use App\Events\BonusDepositEvent;
use App\Events\AccountStatusEvent;
use Illuminate\Support\Facades\Log;
use App\Events\DepositWagerDoneEvent;
use App\Events\WithdrawalFrozenEvent;
use App\Jobs\IntercomCreateUpdateUser;
use App\Events\WithdrawalApprovedEvent;
use App\Events\WithdrawalRequestedEvent;

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

    public function onDeposit(DepositEvent $event)
    {
        $name = 'внесены средства';
        $this->sendEvent($event->user->email, $name, [
            'value' => $event->value,
        ]);
    }

    public function onBonusDeposit(BonusDepositEvent $event)
    {
        $name = 'начислены бонусы';
        $this->sendEvent($event->user->email, $name, [
            'value' => $event->value,
        ]);
    }

    public function onWagerDone(WagerDoneEvent $event)
    {
        $name = 'wager done';
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onDepositWagerDone(DepositWagerDoneEvent $event)
    {
        $name = 'deposit wager done';
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onBonusGame(BonusGameEvent $event)
    {
        $name = "50 free spin in {$event->gameName}";
        $this->sendEvent($event->user->email, $name, []);
    }

    // onWithdrawalRequested
    // onWithdrawalApproved
    // onWithdrawalFrozen
    // onAccountStatus

    public function onWithdrawalRequested(WithdrawalRequestedEvent $event)
    {
        $name = 'withdrawal requested';
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onWithdrawalApproved(WithdrawalApprovedEvent $event)
    {
        $name = 'withdrawal approved';
        $this->sendEvent($event->user->email, $name, []);
    }

    public function onWithdrawalFrozen(WithdrawalFrozenEvent $event)
    {
        $name = 'withdrawal frozen';
        $this->sendEvent($event->user->email, $name, [
            'comment' => $event->comment,
        ]);
    }

    public function onAccountStatus(AccountStatusEvent $event)
    {
        $name = 'account status change';
        $this->sendEvent($event->user->email, $name, [
            'old_status' => $event->old_status,
            'new_status' => $event->new_status,
        ]);
    }

    public function onBonusCancel(BonusCancelEvent $event)
    {
        $name = "bonus cancel '{$event->bonusName}'";
        $this->sendEvent($event->user->email, $name, []);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen(\App\Events\OpenBonusEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onOpenBonus');
        $events->listen(\App\Events\CloseBonusEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onCloseBonus');
        $events->listen(\App\Events\DepositEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onDeposit');
        $events->listen(\App\Events\BonusDepositEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onBonusDeposit');
        $events->listen(\App\Events\WagerDoneEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onWagerDone');
        $events->listen(\App\Events\DepositWagerDoneEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onDepositWagerDone');
        $events->listen(\App\Events\BonusGameEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onBonusGame');

        $events->listen(\App\Events\WithdrawalRequestedEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onWithdrawalRequested');
        $events->listen(\App\Events\WithdrawalApprovedEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onWithdrawalApproved');
        $events->listen(\App\Events\WithdrawalFrozenEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onWithdrawalFrozen');
        $events->listen(\App\Events\AccountStatusEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onAccountStatus');

        $events->listen(\App\Events\BonusCancelEvent::class, 'App\Providers\Intercom\IntercomEventHandler@onBonusCancel');
    }

    private function sendEvent($email, $name, $metadata = [])
    {
        dispatch(new IntercomCreateUpdateUser(User::where('email', $email)->first()));

        $timestamp = time();
        $dt = Carbon::createFromTimestamp($timestamp);

        Carbon::setToStringFormat('d-m-y H:i');

        $data = [
            'created_at' => $timestamp,
            'email' => $email,
            'event_name' => $name,
        ];

        if ($metadata) {
            $data['metadata'] = $metadata;
        }

        //Log::info('Add job send event "' . $data['event_name'] . '"');

        dispatch(new IntercomSendEvent($data));
    }
}
