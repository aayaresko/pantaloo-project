<?php

namespace App\Console;

use App\Console\Commands\UpdateTransactions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\BitcoinGetTransactions::class,
        Commands\Games\PantalloLogOutPlayer::class,
        Commands\Games\PantalloGetGames::class,
        Commands\UpdateTransactions::class,
        Commands\BitcoinNewAddr::class,
        Commands\BitcoinResend::class,
        Commands\SlotChecker::class,
        Commands\BitcoinSend::class,
        Commands\BonusTest::class,
        Commands\BonusJobs::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();

        //$schedule->command('bitcoin:getTransactions')->cron('* * * * * *');

        //get games pantallo every our
        //$schedule->command('games:PantalloGetGames')->hourly();
        //close session for games
        //$schedule->command('games:PantalloLogOutPlayer')->everyTenMinutes();
    }
}
