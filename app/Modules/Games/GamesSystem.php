<?php

namespace App\Modules\Games;

/**
 * Interface GamesSystem.
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

    /**
     * @param $request
     * @return mixed
     */
    public function callback($request);

    /**
     * @param $request
     * @return mixed
     */
    public function freeRound($request);
}
