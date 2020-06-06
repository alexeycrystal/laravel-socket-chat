<?php


namespace Tests\Unit;


use App\Modules\Auth\Services\AuthServiceContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

abstract class AbstractTest extends TestCase
{
    use DatabaseMigrations;

    protected int $createdUserId;

    public function createTestUser(?array $payload = null): void
    {
        $payload = $payload ?? [
                'email' => 'test@gmail.com',
                'password' => bcrypt('testtest'),
                'name' => 'test',
                'lang' => 'en'
            ];

        $authService = $this->app->make(AuthServiceContract::class);

        $data = $authService->registration($payload);

        $this->createdUserId = $data['data']['user_id'];
    }
}
