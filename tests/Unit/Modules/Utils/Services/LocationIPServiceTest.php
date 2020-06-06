<?php


namespace Tests\Unit\Modules\Utils\Services;


use App\Modules\User\Services\UserProfileServiceContract;
use App\Services\IP\LocationIPServiceContract;
use Tests\Unit\AbstractTest;

class LocationIPServiceTest extends AbstractTest
{
    protected LocationIPServiceContract $locationIpService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->locationIpService = $this->app->make(LocationIPServiceContract::class);
    }

    public function testGetLocationDataByIp()
    {
        $service = $this->locationIpService;

        $invalidIP1 = '123123123123';
        $this->assertNull($service->getLocationDataByIp($invalidIP1));

        $invalidIP2 = '1231.123123123.12312312.1233';
        $this->assertNull($service->getLocationDataByIp($invalidIP2));

        $ukraineIP = '2.57.112.0';
        $this->assertNotNull($service->getLocationDataByIp($ukraineIP));

        $usaIP = '2.18.39.0';
        $this->assertNotNull($service->getLocationDataByIp($usaIP));

        $unitedKingdom = '2.16.45.0';
        $this->assertNotNull($service->getLocationDataByIp($unitedKingdom));
    }
}
