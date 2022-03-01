<?php

namespace macropage\LaravelJsonSettings;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelJsonSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-json-settings')
            ->hasConfigFile();
    }

    public function bootingPackage(): void
    {
        $this->app->singleton(SettingsRepository::class, function ($app) {
            return new SettingsRepository(
                $app['config']->get('json-settings.path'),
                $app['config']->get('json-settings.namespace_path'),
            );
        });
    }
}
