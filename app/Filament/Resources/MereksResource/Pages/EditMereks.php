<?php

namespace App\Filament\Resources\MereksResource\Pages;

use App\Filament\Resources\MereksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMereks extends EditRecord
{
    protected static string $resource = MereksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
