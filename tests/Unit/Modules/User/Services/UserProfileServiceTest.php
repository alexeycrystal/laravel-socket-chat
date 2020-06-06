<?php


namespace Tests\Unit\Modules\User\Services;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Auth\Services\JWTServiceContract;
use App\Modules\User\Services\UserProfileServiceContract;
use Illuminate\Support\Facades\Hash;
use Tests\Unit\AbstractTest;

class UserProfileServiceTest extends AbstractTest
{
    protected AuthServiceContract $authService;
    protected UserProfileServiceContract $userProfileService;
    protected JWTServiceContract $jwtService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestUser([
            'email' => 'test@gmail.com',
            'password' => Hash::make('testtest'),
            'name' => 'test',
            'lang' => 'en'
        ]);

        $this->userProfileService = $this->app->make(UserProfileServiceContract::class);
        $this->jwtService = $this->app->make(JWTServiceContract::class);
        $this->authService = $this->app->make(AuthServiceContract::class);
    }

    public function testPasswordChange()
    {
        $this->authService
            ->setLoggedUser($this->createdUserId);

        $email = 'test@gmail.com';
        $password = 'newpassword';

        $result = $this->userProfileService
            ->changePassword($password);

        $this->assertNull($this->userProfileService->hasErrors());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('result', $result);

        $token = $this->jwtService
            ->createTokenByCredentials([
                'email' => $email,
                'password' => $password
            ]);

        $this->assertNull($this->jwtService->hasErrors());
        $this->assertIsString($token);
    }

    public function testGetUserProfileInfo()
    {
        $this->authService->setLoggedUser($this->createdUserId);

        $result = $this->userProfileService
            ->getUserProfileInfoByLoggedUser();

        $this->assertNull($this->userProfileService->hasErrors());
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('nickname', $result);
        $this->assertArrayHasKey('timezone', $result);
        $this->assertArrayHasKey('phone', $result);
        $this->assertArrayHasKey('lang', $result);
    }
}
