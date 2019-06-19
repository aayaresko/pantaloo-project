<?php

use Illuminate\Database\Seeder;

class MetaTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefixs = [
            'main' => [
                '' => [
                    'title' => 'Casinobit: Bitcoin Casino - No1 Bitcoin Gambling Platform ➤ 110% Bonus',
                    'description' => 'Play & Win with Casinobit ♠ New Bitcoin Casino Online ✔ 50 Free Spins ✔ Anonymous BTC Gambling Site ✔ Welcome Bonus 110% ✔Provably Fair ✔ 1000+ Games'
                ],
            ],
            'games' => [
                '' => [
                    'title' => 'Bitcoin Games – Play Casino Games with Bitcoin Online | Casinobit',
                    'description' => 'Play Bitcoin Gambling Games at Casinobit ♠ 1000+ Online Casino Games with Bitcoin Rewards and Bonuses ✔ Provably Fair'
                ],
                'slots' => [
                    'title' => 'Bitcoin Slots – Play Online Slots with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Slot Games at Casinobit ♠ Spin and Win Online with all popular Bitcoin Slots ✔ Provably Fair'
                ],
                'roulette' => [
                    'title' => 'Bitcoin Roulette – Play Online Roulette with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Roulette at Casinobit ♠ Spin and Win Online with Bitcoin Casino Roulette ✔ Provably Fair'
                ],
                'blackjack' => [
                    'title' => 'Bitcoin Blackjack – Play Online Blackjack with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Blackjack at Casinobit ♠ Wide Selection of Online BlackjackGames to Win BTC ✔ Provably Fair'
                ],
                'poker' => [
                    'title' => 'Bitcoin Poker – Play Online Poker with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Poker at Casinobit ♠ Wide Selection of Online Poker Gamesto Win BTC ✔ Provably Fair'
                ],
                'baccarat' => [
                    'title' => 'Bitcoin Baccarat – Play Online Baccarat with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Baccarat at Casinobit ♠ Wide Selection of Online Baccarat Games to Win BTC ✔ Provably Fair'
                ],
                'dice' => [
                    'title' => 'Bitcoin Dice – Play Online Dice with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Dice at Casinobit ♠ Wide Selection of Online Dice Games to Win BTC ✔ Provably Fair'
                ],
                'bet-on-numbers' => [
                    'title' => 'Bitcoin Bet on Numbers – Play Online Bet on Numbers with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Bet on Numbers at Casinobit ♠ Wide Selection of Online Bet on Number Games to Win BTC ✔ Provably Fair'
                ],
                'keno' => [
                    'title' => 'Bitcoin Keno – Play Online Keno with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Keno at Casinobit ♠ Wide Selection of Online Keno Games to Win BTC ✔ Provably Fair'
                ],
                'bingo' => [
                    'title' => 'Bitcoin Bingo – Play Online Bingo with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Bingo at Casinobit ♠ Wide Selection of Online Bingo Games to Win BTC ✔ Provably Fair'
                ],
                'scratch-card' => [
                    'title' => 'Bitcoin Scratch Сards – Play Online Scratch Сards with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Scratch Сards at Casinobit ♠ Wide Selection of Online Scratch Сard Games to Win BTC ✔ Provably Fair'
                ],
                'video-poker' => [
                    'title' => 'Bitcoin Video Poker – Play Online Video Poker with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Video Poker at Casinobit ♠ Wide Selection of Online Video Poker Games to Win BTC ✔ Provably Fair'
                ],
                'virtual-games' => [
                    'title' => 'Bitcoin Virtual Games – Play Online Virtual Games with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Virtual Games at Casinobit ♠ Wide Selection of Online Virtual Games to Win BTC ✔ Provably Fair'
                ],
                'virtual-sports' => [
                    'title' => 'Bitcoin Virtual Sports – Play Online Virtual Sports with Bitcoin | Casinobit',
                    'description' => 'Play Bitcoin Virtual Sports at Casinobit ♠ Wide Selection of Online Virtual Sport Games to Win BTC ✔ Provably Fair'
                ],
                'live-casino' => [
                    'title' => 'Bitcoin Live Casino – Play Live BTC Casino Games Online with Real Dealers | Casinobit',
                    'description' => 'Play Bitcoin Live Casino with Real Dealers at Casinobit ♠ Wide Selection of Online Live Casino Games to Win BTC ✔ Provably Fair'
                ],
                'table-games' => [
                    'title' => 'Bitcoin Table Games – Play Bitcoin Casino Table Games Online with Bitcoin | Casinobit',
                    'description' => 'Play Gambling Table Games with Bitcoin at Casinobit ♠ Wide Selection of Online Casino Table Games to Win BTC ✔ Provably Fair'
                ],
                'others' => [
                    'title' => 'Bitcoin Games – Play Bitcoin Casino Games Online with Bitcoin | Casinobit',
                    'description' => 'Play at Casinobit ♠ Wide Selection of Online Games to Win BTC ✔ Provably Fair'
                ]
            ]
        ];
        $langRepos = \Illuminate\Support\Facades\App::make(\Waavi\Translation\Repositories\LanguageRepository::class);
        $translateRepos = \Illuminate\Support\Facades\App::make(\Waavi\Translation\Repositories\TranslationRepository::class);

        $locales = $langRepos->availableLocales();

        foreach ($prefixs as $prefix => $keys) {
            foreach ($locales as $locale) {
                foreach ($keys as $key => $value) {
                    foreach ($value as $k => $v) {
                        $item = $prefix . '_' . $key . '_' . $k;

                        $translateRepos->create([
                            'locale' => $locale,
                            'namespace' => '*',
                            'group' => 'metatag',
                            'item' => $item,
                            'text' => $v,
                        ]);

                        $tId = $translateRepos->search($locale, $item)->first()->id;

//                        $translateRepos->update($tId, $locale . '::::' . $v);
                        $translateRepos->update($tId, $v);
                    }

                }
            }
        }

//        // For main page
//        foreach ($locales as $locale) {
//            $item = 'games_' . $key . '_' . $k;
//
//            $translateRepos->create([
//                'locale' => $locale,
//                'namespace' => '*',
//                'group' => 'metatag',
//                'item' => $item,
//                'text' => $v,
//            ]);
//
//            $tId = $translateRepos->search($locale, $item)->first()->id;
//
//            $translateRepos->update($tId, $locale . '::::' . $v);
//        }

    }
}
