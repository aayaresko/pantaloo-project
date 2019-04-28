<?php

namespace Helpers;

use Log;
use App\UserBonus;

/**
 * Class BonusHelper
 * @package Helpers
 */
class BonusHelper
{

    /**
     * @param $user
     * @param int $mode
     * @return bool
     */
    static public function bonusCheck($user, $mode = 0)
    {
        $notActiveBonus = UserBonus::where('user_id', $user->id)->first();

        if (!is_null($notActiveBonus)) {
            $class = $notActiveBonus->bonus->getClass();
            $bonus_obj = new $class($user);
            try {
                $bonus_obj->realActivation();
                if ($mode === 1) {
                    $bonus_obj->close();
                }
            } catch (\Exception $e) {
                Log::alert([
                    'code' => 'bonusMessage',
                    'id' => $notActiveBonus->id,
                    'error' => $e->getMessage()
                ]);
                return false;
            }
        }
        return true;
    }

}