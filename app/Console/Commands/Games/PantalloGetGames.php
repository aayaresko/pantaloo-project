<?php

namespace App\Console\Commands\Games;

use DB;
use Log;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesCategory;
use App\Models\GamesListExtra;
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
        $this->info("Start ...");
        Log::info('PantalloGetGames START');
        $unwantedCharacter = '_';
        DB::beginTransaction();
        try {
            $pantalloGames = new PantalloGames;
            $allGames = $pantalloGames->getGameList([], true);
            $providerId = 1;
            //get list categories
            $types = GamesType::all()->keyBy('code');
            $categories = GamesCategory::all()->keyBy('code');

            //don't active games
            if (count($allGames->response) > 0) {
                GamesList::where('provider_id', $providerId)->update([
                    'active' => 0
                ]);
            }

            //get list types
            foreach ($allGames->response as $game) {
                //use trim and
                $gameId = $game->id;
                if ($game->category !== '') {
                    $gameCategory = $game->category;
                } else {
                    if ($game->subcategory !== '') {
                        $subcategory = $game->subcategory;
                        if ($subcategory[0] === $unwantedCharacter) {
                            $subcategory = ltrim($subcategory, $unwantedCharacter);
                        }
                        $gameCategory = $subcategory;
                    } else {
                        $gameCategory = 'empty';
                    }
                }

                $gameCategory = trim($gameCategory);
                $gameType = trim($game->type);

                if (!isset($categories[$gameCategory])) {
                    GamesCategory::create([
                        'code' => $gameCategory,
                        'name' => $gameCategory
                    ]);
                    $categories = GamesCategory::all()->keyBy('code');
                }

                if (!isset($types[$gameType])) {
                    GamesType::create([
                        'code' => $gameType,
                        'name' => $gameType
                    ]);
                    $types = GamesType::all()->keyBy('code');
                }

                $currentGame = GamesList::select(['id'])->where('system_id', $gameId)->first();
                if (is_null($currentGame)) {
                    $gameDate = [
                        'provider_id' => $providerId,
                        'system_id' => $gameId,
                        'name' => $game->name,
                        'our_name' => $game->name,
                        'type_id' => $types[$gameType]->id,
                        'category_id' => $categories[$gameCategory]->id,
                        'details' => $game->details,
                        'mobile' => (int)$game->mobile,
                        'image' => $game->image,
                        'image_preview' => $game->image_preview,
                        'image_filled' => $game->image_filled,
                        'image_background' => $game->image_background,
                        'rating' => 1,
                        'active' => 1
                    ];
                    $game = GamesList::create($gameDate);
                    $gameDateExtra = [
                        'game_id' => $game->id,
                        'type_id' => $types[$gameType]->id,
                        'category_id' => $categories[$gameCategory]->id,
                    ];
                    GamesListExtra::create($gameDateExtra);
                } else {
                    dd(222);
                    $gameDate = [
                        'details' => $game->details,
                        'mobile' => (int)$game->mobile,
                        'image' => $game->image,
                        'image_preview' => $game->image_preview,
                        'image_filled' => $game->image_filled,
                        'image_background' => $game->image_background,
                        'active' => 1
                    ];
                    $game = GamesList::updateOrCreate(['system_id' => $gameId], $gameDate);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
        DB::commit();
        //get games and load or update
        Log::info('PantalloGetGames END');
        $this->info("Games loaded or updated.");
    }
}
