<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MereksResource\Pages;
use App\Filament\Resources\MereksResource\RelationManagers;
use App\Models\Merek;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MereksResource extends Resource
{
    protected static ?string $model = Merek::class;
    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Merek Barang';
    protected static ?string $slug = 'merek';
    protected static ?string $navigationGroup = 'Barang';

    protected static ?string $navigationBadgeTooltip = 'Total Merek';    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMereks::route('/'),
            'create' => Pages\CreateMereks::route('/create'),
            'view' => Pages\ViewMereks::route('/{record}'),
            'edit' => Pages\EditMereks::route('/{record}/edit'),
        ];
    }
}
