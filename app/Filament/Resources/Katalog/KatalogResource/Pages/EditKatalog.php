<?php

namespace App\Filament\Resources\Katalog\KatalogResource\Pages;

use App\Filament\Resources\Katalog\KatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKatalog extends EditRecord
{
    protected static string $resource = KatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
