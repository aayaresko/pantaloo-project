<?php


namespace App\Providers\Intercom;

use App\Bonus;
use App\ModernExtraUsers;
use App\User;
use App\UserBonus;
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
            ]];
        foreach ($response['custom_attributes'] as $k => $v) {
            Log::info($k . ' => ' . $v);
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

            //to do FIX THIS!
            //to do helper - delete this lines and from view bonus
            $dataBonus = $userBonus->data;

            $bonusWagerUser = isset($dataBonus['wagered_bonus_amount']) ? $dataBonus['wagered_bonus_amount'] : 0;
            $bonusWager = isset($dataBonus['wagered_sum']) ? $dataBonus['wagered_sum'] : 0;

            $depositWagerUser = isset($dataBonus['wagered_amount']) ? $dataBonus['wagered_amount'] : 0;

            if (isset($dataBonus['wagered_deposit']) and (int)$dataBonus['wagered_deposit'] === 1) {
                $depositWager = isset($dataBonus['total_deposit']) ? $dataBonus['total_deposit'] : 0;
            } else {
                $depositWager = 0;
            }

            $curreny = config('app.currencyCode');

            $depositWagerString = '-';
            $bonusWagerString = $bonusWagerUser . ' / ' . $bonusWager . $curreny;
            if ($userBonus->bonus_id == 1) {
                $depositWagerString = $depositWagerUser . ' / ' . $depositWager . $curreny;
            }
        }

        return "bw: {$bonusWagerString}  dw:{$depositWagerString}";
    }

    private static function getBalanceRealBonus(User $user)
    {
        return $user->getRealBalance() . '/' . $user->getBonusBalance();
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