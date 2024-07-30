<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoriesResource\Pages;
use App\Filament\Resources\InventoriesResource\RelationManagers;
use App\Imports\ContentsImport;
use App\Models\Inventories;
use App\Models\Kategories;
use App\Models\Merek;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use YOS\FilamentExcel\Actions\Import;

class InventoriesResource extends Resource
{
    protected static ?string $model = Inventories::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Inventori';
    protected static ?string $navigationGroup = 'Barang';
    protected static ?string $slug = 'inventoriBarang';
    protected static ?string $title = 'Inventori';
    protected static ?string $navigationBadgeTooltip = 'Total Barang';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('nama')->label('Nama'),
                        Group::make([
                            Select::make('merek_id')->label('Merek')
                                ->options(Merek::all()->pluck('nama', 'id')),
                            Select::make('kategori_id')->label('Kategori')
                                ->options(Kategories::all()->pluck('nama', 'id')),
                        ]),
                    ]),
                Grid::make(4)
                    ->schema([
                        TextInput::make('jumlah')->label('Jumlah')
                            ->numeric()
                            ->integer(),
                        TextInput::make('hargaJual')->label('Harga Jual')
                            ->numeric(),
                        TextInput::make('hargaBeli')->label('Harga Beli')
                            ->numeric()
                            ->hidden(fn() => !in_array(Auth::user()->email, [
                                'hamzaniwahid321@gmail.com'
                            ])),
                        DatePicker::make('expired')->label('Expired')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->firstDayOfWeek(7),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama')->searchable(),
                TextColumn::make('merek.nama')->label('Merek')->searchable(),
                TextColumn::make('jumlah')->label('Jumlah')->searchable(),
                TextColumn::make('kategori.nama')->label('Kategori')->searchable(),
                TextColumn::make('expired')
                    ->label('Tanggal_Expired')
                    ->searchable()
                    ->badge()
                    ->color(function (string $state): string {
                        $date = Carbon::parse($state);
                        $now = now();

                        $daysRemaining = $date->diffInDays($now);
                        $monthsRemaining = $date->diffInMonths($now);
                        $yearsRemaining = $date->diffInYears($now);

                        \Log::info("State date: {$date->toDateString()}, Days remaining: {$daysRemaining}, Months remaining: {$monthsRemaining}, Years remaining: {$yearsRemaining}");

                        if ($daysRemaining < 30 && $monthsRemaining == 0 && $yearsRemaining == 0) {
                            return 'secondary'; // merah
                        } elseif ($yearsRemaining == 0 && $monthsRemaining < 1) {
                            return 'warning'; // kuning
                        } else {
                            return 'secondary'; // hijau
                        }
                        // if($monthsRemaining > 1){
                        //     return 'success';
                        // }
                    })
                    // ->formatStateUsing(function (string $state): string {
                    //     return Carbon::parse($state)->format('d-m-Y');
                    // })
                    ,
                TextColumn::make('hargaJual')->searchable()
                    ->money('Rp.')
                    ->label('Harga_Jual'),
                TextColumn::make('hargaBeli')->searchable()
                    ->money('Rp.')
                    ->label('Harga_Beli')
                    ->hidden(fn() => !in_array(Auth::user()->email, [
                        'hamzaniwahid321@gmail.com'
                    ])),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()->label('Export'),
                Tables\Actions\DeleteBulkAction::make(),
                // Tables\Actions\BulkActionGroup::make([
                // ]),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventories::route('/create'),
            'edit' => Pages\EditInventories::route('/{record}/edit'),
        ];
    }
}
