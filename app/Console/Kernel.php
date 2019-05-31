<?php

namespace App\Console;

use App\Console\Commands\Translate;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\RemoveKeyTranslate;
use App\Console\Commands\UpdateTransactions;
use App\Console\Commands\updateUserIntercom;
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
        Commands\Optimization\ClearRawLog::class,
        Commands\BitcoinGetTransactions::class,
        Commands\Games\PantalloLogOutPlayer::class,
        Commands\Games\PantalloGetGames::class,
        Commands\UpdateTransactions::class,
        Commands\BitcoinNewAddr::class,
        Commands\BitcoinResend::class,
        Commands\SlotChecker::class,
        Commands\BitcoinSend::class,
        Commands\BonusTest::class,
        Commands\BonusJobs::class,
        updateUserIntercom::class,
        Translate::class,
        RemoveKeyTranslate::class,

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

        //add to bitcoin update every sec

        //$schedule->command('bonus:jobs')->everyMinute();

        //get games pantallo
        $schedule->command('games:PantalloGetGames')->hourly();
        //get games pantallo with image
        $schedule->command('games:PantalloGetGames getImage')->dailyAt('00:30');
        //optimizations
        //clear raw log
        $schedule->command('optimization:ClearRawLog')->dailyAt('00:40');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
