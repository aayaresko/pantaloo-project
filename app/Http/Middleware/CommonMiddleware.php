<?php

namespace App\Http\Middleware;

/**
 * Class CommonMiddleware
 * @package App\Http\Middleware
 */
abstract class CommonMiddleware
{
    /**
     * @var array
     */
    protected $except = [];

    /**
     * @param $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
//            if ($except !== '/') {
//                $except = trim($except, '/');
//            }
            $except = trim($except, '/');

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}



