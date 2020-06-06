<?php


namespace Tests\Unit\Modules\Auth\Services;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Auth\Services\JWTServiceContract;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Unit\AbstractTest;

class AuthServiceTest extends AbstractTest
{
    protected AuthServiceContract $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->app->make(AuthServiceContract::class);
    }

    public function testGetLoggedUser()
    {
        $userInfo = $this->createTestUser();
        $token = $userInfo['token'];
        $this->assertIsString($token);

        $service = $this->authService;

        $notLoggedUser = $service->getLoggedUser();
        $this->assertNull($notLoggedUser);

        request()->headers->set('Authorization',"Bearer {$token}");
        $user = $service->getLoggedUser();
        $this->assertNotNull($user);
    }

    public function testSetLoggedUser()
    {
        $service = $this->authService;

        $userInfo = $this->createTestUser();

        $service->setLoggedUser(0);
        $this->assertNull($service->getLoggedUser());

        $service->setLoggedUser($userInfo['user_id']);

        $user = $service->getLoggedUser();
        $this->assertNotNull($user);
        $this->assertEquals($userInfo['user_id'], $user->id);
    }

    public function testLogin()
    {
        $email = 'test@gmail.com';
        $password = 'testtest';

        $this->createTestUser([
            'email' => $email,
            'password' => $password,
            'name' => 'test',
            'lang' => 'en'
        ]);

        $service = $this->authService;

        $creds = [
            'email' => $email,
            'password' => $password,
        ];

        $result = $service->login($creds);

        $this->assertNull($service->hasErrors());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);

        $failCreds = [
            'email' => 'notexisted@gmail.com',
            'password' => 'password',
        ];

        $result = $service->login($failCreds);

        $this->assertIsArray($service->hasErrors());
        $this->assertNull($result);
    }

    public function restRegistration()
    {
        $email = 'test@gmail.com';
        $password = 'testtest';

        $credentials = [
            'email' => $email,
            'password' => Hash::make($password),
            'name' => 'test',
            'lang' => 'en'
        ];

        $service = $this->authService;

        $result = $service->registration($credentials);

        $this->assertNull($service->hasErrors());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user_id', $result);

        /**
         * Trying the same credentials, which must give an error
         */
        $result = $service->registration($credentials);

        $this->assertIsArray($service->hasErrors());
        $this->assertNull($result);
    }
}
