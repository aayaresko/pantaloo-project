<?php

namespace Helpers;

use DB;
use Log;
use App\UserBonus;

/**
 * Class BonusHelper.
 */
class BonusHelper
{
    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getClass($id)
    {
        $bonusClasses = config('bonus.classes');

        foreach ($bonusClasses as $class) {
            if ($class::$id == $id) {
                return $class;
            }
        }

        return false;
    }

    public static function bonusStatistics($bonusObject)
    {
        $dataBonus = $bonusObject->data;

        $bonusWagerUser = isset($dataBonus['wagered_bonus_amount']) ? $dataBonus['wagered_bonus_amount'] : 0;
        $bonusWager = isset($dataBonus['wagered_sum']) ? $dataBonus['wagered_sum'] : 0;

        $depositWagerUser = isset($dataBonus['wagered_amount']) ? $dataBonus['wagered_amount'] : 0;

        if (isset($dataBonus['wagered_deposit'])) {
            $depositWager = isset($dataBonus['total_deposit']) ? $dataBonus['total_deposit'] : 0;
        } else {
            $depositWager = 0;
        }

        return [
            'bonusWager' => [
                'real' => $bonusWagerUser,
                'necessary' =>$bonusWager,
            ],
            'depositWager' => [
                'real' => $depositWagerUser,
                'necessary' =>$depositWager,
            ],
        ];
    }
}
