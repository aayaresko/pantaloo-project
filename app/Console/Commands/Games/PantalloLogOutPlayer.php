<?php

namespace App\Console\Commands\Games;

use App\User;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesCategory;
use App\Modules\PantalloGames;
use Illuminate\Console\Command;

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
        $this->info("Start ...");
        $lifetime = 120;
        $date = new \DateTime();
        $date->setTimestamp(time() + 30 * $lifetime);
        $users = User::leftJoin('games_pantallo_session', 'users.id', '=', 'games_pantallo_session.user_id')
            ->where([
                ['last_activity', '>', $date],
                ['status', '=', 0]
            ])->selest(['users.id as user_id'])->get();
        dd($users);
        try {
            //to make logout for players
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        //get games and load or update
        $this->info("Users have been logged in.");
    }
}
