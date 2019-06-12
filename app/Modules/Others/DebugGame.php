<?php

namespace App\Modules\Others;

/**
 * Class DebugGame.
 */
class DebugGame
{
    /**
     * @var
     */
    protected $start;

    /**
     * @var
     */
    protected $startDate;

    /**
     * DebugGame constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function start()
    {
        $start = microtime(true);
        $startTime = explode(' ', microtime());
        $startDate = date('m-d-y H:i:s', $startTime[1]).
            substr((string) $startTime[0], 1, 4);

        $this->start = $start;
        $this->startDate = $startDate;

        return true;
    }

    /**
     * @return array
     */
    public function end()
    {
        $start = $this->start;
        $startDate = $this->startDate;
        $endTime = explode(' ', microtime());
        $endDate = date('m-d-y H:i:s', $endTime[1]).
            substr((string) $endTime[0], 1, 4);
        $time = round(microtime(true) - $start, 4);

        $responseLog['time'] = $time;
        $responseLog['startDate'] = $startDate;
        $responseLog['endDate'] = $endDate;

        return [
            'time' => $time,
            'endDate' => $endDate,
            'startDate' => $startDate,
        ];
    }
}
