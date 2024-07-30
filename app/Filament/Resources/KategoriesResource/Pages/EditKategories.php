<?php

namespace App\Filament\Resources\KategoriesResource\Pages;

use App\Filament\Resources\KategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategories extends EditRecord
{
    protected static string $resource = KategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
