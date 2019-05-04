<?php


namespace App\Providers\Intercom;

use App\User;


class UserDataResolver
{
    protected $user;
    protected $container;

    public static function getData(User $user)
    {
        $response = [
            'email' => 'example2@example.com',
            'custom_attributes' => [
                'Current bonus' => self::getCurrentBonus($user),
                'Wager status' => self::getWagerStatus($user),
                'Balance Real/Bonus' => self::getBalanceRealBonus($user),
                'Account status' => self::getAccountStatus($user),
                'Email verified' => self::getEmailVerified($user),
            ]];
        dump($response);
        return $response;
    }

    private static function getCurrentBonus(User $user)
    {
        return 'dummy';
    }

    private static function getWagerStatus(User $user)
    {
        return 'dummy';
    }

    private static function getBalanceRealBonus(User $user)
    {
        return 'dummy';
    }

    private static function getAccountStatus(User $user)
    {
        return 'dummy';
    }

    private static function getEmailVerified(User $user)
    {
        return 'dummy';
    }
}