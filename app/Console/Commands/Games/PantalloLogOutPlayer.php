<?php

namespace App\Console\Commands\Games;

use Log;
use App\User;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesCategory;
use App\Modules\PantalloGames;
use Illuminate\Console\Command;
use App\Modules\Games\PantalloGamesSystem;
use App\Models\Pantallo\GamesPantalloSession;

class PantalloLogOutPlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:PantalloLogOutPlayer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LogOutPlayer';

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
        //notice
        //check  this code
        //to do this select fileds
        //check last_activities field
        $this->info("Start ...");
        Log::info('PantalloLogOutPlayer START');
        $lifetime = 120;
        $chunk = 20;
        try {
            $configIntegratedGames = config('integratedGames.common');
            $statusConfig = $configIntegratedGames['statusSession'];
            $date = new \DateTime();
            $date->setTimestamp(time() + 60 * $lifetime);
            $pantalloGamesSystem = new PantalloGamesSystem();
            User::leftJoin('games_pantallo_session', 'users.id', '=', 'games_pantallo_session.user_id')
                ->where([
                    ['users.last_activity', '>', $date],
                    ['games_pantallo_session.status', '=', 0]
                ])->chunk($chunk, function ($users) use ($statusConfig, $pantalloGamesSystem) {
                    //to make logout for players
                    foreach ($users as $user) {
                        //make logout if success
                        $response = $pantalloGamesSystem->logoutPlayer($user);
                        Log::info($response);
                    }
                });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        //get games and load or update
        Log::info('PantalloLogOutPlayer END');
        $this->info("Users have been logged in.");
    }
}
