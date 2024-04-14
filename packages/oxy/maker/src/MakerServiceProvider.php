<?php

namespace Oxy\Maker;

use Illuminate\Support\ServiceProvider;
use Oxy\Maker\Console\MakeBuilder;
use Oxy\Maker\Console\MakeCollection;
use Oxy\Maker\Console\MakeComponents;
use Oxy\Maker\Console\MakeController;
use Oxy\Maker\Console\MakePermission;
use Oxy\Maker\Console\MakePermissionGroup;
use Oxy\Maker\Console\MakePolicy;
use Oxy\Maker\Console\MakeRequest;
use Oxy\Maker\Console\MakeResource;
use Oxy\Maker\Console\MakeRoute;
use Oxy\Maker\Console\MakeService;
use Oxy\Maker\Console\MakeTestPestController;

class MakerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeBuilder::class,
                MakeCollection::class,
                MakeResource::class,
                MakeComponents::class,
                MakePermission::class,
                MakePolicy::class,
                MakeService::class,
                MakeController::class,
                MakeRequest::class,
                MakeTestPestController::class,
                MakePermissionGroup::class,
                MakeRoute::class,
            ]);
        }
    }

    /**
     * Регистрация любых служб пакета.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/maker.php', 'maker'
        );
    }
}
