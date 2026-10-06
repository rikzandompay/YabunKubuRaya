<?php

namespace Yabun\FilamentCms;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentCmsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-cms';
    }

    public function register(Panel $panel): void
    {
        // ponytail: register resources/pages/widgets here as they are created
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
