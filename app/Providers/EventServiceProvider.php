<?php

namespace App\Providers;

use App\Events\TransactionSaved;
use Illuminate\Support\Facades\Event;
use App\Providers\Intercom\IntercomEventHandler;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $subscriber = new IntercomEventHandler();
        Event::subscribe($subscriber);
    }
}
