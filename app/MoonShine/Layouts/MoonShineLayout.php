<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\CompactLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{
    Layout\Layout,
};
use App\MoonShine\Resources\UserResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\ScheduleResource;
use App\MoonShine\Resources\PerformanceResource;
use App\MoonShine\Resources\LocationResource;
use MoonShine\UI\Components\Layout\Head;
use MoonShine\UI\Components\Layout\Footer;
use MoonShine\UI\Components\Layout\Sidebar;
use MoonShine\UI\Components\Layout\Logo;
use MoonShine\UI\Components\Layout\Div;
use MoonShine\UI\Components\Layout\Burger;
use MoonShine\UI\Components\Layout\ThemeSwitcher;
use MoonShine\UI\Components\Layout\Menu;
use MoonShine\UI\Components\When;
use App\MoonShine\Resources\PerformanceKanbanResource;
use App\MoonShine\Resources\SectionResource;
use App\MoonShine\Resources\StatusResource;

final class MoonShineLayout extends CompactLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            // ...parent::menu(),
            MenuItem::make('Пользователи', UserResource::class),
            MenuItem::make('Расписание', ScheduleResource::class),
            MenuItem::make('Заявки', PerformanceResource::class),
            MenuItem::make('Аудитории', LocationResource::class),
            MenuItem::make('PerformanceKanbans', PerformanceKanbanResource::class),
            MenuItem::make('Sections', SectionResource::class),
            MenuItem::make('Statuses', StatusResource::class),
        ];
    }
    protected function getFooterComponent(): Footer
    {
        return parent::getFooterComponent()->menu([]);
    }

    protected function getSidebarComponent(): Sidebar
    {
        return Sidebar::make([
            Div::make([
                // Div::make([
                //     $this->getLogoComponent()->minimized(),
                // ])->class('menu-heading-logo'),

                Div::make([
                    ThemeSwitcher::make(),

                    Div::make([
                        Burger::make(),
                    ])->class('menu-heading-burger'),
                ])->class('menu-heading-actions'),
            ])->class('menu-heading'),

            Div::make([
                Menu::make(),
                // When::make(
                //     fn(): bool => $this->isAuthEnabled(),
                //     static fn(): array => [Profile::make(withBorder: true)],
                // ),
            ])->customAttributes([
                'class' => 'menu',
                ':class' => "asideMenuOpen && '_is-opened'",
            ]),
        ])->collapsed();
    }

    protected function getFooterCopyright(): string
    {
        return '';
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
