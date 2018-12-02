<?php

namespace App\Modules\Games;

/**
 * Interface GamesSystem
 * @package App\Modules\Games
 */
interface GamesSystem
{
    /**
     * @param $request
     * @return mixed
     */
    public function loginPlayer($request);

    /**
     * @param $request
     * @return mixed
     */
    public function logoutPlayer($request);

}