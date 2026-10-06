<?php

namespace App\Filament\Resources\Gallery\GalleryPhotoResource\Pages;

use App\Filament\Resources\Gallery\GalleryPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGalleryPhoto extends EditRecord
{
    protected static string $resource = GalleryPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
