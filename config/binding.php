<?php

return [
    /**
     * Repositories
     */

    \App\Modules\User\Repositories\UserRepositoryContract::class => \App\Modules\User\Repositories\UserRepository::class,
    \App\Modules\User\Repositories\UserSettingsRepositoryContract::class => \App\Modules\User\Repositories\UserSettingsRepository::class,

    \App\Modules\User\Services\UserProfileServiceContract::class => \App\Modules\User\Services\UserProfileService::class,
    \App\Modules\User\Repositories\UserContactsRepositoryContract::class => \App\Modules\User\Repositories\UserContactRepository::class,

    /*
     * Chat
     */
    \App\Modules\Chat\Repositories\ChatRepositoryContract::class => \App\Modules\Chat\Repositories\ChatRepository::class,
    \App\Modules\Chat\Repositories\ChatUserRepositoryContract::class => \App\Modules\Chat\Repositories\ChatUserRepository::class,

    \App\Modules\Message\Repositories\MessageRepositoryContract::class => \App\Modules\Message\Repositories\MessageRepository::class,

    /**
     * Services
     */

    \App\Modules\Chat\Services\ChatServiceContract::class => \App\Modules\Chat\Services\ChatService::class,

    \App\Modules\Message\Services\MessageServiceContract::class => \App\Modules\Message\Services\MessageService::class,

    \App\Modules\User\Services\UserContactsServiceContract::class => \App\Modules\User\Services\UserContactsService::class,

    \App\Modules\Auth\Services\JWTServiceContract::class => \App\Modules\Auth\Services\JWTService::class,

    \App\Services\IP\LocationIPServiceContract::class => \App\Services\IP\LocationIPService::class,
];
