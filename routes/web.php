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

//use Illuminate\Foundation\Auth\ResetsPasswords;

Route::group(['middleware' => ['web'], 'prefix' => 'testMode'], function () {
    Route::get('/getTestMode', ['uses' => 'TestMode\GeneralController@getTestMode']);
    Route::get('/sendDeposit', ['uses' => 'TestMode\GeneralController@sendDepositView']);
    Route::post('/sendDeposit', ['uses' => 'TestMode\GeneralController@sendDeposit']);
});

Route::get('robots.txt', function (Illuminate\Http\Request $request) {
    return view('robots', [
        'host' => $request->getHost(),
    ]);
});

//for optimization add array keep all language in config
$languages = Helpers\GeneralHelper::getListLanguage();
Config::set('getListLanguage', $languages);

$foreignPages = config('app.foreignPages');
$partner = parse_url($foreignPages['partner'])['host'];
$landingPage = parse_url($foreignPages['landingPage'])['host'];
//$partner = 'partner.test.test';

Route::group(['middleware' => ['landing', 'ip.country.block']], function () use ($landingPage) {
    Route::group(['domain' => $landingPage, 'as' => 'landing'], function () {
        Route::get('/', ['as' => 'general', 'uses' => 'Landing\LandingController@main']);
        Route::get('/general/{lang?}', ['as' => 'general', 'uses' => 'Landing\LandingController@generalLending']);
    });
});

Route::group(['middleware' => ['web', 'ip.domain.country.block']], function () use ($partner) {
    //sub-domain
    Route::group(['domain' => $partner], function () {
        Route::get('/', ['as' => 'affiliates.index', 'uses' => 'Partner\AffiliatesController@index']);
        Route::post('/affiliates/feedback', ['as' => 'affiliates.login', 'uses' => 'Partner\AffiliatesController@feedback']);
        Route::post('/affiliates/login', ['as' => 'affiliates.login', 'uses' => 'Auth\Affiliates\AuthController@enter']);
        Route::post('/affiliates/register', ['as' => 'affiliates.login', 'uses' => 'Auth\Affiliates\AuthController@register']);

        Route::get('/password/reset/{token?}', ['as' => 'affiliates.passwordResetPage', 'uses' => 'Auth\Affiliates\PasswordController@showResetForm']);
        Route::post('/affiliates/password/email', ['as' => 'affiliates.passwordEmail', 'uses' => 'Auth\Affiliates\PasswordController@sendResetLinkEmail']);

        Route::post('/affiliates/password/reset', ['as' => 'affiliates.passwordReset', 'uses' => 'Auth\Affiliates\PasswordController@reset']);
        Route::post('affiliates/sendToken/{userEmail}', ['as' => 'affiliates.sendToken', 'uses' => 'Auth\Affiliates\AuthController@confirmEmail']);
        Route::post('affiliates/activate/{token}/email/{email}', ['as' => 'affiliates.email.activate', 'uses' => 'Auth\Affiliates\AuthController@activate']);

        //redefine routes affiliates
        Route::group(['prefix' => 'affiliates', 'middleware' => ['auth', 'agent']], function () {
            Route::get('/logoutMain', ['as' => 'affiliates.logoutMain', 'uses' => 'Auth\Affiliates\AuthController@logout']);
        });
    });

    //delete this after only ine panel partner
    Route::group(['prefix' => 'affiliates', 'middleware' => ['agent']], function () {
        Route::get('/logoutMain', ['as' => 'affiliates.logoutMain', 'uses' => 'Auth\Affiliates\AuthController@logout']);
    });
});

Route::group(['middleware' => ['web', 'ip.country.block']], function () use ($languages) {
    Route::get('/language/{lang}', ['as' => 'setLanguage', 'uses' => 'TranslationController@setLanguage']);

    Route::get('/', 'HomeController@multiLang')->middleware(['session.reflash', 'language.switch']);

    //auth
    Route::post('register', 'Auth\RegisterController@register');

    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout');


    //bonus bonus
    //Route::get('/invite-for-welcome-bonus', ['uses' => 'BonusController@getWelcomeBonus']);
    //bonus bonus


    Route::group([
        'prefix' => '{lang}',
        'where' => ['lang' => '('.implode('|', $languages).')'],
        'middleware' => 'language.switch',
    ], function () {
        Route::get('/', ['as' => 'main', 'uses' => 'HomeController@index']);
        Route::get('/casino', ['as' => 'casino', 'uses' => 'SlotController@casino']);
        Route::get('/dice', ['as' => 'dice', 'uses' => 'SlotController@dice']);
        Route::get('/blackjack', ['as' => 'blackjack', 'uses' => 'SlotController@blackjack']);
        Route::get('/roulette', ['as' => 'roulette', 'uses' => 'SlotController@roulette']);
        Route::get('/baccarat', ['as' => 'baccarat', 'uses' => 'SlotController@baccarat']);
        Route::get('/numbers', ['as' => 'numbers', 'uses' => 'SlotController@numbers']);
        Route::get('/keno', ['as' => 'keno', 'uses' => 'SlotController@keno']);
        Route::get('/holdem', ['as' => 'holdem', 'uses' => 'SlotController@holdem']);

        Route::get('/games/{type_name?}', ['as' => 'games', 'uses' => 'IntegratedGamesController@index']);
        //Route::get('/slots', ['as' => 'slots', 'uses' => 'SlotController@index']);
        Route::get('/slots/filter', ['as' => 'slots.filter', 'uses' => 'SlotController@filter']);
        Route::get('/test', ['as' => 'test', 'uses' => 'SlotController@test']);

        Route::get('/page/{page_url}', ['as' => 'page', 'uses' => 'PageController@get']);

        Route::get('/faq', ['as' => 'support', 'uses' => 'ChatController@index']);

        Route::get('/demo/{slot}/{game_id?}', ['as' => 'demo', 'uses' => 'SlotController@demo']);

        Route::get('/bonuses', ['as' => 'bonus.promo', 'uses' => 'BonusController@promo']);

        //auth
        Route::get('/password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('post.password.reset');

        Route::get('/password/forgot', 'Auth\ForgotPasswordController@showLinkRequestForm');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    });

    //Route::any('/ezugi/callback', ['as' => 'ezugi', 'uses' => 'EzugiController@callback']);
    //Route::any('/api/callback', ['as' => 'slots.api', 'uses' => 'ApiController@callback']);
    //Route::any('/api/demo', ['as' => 'slots.free', 'uses' => 'FreeSpinsController@callback']);
    //Route::post('/contribution/callback', ['as' => 'usd.callback', 'uses' => 'MoneyController@depositCallback'])->middleware(['ip.check']);

    Route::group(['middleware' => ['auth']], function () use ($languages) {
        Route::get('/intercom/update', ['as' => 'intercom', 'uses' => 'IntercomController@update']);

        Route::group([
            'prefix' => '{lang}',
            'where' => ['lang' => '('.implode('|', $languages).')'],
            'middleware' => 'language.switch',
        ], function () {
            Route::get('/account', ['as' => 'account', 'uses' => 'UserAccountController@account']);
            //Route::get('/deposit', ['as' => 'deposit', 'uses' => 'MoneyController@deposit']);
            Route::get('/deposit', ['as' => 'deposit', 'uses' => 'UserAccountController@deposit']);
            Route::get('/getDeposits', ['as' => 'getDeposit', 'uses' => 'UserAccountController@getDeposits']);
            Route::post('/updateUserExtra', ['as' => 'updateUserExtra', 'uses' => 'UserAccountController@updateUserExtra']);

            Route::get('/withdraw', ['as' => 'withdraw', 'uses' => 'UserAccountController@withdraw']);
            Route::post('/withdraw', ['as' => 'withdrawDo', 'uses' => 'MoneyController@withdrawDo']);
            Route::get('/settings', ['as' => 'settings', 'uses' => 'UserAccountController@settings']);
            Route::get('/bonus', ['as' => 'bonus', 'uses' => 'UserAccountController@bonuses']);
        });

        Route::get('/contribution', ['as' => 'usd.deposit', 'uses' => 'MoneyController@depositUsd']);
        Route::post('/contribution', ['as' => 'usd.depositDo', 'uses' => 'MoneyController@depositUsdDo']);
        Route::get('/contribution/success', ['as' => 'usd.success', 'uses' => 'MoneyController@depositSuccess']);
        Route::get('/contribution/fail', ['as' => 'usd.fail', 'uses' => 'MoneyController@depositFail']);


        Route::post('/password', ['as' => 'password', 'uses' => 'UsersController@password']);
        Route::post('/email/confirm', ['as' => 'email.confirm', 'uses' => 'UsersController@confirmEmail']);

        Route::post('/bonus/cancel', ['as' => 'bonus.cancel', 'uses' => 'BonusController@cancel']);
        Route::post('/bonus/{bonus}/activate', ['as' => 'bonus.activate', 'uses' => 'BonusController@activate']);

        Route::get('/slot/{slot}/{game_id?}', ['as' => 'slot', 'uses' => 'SlotController@get']);
        Route::get('/free', ['as' => 'freeSpins', 'uses' => 'SlotController@freeSpins']);

        Route::get('/free/stop', ['as' => 'stopFree', 'uses' => 'MoneyController@stopFreeGame']);

        //Route::get('/ajax/balance', ['as' => 'ajax.balance', 'uses' => 'MoneyController@balance']);
        Route::get('/ajax/start/{slot}', ['as' => 'ajax.slot', 'uses' => 'SlotController@startUrl']);

        Route::get('/ajax/transactions/{transaction_id}/new', ['as' => 'ajax.transactions', 'uses' => 'MoneyController@newTransactions']);
        Route::get('/ajax/transactions/all', ['as' => 'ajax.allTransactions', 'uses' => 'MoneyController@allTransactions']);

        //Route::get('/share/session', 'AuthController@share');

        Route::group(['prefix' => 'admin', 'middleware' => ['can:accessUserAdminPublic']], function () {
            Route::group(['middleware' => ['can:accessUserAdmin']], function () {
                Route::get('/bonus/{user}', ['as' => 'admin.bonuses', 'uses' => 'BonusController@userBonuses']);
                Route::get('/bonus/{user}/{bonus}/activate', ['as' => 'admin.bonusActivate', 'uses' => 'BonusController@adminActivate']);
                Route::get('/bonus/{user}/cancel', ['as' => 'admin.bonusCancel', 'uses' => 'BonusController@adminCancel']);

                //Route::get('/translations', ['as' => 'translations', 'uses' => 'TranslationController@index']);
                //Route::post('/translations/save', ['as' => 'translations.save', 'uses' => 'TranslationController@save']);
                //Route::post('/translations/delete', ['as' => 'translations.delete', 'uses' => 'TranslationController@delete']);

                Route::any('/dashboard', ['as' => 'dashboard', 'uses' => 'AdminController@dashboard']);

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

                //Route::get('/agent/list', ['as' => 'admin.agents', 'uses' => 'AgentController@all']);
                //Route::post('/agent/{user}/commission', ['as' => 'admin.agentCommission', 'uses' => 'AgentController@commission']);

                Route::get('/agent/payments', ['as' => 'admin.agentPayments', 'uses' => 'AgentController@payments']);

                Route::get('/transactions', ['as' => 'admin.transactions', 'uses' => 'TransactionController@index']);
                Route::get('/transactions/filter', ['as' => 'admin.filterTransactions', 'uses' => 'TransactionController@filter']);

                Route::get('/balance', ['as' => 'admin.balance', 'uses' => 'AdminController@balance']);
            });

            Route::group(['middleware' => ['can:accessAdminAffiliatePublic']], function () {
                Route::get('/agent/list', ['as' => 'admin.agents', 'uses' => 'AgentController@all']);
                Route::get('/agent/tree', ['as' => 'admin.agents.tree', 'uses' => 'AgentController@showTree']);
                Route::get('/agent/tree/{id}', ['as' => 'admin.agents.show', 'uses' => 'AgentController@showAffiliate']);
                Route::post('/agent/tree/{id}/makeSuper', ['as' => 'admin.agents.makeSuper', 'uses' => 'AgentController@makeSuper']);
                Route::post('/agent/tree/{id}/setAffiliate', ['as' => 'admin.agents.setAffiliate', 'uses' => 'AgentController@setAffiliate']);
                Route::post('/agent/tree/{id}/setPercent', ['as' => 'admin.agents.setPercent', 'uses' => 'AgentController@setPercent']);
                Route::post('/agent/{user}/commission', ['as' => 'admin.agentCommission', 'uses' => 'AgentController@commission']);
            });

            Route::group(['prefix' => 'globalAffiliate', 'middleware' => ['can:accessUserAffiliate']], function () {
                Route::get('/users', ['as' => 'globalAffiliates.index', 'uses' => 'Partner\GlobalAffiliatesController@index']);
                Route::get('/withdraws', ['as' => 'globalAffiliates.withdraws', 'uses' => 'Partner\GlobalAffiliatesController@withdraws']);
                Route::get('/getFinance', ['as' => 'globalAffiliates.getFinance', 'uses' => 'Partner\GlobalAffiliatesController@getFinance']);
                Route::get('/getUsers', ['as' => 'globalAffiliates.users', 'uses' => 'Partner\GlobalAffiliatesController@getUsers']);
                Route::get('/getUsersTable', ['as' => 'globalAffiliates.usersTable', 'uses' => 'Partner\GlobalAffiliatesController@getUsersTable']);

                Route::get('/transaction/{transaction}/approve', ['as' => 'globalAffiliates.approve', 'uses' => 'Partner\GlobalAffiliatesController@approve']);
                Route::get('/transaction/{transaction}/freeze', ['as' => 'globalAffiliates.freeze', 'uses' => 'Partner\GlobalAffiliatesController@freeze']);
                Route::get('/transaction/{transaction}/unfreeze', ['as' => 'globalAffiliates.unfreeze', 'uses' => 'Partner\GlobalAffiliatesController@unfreeze']);
                Route::get('/transaction/{transaction}/cancel', ['as' => 'globalAffiliates.cancel', 'uses' => 'Partner\GlobalAffiliatesController@cancel']);
            });

            Route::group(['middleware' => ['can:accessAdminTranslatorPublic']], function () {
                Route::get('/translations', ['as' => 'translations', 'uses' => 'Admin\TranslationController@index']);
//                Route::get('/changeTranslation/{lang}', ['as' => 'changeTranslations', 'uses' => 'Admin\TranslationController@changeTranslation']);
//                Route::post('/translations/save', ['as' => 'translations.save', 'uses' => 'Admin\TranslationController@save']);

                Route::get('/changeTranslation/{lang}', ['as' => 'changeTranslationsModern', 'uses' => 'Admin\TranslationController@changeTranslationModern']);
                Route::get('/translation/getTransactions', ['as' => 'translations.getTransactions', 'uses' => 'Admin\TranslationController@getTransactions']);
                Route::post('/translations/save', ['as' => 'translations.saveModern', 'uses' => 'Admin\TranslationController@saveModern']);
            });

            Route::get('/', 'UsersController@index');

        });

        Route::group(['prefix' => 'affiliates', 'middleware' => ['agent']], function () {
            Route::get('/', function () {
                return redirect()->route('agent.dashboard');
            });

            Route::get('/logout', ['as' => 'agent.logout', 'uses' => 'AgentController@logout']);

            Route::get('/dashboard', ['as' => 'agent.dashboard', 'uses' => 'Partner\AffiliatesController@dashboard']);

            //Route::get('/transactions', ['as' => 'agent.transactions', 'uses' => 'TransactionController@index']);
            //Route::get('/transactions/filter', ['as' => 'agent.filterTransactions', 'uses' => 'TransactionController@filter']);

            Route::get('/transactions', ['as' => 'agent.transactions', 'uses' => 'Partner\TransactionController@index']);
            Route::get('/transactions/filter', ['as' => 'agent.filterTransactions', 'uses' => 'Partner\TransactionController@getAll']);

            //Route::get('/trackers', ['as' => 'agent.trackers', 'uses' => 'AgentController@trackers']);
            Route::get('/trackers', ['as' => 'agent.trackers', 'uses' => 'Partner\AffiliatesController@trackers']);
            Route::get('/partners', ['as' => 'agent.affiliates', 'uses' => 'Partner\AffiliatesController@partners']);
            Route::get('/partners/{id}', ['as' => 'agent.affiliates.show', 'uses' => 'Partner\AffiliatesController@partnerShow']);
            Route::get('/users', ['as' => 'agent.users', 'uses' => 'Partner\AffiliatesController@users']);
            Route::post('/partners/change/{id}', 'Partner\AffiliatesController@changeKoef')->name('agent.change.koef');

            Route::post('/tracker/create', ['as' => 'agent.store_tracker', 'uses' => 'AgentController@storeTracker']);
            Route::post('/tracker/{tracker}/update', ['as' => 'agent.updateTracker', 'uses' => 'AgentController@updateTracker']);

            Route::get('/banners', ['as' => 'agent.banners', 'uses' => 'BannerController@view']);

            Route::get('/marketingMaterial/{id}',
                ['as' => 'affiliates.marketingMaterial', 'uses' => 'Partner\AffiliatesController@marketingMaterial']);

            Route::get('/faq', ['as' => 'agent.faq', 'uses' => 'QuestionController@view']);

            //Route::get('/withdraw', ['as' => 'agent.withdraw', 'uses' => 'AgentController@withdraw']);
            Route::get('/withdraw', ['as' => 'agent.withdraw', 'uses' => 'Partner\AffiliatesController@withdraw']);
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

    Route::get('/activate/{token}/email/{email}', ['as' => 'email.activate', 'uses' => 'UsersController@activate']);

    //Route::get('/games', ['as' => 'slots', 'uses' => 'IntegratedGamesController@index']);
    Route::get('/integratedGames', ['as' => 'integratedGames', 'uses' => 'IntegratedGamesController@index']);
    Route::get('/integratedGamesJson', ['as' => 'integratedGamesJson', 'uses' => 'IntegratedGamesController@getGames']);

//    /* Pantallo Games */
//    Route::group(['prefix' => 'games'], function () {
//        Route::get('/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']);
//        Route::get('/pantallo/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']);
//        Route::get('/qtech/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']);//check this
//    });

    //Route::get('/test/freespin', ['as' => 'test.freespin', 'uses' => 'PantalloGamesController@freeRound']);

    //testing
    Route::get('/test/test', ['as' => 'test.test', 'uses' => 'TestController@test']);
    Route::get('/test/test1', ['as' => 'test.test1', 'uses' => 'TestController@test1']);
    Route::get('/test/phpinfo', ['as' => 'test.phpinfo', 'uses' => 'TestController@phpinfo']);
    Route::get('/test/error', ['as' => 'test.error', 'uses' => 'TestController@error']);
    Route::get('/test/http404', ['as' => 'test.http404', 'uses' => 'TestController@http404']);

    Route::get('/test/types/{category}', ['as' => 'test.test', 'uses' => 'TestController@testTypes']);
    Route::get('/test/game/{game}', ['as' => 'test.test', 'uses' => 'TestController@game']);

    Route::get('/agent/login', ['as' => 'agent.login', 'uses' => 'AgentController@login']);
    Route::post('/agent/login', ['as' => 'agent.login.post', 'uses' => 'AgentController@enter']);

    Route::group(['middleware' => ['auth', 'session.reflash']], function () {
        Route::get('/ajax/userActive', ['as' => 'ajax.userActive', 'uses' => 'MoneyController@userActive']);
    });
});

Route::group(['middleware' => ['ajax', 'ip.country.block']], function () {
    Route::get('/ajax/balance/{email}', ['as' => 'ajax.balance', 'uses' => 'MoneyController@balance']);
});

Route::group(['middleware' => ['ajax'], 'prefix' => 'bitcoin'], function () {
    Route::get('walletNotify', 'Bitcoin\TransactionController@walletNotify')->name('walletNotify');
    Route::get('blockNotify', 'Bitcoin\TransactionController@blockNotify')->name('blockNotify');
});

/* Pantallo Games */
Route::group(['middleware' => ['games'], 'prefix' => 'games'], function () {
    Route::get('/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']);
    Route::get('/pantallo/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']);
    Route::get('/qtech/endpoint', ['as' => 'games.balance', 'uses' => 'PantalloGamesController@endpoint']); //check this
});

Route::group(['middleware' => ['api', 'ip.country.block']], function () {
    Route::post('/api/getToken', ['uses' => 'Api\ApiController@authenticate']);
});
