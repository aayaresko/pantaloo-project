<?php

namespace App\Console\Commands\Games;

use App\Modules\PantalloGames;
use Illuminate\Console\Command;

class PantalloGetGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:PantalloGetGames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get games';

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
        $pantalloGames = new PantalloGames;
        $params = [];
        $allGames = $pantalloGames->getGameList($params);
        foreach ($allGames->response as $game) {

        }
        dd($allGames);
        //ask


        //get games and load or update
        $this->info("All users get new addresses");
    }
}
