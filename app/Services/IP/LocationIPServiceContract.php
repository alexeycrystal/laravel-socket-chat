<?php


namespace App\Services\IP;


use Stevebauman\Location\Position;

/**
 * Interface LocationIPServiceContract
 * @package App\Services\IP
 */
interface LocationIPServiceContract
{
    /**
     * @param string $ip
     * @return Position|null
     */
    public function getLocationDataByIp(string $ip): ?Position;
}
