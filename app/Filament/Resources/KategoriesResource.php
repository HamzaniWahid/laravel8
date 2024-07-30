<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriesResource\Pages;
use App\Filament\Resources\KategoriesResource\RelationManagers;
use App\Models\Kategories;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriesResource extends Resource
{
    protected static ?string $model = Kategories::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Kategori Barang';
    protected static ?string $slug = 'kategori';
    protected static ?string $navigationGroup = 'Barang';

    protected static ?string $navigationBadgeTooltip = 'Total Kategori';    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategories::route('/'),
            'create' => Pages\CreateKategories::route('/create'),
            'edit' => Pages\EditKategories::route('/{record}/edit'),
        ];
    }
}
