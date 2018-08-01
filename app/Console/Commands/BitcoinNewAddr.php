<?php

namespace App\Console\Commands;

use App\Bitcoin\Service;
use App\User;
use Illuminate\Console\Command;

class BitcoinNewAddr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:newAddr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genretes new addreses for all users';

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
        exit;

        $service = new Service();

        $users = User::all();

        foreach ($users as $user)
        {
            $address = $service->getNewAddress('common');

            $user->bitcoin_address = $address;

            $user->save();
        }

        $this->info("All users get new addresses");
    }
}
