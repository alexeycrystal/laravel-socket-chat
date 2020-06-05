<?php

namespace App\Providers;

use App\Generics\Repositories\RepositoryManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('RepositoryManager', RepositoryManager::class);

        $bindings = config('binding');

        foreach ($bindings as $abstract=>$concrete)
            $this->app->bind($abstract, $concrete);
    }
}
