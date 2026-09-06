<?php

namespace JeffersonGoncalves\Ahrefs;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AhrefsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ahrefs')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('ahrefs', fn () => new Ahrefs);
    }
}
