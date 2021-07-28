<?php

namespace Wymanliu01\BackpackAddons;

use Illuminate\Support\ServiceProvider;

/**
 * Class BackpackAddonsServiceProvider
 * @package Wymanliu01\BackpackAddons
 */
class BackpackAddonsServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        //
    }

    /**
     *
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
