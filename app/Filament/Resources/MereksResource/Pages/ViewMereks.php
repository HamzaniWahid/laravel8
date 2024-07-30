<?php

namespace App\Filament\Resources\MereksResource\Pages;

use App\Filament\Resources\MereksResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMereks extends ViewRecord
{
    protected static string $resource = MereksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
