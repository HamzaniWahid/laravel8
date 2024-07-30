<?php

namespace App\Filament\Resources\InventoriesResource\Pages;

use App\Filament\Resources\InventoriesResource;
use App\Imports\InventoriesImport;
use Filament\Actions;

use Filament\Resources\Pages\ListRecords;


class ListInventories extends ListRecords
{
    protected static string $resource = InventoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            \EightyNine\ExcelImport\ExcelImportAction::make()
            ->slideOver()
            ->color("success")
            ->use(InventoriesImport::class)
        ];
    }
}
