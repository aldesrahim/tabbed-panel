<?php

namespace Aldesrahim\TabbedPanel;

use Aldesrahim\TabbedPanel\Testing\TestsTabbedPanel;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Contracts\Foundation\Application;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TabbedPanelServiceProvider extends PackageServiceProvider
{
    public static string $name = 'tabbed-panel';

    public static string $viewNamespace = 'tabbed-panel';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('aldesrahim/tabbed-panel');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        // Testing
        Testable::mixin(new TestsTabbedPanel);

        $this->app->singleton(TabbedPanelManager::class, fn () => new TabbedPanelManager);

        $this->app->scoped(TabbedPanel::class, function (Application $app) {
            $manager = $app->make(TabbedPanelManager::class);
            $store = $manager->store();

            return new TabbedPanel($store);
        });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'aldesrahim/tabbed-panel';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('tabbed-panel', __DIR__ . '/../resources/dist/components/tabbed-panel.js'),
            // Css::make('tabbed-panel-styles', __DIR__ . '/../resources/dist/tabbed-panel.css'),
            // Js::make('tabbed-panel-scripts', __DIR__ . '/../resources/dist/tabbed-panel.js'),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_tabbed_panel_tabs_table',
        ];
    }
}
