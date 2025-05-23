<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\ScheduleResource;
use App\MoonShine\Resources\ThesisResource;
use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\ThesisKanbanResource;
use App\MoonShine\Resources\SectionResource;
use App\MoonShine\Resources\StatusResource;
use App\MoonShine\Resources\StaticPageResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                UserResource::class,
                ScheduleResource::class,
                ThesisResource::class,
                LocationResource::class,
                ThesisKanbanResource::class,
                SectionResource::class,
                StatusResource::class,
                StaticPageResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }
}
