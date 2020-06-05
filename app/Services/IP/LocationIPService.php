<?php


namespace App\Services\IP;


use App\Generics\Services\AbstractService;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

/**
 * Class LocationIPService
 * @package App\Services\IP
 */
class LocationIPService extends AbstractService implements LocationIPServiceContract
{
    /**
     * @param string $ip
     * @return Position|null
     */
    public function getLocationDataByIp(string $ip): ?Position
    {
        $data = Location::get($ip);

        if($data && is_object($data))
            return $data;

        return null;
    }
}
