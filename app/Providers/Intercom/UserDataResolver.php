<?php


namespace App\Providers\Intercom;

use App\Bonuses\Bonus;
use App\ModernExtraUsers;
use App\User;
use App\UserBonus;


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
            ]];
        //dump($response);
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
        return 'dummy';
    }

    private static function getBalanceRealBonus(User $user)
    {
        return $user->getRealBalance() . '/' .$user->getBonusBalance();
    }

    private static function getAccountStatus(User $user)
    {
        $blockUser = ModernExtraUsers::where('user_id', $user->id)
            ->where('code', 'block')->first();
        return is_null($blockUser) ? 'open','banned';
    }

    private static function getEmailVerified(User $user)
    {
        return $user->email_confirmed ? 'confirmed' : 'not confirmed';
    }
}