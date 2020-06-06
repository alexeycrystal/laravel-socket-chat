<?php


namespace Tests\Unit;


use App\Modules\Auth\Services\AuthServiceContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

abstract class AbstractTest extends TestCase
{
    use DatabaseMigrations;

    protected int $createdUserId;

    public function createTestUser(?array $payload = null): ?array
    {
        $payload = $payload ?? [
                'email' => 'test@gmail.com',
                'password' => 'testtest',
                'name' => 'test',
                'lang' => 'en'
            ];

        $authService = $this->app->make(AuthServiceContract::class);

        $data = $authService->registration($payload);

        if($data) {

            $this->createdUserId = $data['user_id'];

            return $data;
        }

        return null;
    }
}
