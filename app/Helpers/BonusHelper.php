<?php

namespace Helpers;

use DB;
use Log;
use App\UserBonus;

/**
 * Class BonusHelper
 * @package Helpers
 */
class BonusHelper
{
    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    static public function getClass($id)
    {
        $bonusClasses = config('bonus.classes');

        foreach ($bonusClasses as $class) {
            if ($class::$id == $id) {
                return $class;
            };
        }

        return false;
    }
}