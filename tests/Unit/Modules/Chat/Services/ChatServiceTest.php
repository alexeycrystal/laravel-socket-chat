<?php


namespace Tests\Unit\Modules\Chat\Services;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Services\ChatServiceContract;
use Illuminate\Support\Facades\Hash;
use Tests\Unit\AbstractTest;

class ChatServiceTest extends AbstractTest
{
    protected AuthServiceContract $authService;
    protected ChatServiceContract $chatService;
    protected int $firstUserId;
    protected int $secondUserId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->firstUserId = $this->createTestUser([
            'email' => 'test1@gmail.com',
            'password' => Hash::make('testtest'),
            'name' => 'test',
            'lang' => 'en'
        ])['user_id'];

        $this->secondUserId = $this->createTestUser([
            'email' => 'test2@gmail.com',
            'password' => Hash::make('testtest'),
            'name' => 'test',
            'lang' => 'en'
        ])['user_id'];

        $this->authService = $this->app->make(AuthServiceContract::class);
        $this->chatService = $this->app->make(ChatServiceContract::class);
    }

    public function testCreateChat()
    {
        $this->authService
            ->setLoggedUser($this->firstUserId);

        $service = $this->chatService;

        $result = $service->createChatIfNotExists([$this->secondUserId]);

        $this->assertNull($service->hasErrors());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('chat_id', $result);
        $this->assertArrayHasKey('chat_already_exists', $result);

        $result = $service->createChatIfNotExists([$this->secondUserId]);

        $this->assertNull($service->hasErrors());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('chat_id', $result);
        $this->assertArrayHasKey('chat_already_exists', $result);
    }
}
