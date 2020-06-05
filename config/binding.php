<?php

return [
    /**
     * Repositories
     */

    /*
     * Users
     */
    \App\Modules\User\Repositories\UserRepositoryContract::class => \App\Modules\User\Repositories\UserRepository::class,
    \App\Modules\User\Repositories\UserSettingsRepositoryContract::class => \App\Modules\User\Repositories\UserSettingsRepository::class,

    /**
     * Services
     */

    /*
     * Auth
     */
    \App\Modules\Auth\Services\JWTServiceContract::class => \App\Modules\Auth\Services\JWTService::class,

    /*
     * IP Services
     */
    \App\Services\IP\LocationIPServiceContract::class => \App\Services\IP\LocationIPService::class,
];
