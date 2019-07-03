<?php

namespace App\Events;

use App\Transaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransactionSaved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $userId;
    public $balance, $real_balance, $bonus_balance, $currency;

    public function __construct(Transaction $transaction)
    {
        $userId = $transaction->user_id;

        $user = User::find($userId);

        if ($user) {
            $this->currency = ' m' . strtoupper($user->currency->title);
            $this->balance = $user->getBalance();
            $this->real_balance = $user->getRealBalance();
            $this->bonus_balance = $user->getBonusBalance();

            //$this->real_balance = rand(5000, 50000) / 100;
            //$this->bonus_balance = rand(5000, 50000) / 100;
            //$this->balance = $this->real_balance + $this->bonus_balance;
        }

        $this->userId = $userId;

    }


    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'transaction';
    }
}
