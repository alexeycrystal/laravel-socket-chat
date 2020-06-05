<?php

return [
    /**
     * Repositories
     */
    /*
     * Users
     */
    \App\Modules\User\Repositories\UserRepositoryContract::class => \App\Modules\User\Repositories\UserRepository::class,

    /**
     * Services
     */

    /*
     * Auth
     */
    \App\Modules\Auth\Services\JWTServiceContract::class => \App\Modules\Auth\Services\JWTService::class,
    \App\Modules\Auth\Services\AuthServiceContract::class => \App\Modules\Auth\Services\AuthService::class,

];
