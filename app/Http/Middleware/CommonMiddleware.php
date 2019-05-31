<?php

namespace App\Http\Middleware;

/**
 * Class CommonMiddleware.
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
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
