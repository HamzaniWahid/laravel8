<?php

namespace App\Filament\Resources\MereksResource\Pages;

use App\Filament\Resources\MereksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMereks extends ListRecords
{
    protected static string $resource = MereksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
