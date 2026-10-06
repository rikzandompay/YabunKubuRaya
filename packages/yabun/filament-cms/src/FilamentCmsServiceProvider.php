<?php

namespace Yabun\FilamentCms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-cms')
            ->hasConfigFile()
            ->hasViews();
    }
}
