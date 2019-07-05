<?php

namespace App\Providers\Intercom;

use App\User;
use App\Bonus;
use App\UserBonus;
use Helpers\BonusHelper;
use App\ModernExtraUsers;
use Helpers\GeneralHelper;
use Illuminate\Support\Facades\Log;

class UserDataResolver
{
    protected $user;

    protected $container;

    public static function getData(User $user)
    {
        $response = [
            'email' => $user->email,
            'custom_attributes' => [
                'Current bonus' => self::getCurrentBonus($user),
                'Wager status' => self::getWagerStatus($user),
                'Balance Real/Bonus' => self::getBalanceRealBonus($user),
                'Account status' => self::getAccountStatus($user),
                'Email verified' => self::getEmailVerified($user),
                'IP' => GeneralHelper::visitorIpCloudFlare(),
                'GEO' => GeneralHelper::visitorCountryCloudFlare()
            ], ];
        foreach ($response['custom_attributes'] as $k => $v) {
            Log::info($k.' => '.$v);
        }

        return $response;
    }

    private static function getCurrentBonus(User $user)
    {
        $userBonus = UserBonus::where('user_id', $user->id)->first();
        $bonus = is_null($userBonus) ? null : Bonus::findOrFail($userBonus->bonus_id);

        return is_null($bonus) ? '' : $bonus->name;
    }

    private static function getWagerStatus(User $user)
    {
        $userBonus = UserBonus::where('user_id', $user->id)->first();

        $bonusWagerString = $depositWagerString = '-';

        if ($userBonus) {
            $bonusStatistics = BonusHelper::bonusStatistics($userBonus);
            $currency = config('app.currencyCode');

            $depositWagerString = '-';
            $bonusWagerString = $bonusStatistics['bonusWager']['real'].' / '.
                $bonusStatistics['bonusWager']['necessary'].$currency;
            if ($userBonus->bonus_id == 1) {
                $depositWagerString = $bonusStatistics['depositWager']['real'].' / '.
                    $bonusStatistics['depositWager']['necessary'].$currency;
            }
        }

        return "bw: {$bonusWagerString}  dw:{$depositWagerString}";
    }

    private static function getBalanceRealBonus(User $user)
    {
        return $user->getRealBalance().'/'.$user->getBonusBalance();
    }

    private static function getAccountStatus(User $user)
    {
        $blockUser = ModernExtraUsers::where('user_id', $user->id)
            ->where('code', 'block')->first();

        return is_null($blockUser) || $blockUser->value == 0 ? 'open' : 'banned';
    }

    private static function getEmailVerified(User $user)
    {
        return $user->email_confirmed ? 'confirmed' : 'not confirmed';
    }
}
