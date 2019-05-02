<?php

namespace App\Console\Commands;

use App\Providers\Intercom\Intercom;

use App\Providers\Intercom\IntercomEventsResolver;
use App\User;
use Illuminate\Console\Command;

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
        $intercom = new Intercom();
        //$intercom->create_or_update_user(new User());
        $intercom->send_event(new User(), IntercomEventsResolver::OPEN_BONUS, time());
    }
}
