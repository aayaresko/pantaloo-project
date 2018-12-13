<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$foreignPages = config('app.foreignPages');
$partner = parse_url($foreignPages['partner'])['host'];
$partner = 'partner.test.test';
//sub-domain
Route::group(['domain' => $partner], function () {

    Route::get('/', ['as' => 'affiliates.index', 'uses' => 'AffiliatesController@index']);
    Route::post('/affiliates/login', ['as' => 'affiliates.login', 'uses' => 'Auth\Affiliates\AuthController@enter']);
    Route::post('/affiliates/register', ['as' => 'affiliates.login', 'uses' => 'Auth\Affiliates\AuthController@register']);

    Route::get('/affiliates/password/reset/{token?}', ['as' => 'affiliates.passwordResetPage', 'uses' => 'Auth\Affiliates\PasswordController@showResetForm']);
    Route::post('/affiliates/password/email', ['as' => 'affiliates.passwordEmail', 'uses' => 'Auth\Affiliates\PasswordController@sendResetLinkEmail']);
    Route::post('/affiliates/password/reset', ['as' => 'affiliates.passwordReset', 'uses' => 'Auth\Affiliates\PasswordController@reset']);

    //redefine routes
    Route::group(['prefix' => 'affiliates', 'middleware' => ['agent']], function () {
        Route::get('/logoutMain', ['as' => 'affiliates.logoutMain', 'uses' => 'Auth\Affiliates\PasswordController@logout']);
    });
});

Route::get('/', ['as' => 'main', 'uses' => 'HomeController@index']);

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@home']);

Route::auth();

Route::any('/ezugi/callback', ['as' => 'ezugi', 'uses' => 'EzugiController@callback']);
Route::any('/api/callback', ['as' => 'slots.api', 'uses' => 'ApiController@callback']);
Route::any('/api/demo', ['as' => 'slots.free', 'uses' => 'FreeSpinsController@callback']);

Route::get('/casino', ['as' => 'casino', 'uses' => 'SlotController@casino']);

Route::get('/dice', ['as' => 'dice', 'uses' => 'SlotController@dice']);
Route::get('/blackjack', ['as' => 'blackjack', 'uses' => 'SlotController@blackjack']);
Route::get('/roulette', ['as' => 'roulette', 'uses' => 'SlotController@roulette']);
Route::get('/baccarat', ['as' => 'baccarat', 'uses' => 'SlotController@baccarat']);
Route::get('/numbers', ['as' => 'numbers', 'uses' => 'SlotController@numbers']);
Route::get('/keno', ['as' => 'keno', 'uses' => 'SlotController@keno']);
Route::get('/holdem', ['as' => 'holdem', 'uses' => 'SlotController@holdem']);

//Route::get('/slots', ['as' => 'slots', 'uses' => 'SlotController@index']);
Route::get('/slots/filter', ['as' => 'slots.filter', 'uses' => 'SlotController@filter']);
Route::get('/test', ['as' => 'test', 'uses' => 'SlotController@test']);

Route::get('/page/{page_url}', ['as' => 'page', 'uses' => 'PageController@get']);

Route::get('/support', ['as' => 'support', 'uses' => 'ChatController@index']);

Route::get('/demo/{slot}/{game_id?}', ['as' => 'demo', 'uses' => 'SlotController@demo']);

Route::get('/bonuses', ['as' => 'bonus.promo', 'uses' => 'BonusController@promo']);

Route::post('/contribution/callback', ['as' => 'usd.callback', 'uses' => 'MoneyController@depositCallback'])->middleware(['ip.check']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/activate/{token}', ['as' => 'email.activate', 'uses' => 'UsersController@activate']);

    Route::get('/deposit', ['as' => 'deposit', 'uses' => 'MoneyController@deposit']);
    Route::get('/withdraw', ['as' => 'withdraw', 'uses' => 'MoneyController@withdraw']);
    Route::post('/withdraw', ['as' => 'withdrawDo', 'uses' => 'MoneyController@withdrawDo']);
    Route::get('/settings', ['as' => 'settings', 'uses' => 'UsersController@settings']);

    Route::get('/contribution', ['as' => 'usd.deposit', 'uses' => 'MoneyController@depositUsd']);
    Route::post('/contribution', ['as' => 'usd.depositDo', 'uses' => 'MoneyController@depositUsdDo']);
    Route::get('/contribution/success', ['as' => 'usd.success', 'uses' => 'MoneyController@depositSuccess']);
    Route::get('/contribution/fail', ['as' => 'usd.fail', 'uses' => 'MoneyController@depositFail']);

    Route::get('/bonus/cancel', ['as' => 'bonus.cancel', 'uses' => 'BonusController@cancel']);

    Route::post('/password', ['as' => 'password', 'uses' => 'UsersController@password']);
    Route::post('/email/confirm', ['as' => 'email.confirm', 'uses' => 'UsersController@confirmEmail']);

    Route::get('/bonus', ['as' => 'bonus', 'uses' => 'BonusController@index']);
    Route::get('/bonus/{bonus}/activate', ['as' => 'bonus.activate', 'uses' => 'BonusController@activate']);

    Route::get('/slot/{slot}/{game_id?}', ['as' => 'slot', 'uses' => 'SlotController@get']);
    Route::get('/free', ['as' => 'freeSpins', 'uses' => 'SlotController@freeSpins']);

    Route::get('/free/stop', ['as' => 'stopFree', 'uses' => 'MoneyController@stopFreeGame']);

    Route::get('/ajax/balance', ['as' => 'ajax.balance', 'uses' => 'MoneyController@balance']);
    Route::get('/ajax/start/{slot}', ['as' => 'ajax.slot', 'uses' => 'SlotController@startUrl']);

    Route::get('/ajax/transactions/{transaction_id}/new', ['as' => 'ajax.transactions', 'uses' => 'MoneyController@newTransactions']);
    Route::get('/ajax/transactions/all', ['as' => 'ajax.allTransactions', 'uses' => 'MoneyController@allTransactions']);

    //Route::get('/share/session', 'AuthController@share');

    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
        Route::get('/bonus/{user}', ['as' => 'admin.bonuses', 'uses' => 'BonusController@userBonuses']);
        Route::get('/bonus/{user}/{bonus}/activate', ['as' => 'admin.bonusActivate', 'uses' => 'BonusController@adminActivate']);
        Route::get('/bonus/{user}/cancel', ['as' => 'admin.bonusCancel', 'uses' => 'BonusController@adminCancel']);

        Route::get('/translations', ['as' => 'translations', 'uses' => 'TranslationController@index']);
        Route::post('/translations/save', ['as' => 'translations.save', 'uses' => 'TranslationController@save']);
        Route::post('/translations/delete', ['as' => 'translations.delete', 'uses' => 'TranslationController@delete']);

        Route::any('/dashboard', ['as' => 'dashboard', 'uses' => 'AdminController@dashboard']);

        Route::get('/', 'UsersController@index');
        Route::get('/users', ['as' => 'users', 'uses' => 'UsersController@index']);
        Route::post('/user/{user}', ['as' => 'user.update', 'uses' => 'UsersController@update']);
        Route::get('/slots', ['as' => 'admin.slots', 'uses' => 'SlotController@adminSlots']);
        Route::get('/slot/{slot}', ['as' => 'admin.slot', 'uses' => 'SlotController@edit']);
        Route::post('/slot/{slot}', ['uses' => 'SlotController@update']);

        //admin for integrated games
        //games
        Route::get('/integratedGames', ['as' => 'admin.integratedGames', 'uses' => 'Admin\IntegratedGamesController@index']);
        Route::get('/integratedGame', ['as' => 'admin.integratedGame', 'uses' => 'Admin\IntegratedGamesController@getAll']);
        Route::get('/integratedGame/{id}', ['as' => 'admin.integratedGame', 'uses' => 'Admin\IntegratedGamesController@game']);
        Route::post('/integratedGame/{id}', ['as' => 'admin.integratedGameUpdate', 'uses' => 'Admin\IntegratedGamesController@gameUpdate']);
        //type
        Route::get('/integratedTypes', ['as' => 'admin.integratedTypes', 'uses' => 'Admin\IntegratedTypesController@index']);
        Route::get('/integratedType/{id}', ['as' => 'admin.integratedType', 'uses' => 'Admin\IntegratedTypesController@edit']);
        Route::post('/integratedType/{id}', ['as' => 'admin.integratedTypeUpdate', 'uses' => 'Admin\IntegratedTypesController@update']);
        //category
        Route::get('/integratedCategories', ['as' => 'admin.integratedCategories', 'uses' => 'Admin\IntegratedCategoriesController@index']);
        Route::get('/integratedCategory/{id}', ['as' => 'admin.integratedCategory', 'uses' => 'Admin\IntegratedCategoriesController@edit']);
        Route::post('/integratedCategory/{id}', ['as' => 'admin.integratedCategoryUpdate', 'uses' => 'Admin\IntegratedCategoriesController@update']);
        //Settings
        Route::get('/integratedSettings', ['as' => 'admin.integratedSettings', 'uses' => 'Admin\IntegratedSettingsController@index']);
        Route::post('/integratedSettings', ['as' => 'admin.integratedSettingsUpdate', 'uses' => 'Admin\IntegratedSettingsController@update']);
        //end

        Route::get('/bitcoin', ['as' => 'admin.bitcoin', 'uses' => 'MoneyController@bitcoin']);
        Route::post('/bitcoin', ['as' => 'admin.bitcoin', 'uses' => 'MoneyController@sendBitcoins']);

        Route::get('/transfers', ['as' => 'admin.transfers', 'uses' => 'MoneyController@transfers']);
        Route::post('/transfers', ['as' => 'admin.transfers', 'uses' => 'MoneyController@transfers']);

        Route::get('/pages', ['as' => 'pages', 'uses' => 'PageController@index']);

        Route::get('/pages/new', ['as' => 'pages.new', 'uses' => 'PageController@create']);
        Route::post('/pages/new', ['as' => 'pages.new', 'uses' => 'PageController@store']);

        Route::get('/page/{page}/edit', ['as' => 'pages.edit', 'uses' => 'PageController@edit']);
        Route::post('/page/{page}/edit', ['as' => 'pages.edit', 'uses' => 'PageController@update']);
        Route::get('/page/{page}/delete', ['as' => 'pages.delete', 'uses' => 'PageController@delete']);

        Route::get('/stat', ['as' => 'stat', 'uses' => 'MoneyController@stat']);

        Route::get('/pending', ['as' => 'pending', 'uses' => 'MoneyController@pending']);

        Route::get('/transaction/{transaction}/aprove', ['as' => 'aprove', 'uses' => 'MoneyController@aprove']);
        Route::get('/transaction/{transaction}/freeze', ['as' => 'freeze', 'uses' => 'MoneyController@freeze']);
        Route::get('/transaction/{transaction}/unfreeze', ['as' => 'unfreeze', 'uses' => 'MoneyController@unfreeze']);
        Route::get('/transaction/{transaction}/cancel', ['as' => 'cancel', 'uses' => 'MoneyController@cancel']);

        Route::get('/banners', ['as' => 'admin.banners', 'uses' => 'BannerController@index']);
        Route::get('/banners/create', ['as' => 'banners.create', 'uses' => 'BannerController@create']);
        Route::post('/banners/create', ['as' => 'banners.store', 'uses' => 'BannerController@store']);
        Route::get('/banners/{banner}/delete', ['as' => 'banners.delete', 'uses' => 'BannerController@delete']);

        Route::get('/faq/create', ['as' => 'faqCreate', 'uses' => 'QuestionController@create']);
        Route::get('/faq', ['as' => 'admin.faq', 'uses' => 'QuestionController@index']);
        Route::post('/faq/store', ['as' => 'faqStore', 'uses' => 'QuestionController@store']);

        Route::get('/faq/{question}/edit', ['as' => 'faqEdit', 'uses' => 'QuestionController@edit']);
        Route::post('/faq/{question}/update', ['as' => 'faqUpdate', 'uses' => 'QuestionController@update']);
        Route::get('/faq/{question}/delete', ['as' => 'faqDelete', 'uses' => 'QuestionController@delete']);

        Route::get('/agent/list', ['as' => 'admin.agents', 'uses' => 'AgentController@all']);
        Route::post('/agent/{user}/commission', ['as' => 'admin.agentCommission', 'uses' => 'AgentController@commission']);

        Route::get('/agent/payments', ['as' => 'admin.agentPayments', 'uses' => 'AgentController@payments']);

        Route::get('/transactions', ['as' => 'admin.transactions', 'uses' => 'TransactionController@index']);
        Route::get('/transactions/filter', ['as' => 'admin.filterTransactions', 'uses' => 'TransactionController@filter']);

        Route::get('/balance', ['as' => 'admin.balance', 'uses' => 'AdminController@balance']);

    });

    Route::group(['prefix' => 'affiliates', 'middleware' => ['agent']], function () {
        Route::get('/', function () {
            return redirect()->route('agent.dashboard');
        });

        Route::get('/logout', ['as' => 'agent.logout', 'uses' => 'AgentController@logout']);

        Route::get('/dashboard', ['as' => 'agent.dashboard', 'uses' => 'AgentController@dashboard']);

        Route::get('/transactions', ['as' => 'agent.transactions', 'uses' => 'TransactionController@index']);
        Route::get('/transactions/filter', ['as' => 'agent.filterTransactions', 'uses' => 'TransactionController@filter']);

        Route::get('/trackers', ['as' => 'agent.trackers', 'uses' => 'AgentController@trackers']);
        Route::post('/tracker/create', ['as' => 'agent.store_tracker', 'uses' => 'AgentController@storeTracker']);
        Route::post('/tracker/{tracker}/update', ['as' => 'agent.updateTracker', 'uses' => 'AgentController@updateTracker']);

        Route::get('/banners', ['as' => 'agent.banners', 'uses' => 'BannerController@view']);
        Route::get('/faq', ['as' => 'agent.faq', 'uses' => 'QuestionController@view']);

        Route::get('/withdraw', ['as' => 'agent.withdraw', 'uses' => 'AgentController@withdraw']);
        Route::post('/withdraw', ['as' => 'agent.withdrawDo', 'uses' => 'AgentController@withdrawDo']);
    });

    /* Pantallo Games */
    Route::group(['prefix' => 'games/pantallo'], function () {
        Route::get('/getGameList', ['as' => 'games.pantallo.getGameList', 'uses' => 'PantalloGamesController@getGameList']);
        Route::get('/loginPlayer', ['as' => 'games.pantallo.loginPlayer', 'uses' => 'PantalloGamesController@loginPlayer']);
        Route::get('/logoutPlayer', ['as' => 'games.pantallo.logoutPlayer', 'uses' => 'PantalloGamesController@logoutPlayer']);
    });


    Route::get('/integratedGame/{gameId}', ['as' => 'integratedGame', 'uses' => 'IntegratedGamesController@getGame']);
    Route::get('/integratedGameLink/provider/{providerId}/game/{gameId}',
        ['as' => 'integratedGameJson', 'uses' => 'IntegratedGamesController@getGameLink']);
});

Route::get('/slots', ['as' => 'slots', 'uses' => 'IntegratedGamesController@index']);
Route::get('/integratedGames', ['as' => 'integratedGames', 'uses' => 'IntegratedGamesController@index']);
Route::get('/integratedGamesJson', ['as' => 'integratedGamesJson', 'uses' => 'IntegratedGamesController@getGames']);


/* Pantallo Games */
Route::group(['prefix' => 'games'], function () {
    Route::get('/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']);
});

//testing
Route::get('/test/test', ['as' => 'test.test', 'uses' => 'TestController@test']);
Route::get('/test/types/{category}', ['as' => 'test.test', 'uses' => 'TestController@testTypes']);
Route::get('/test/game/{game}', ['as' => 'test.test', 'uses' => 'TestController@game']);

Route::get('/agent/login', ['as' => 'agent.login', 'uses' => 'AgentController@login']);
Route::post('/agent/login', ['as' => 'agent.login', 'uses' => 'AgentController@enter']);