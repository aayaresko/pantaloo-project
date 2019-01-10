<?php

namespace App\Console\Commands\Games;

use DB;
use Log;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesTypeGame;
use App\Models\GamesCategory;
use App\Models\GamesListExtra;
use App\Modules\PantalloGames;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PantalloGetGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:PantalloGetGames {getImage?}';

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
        //pre init
        $this->info("Start ...");
        Log::info('PantalloGetGames START');
        $maxExecutionTime = 2000;
        ini_set('max_execution_time', $maxExecutionTime);
        $unwantedCharacter = '_';
        $providerId = 1;
        $getImage = $this->argument('getImage');

        DB::beginTransaction();
        try {
            $pantalloGames = new PantalloGames;
            $allGames = $pantalloGames->getGameList([], true);
            //get list categories
            $types = GamesType::all()->keyBy('code');
            $categories = GamesCategory::all()->keyBy('code');

            //don't active games
            if (count($allGames->response) > 0) {

                //image
                if (!is_null($getImage)) {
                    //load image
                    foreach ($allGames->response as $game) {
                        $this->saveImage($game, true);
                    }
                }

                //games
                GamesList::where('provider_id', $providerId)->update([
                    'active' => 0
                ]);

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

                    $gameCategory = strtolower($gameCategory);
                    if (!isset($categories[$gameCategory])) {
                        GamesCategory::create([
                            'code' => $gameCategory,
                            'name' => $gameCategory,
                            'default_name' => $gameCategory,
                            'active' => 1

                        ]);
                        $categories = GamesCategory::all()->keyBy('code');
                    }

                    $gameType = strtolower($gameType);
                    if (!isset($types[$gameType])) {
                        GamesType::create([
                            'code' => $gameType,
                            'name' => $gameType,
                            'default_name' => $gameType,
                            'active' => 0
                        ]);
                        $types = GamesType::all()->keyBy('code');
                    }

                    $currentGame = GamesList::select(['id'])->where('system_id', $gameId)->first();

                    $imageOur = $this->saveImage($game);

                    if (is_null($currentGame)) {
                        $gameDate = [
                            'provider_id' => $providerId,
                            'system_id' => $gameId,
                            'name' => $game->name,
                            'category_id' => $categories[$gameCategory]->id,
                            'details' => $game->details,
                            'mobile' => (int)$game->mobile,
                            'our_image' => $imageOur,
                            'image' => $game->image,
                            'image_preview' => $game->image_preview,
                            'image_filled' => $game->image_filled,
                            'image_background' => $game->image_background,
                            'rating' => 1,
                            'active' => 1
                        ];
                        $game = GamesList::create($gameDate);

                        $gameDateExtra = [
                            'name' => $game->name,
                            'image' => $imageOur,
                            'game_id' => $game->id,
                            'category_id' => $categories[$gameCategory]->id,
                        ];

                        GamesListExtra::create($gameDateExtra);

                        GamesTypeGame::create([
                            'game_id' => $game->id,
                            'type_id' => $types[$gameType]->id,
                            'extra' => 0,
                        ]);

                        GamesTypeGame::create([
                            'game_id' => $game->id,
                            'type_id' => $types[$gameType]->id,
                            'extra' => 1,
                        ]);
                    } else {
                        //to update category and types
                        $gameDate = [
                            'name' => $game->name,
                            'details' => $game->details,
                            'mobile' => (int)$game->mobile,
                            'our_image' => $imageOur,
                            'image' => $game->image,
                            'image_preview' => $game->image_preview,
                            'image_filled' => $game->image_filled,
                            'image_background' => $game->image_background,
                            'active' => 1
                        ];

                        $game = GamesList::updateOrCreate(['system_id' => $gameId], $gameDate);
                    }
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

    /**
     * @param $game
     * @param bool $action
     * @return string
     */
    private function saveImage($game, $action = false)
    {
        $configIntegratedGames = config('integratedGames.common');
        $dummyPicture = $configIntegratedGames['dummyPicture'];

        $url = $game->image_filled;
        if ($action) {
            try {
                //to do replace old picture
                $contents = file_get_contents($url);
                $name = substr($url, strrpos($url, '/') + 1);
                $pathImage = "/gamesPicturesDefault/{$name}";
                $fullPathImage = '/storage' . $pathImage;
                Storage::put('public' . $pathImage, $contents);
            } catch (\Exception $e) {
                $fullPathImage = $dummyPicture;
            }
        } else {
            $name = substr($url, strrpos($url, '/') + 1);
            $fullPathImage = "/storage/gamesPicturesDefault/{$name}";
        }
        return $fullPathImage;
    }
}
