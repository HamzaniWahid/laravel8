<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Inventories;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Validator;
// use Illuminate\Support\Facades\Validator;
use Closure;



class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $slug = 'transaksi';
    protected static ?string $navigationGroup = 'Penjualan';

    protected static ?string $navigationBadgeTooltip = 'Total Transaksi';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                BelongsToSelect::make('user_id') // Using BelongsToSelect for clarity
                    ->label('Nama Pengguna')
                    ->required()
                    ->options(User::all()->pluck('name', 'id')),

                Select::make('metode_pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        'qrcode' => 'QRcode',
                    ])
                    ->default('cash')
                    ->translateLabel()
                    ->required(),

                Select::make('inventory_id')
                    ->options(Inventories::all()->mapWithKeys(function ($inventory) {
                        return [$inventory->id => $inventory->nama];
                    }))
                    ->label('Inventaris')
                    ->reactive() // For real-time updates
                    ->afterStateUpdated(function ($state, callable $set) {
                        $inventory = Inventories::find($state);
                        $set('harga_jual', $inventory->hargaJual);
                        $set('stok', $inventory->jumlah);
                    }),

                TextInput::make('stok')
                    ->numeric()
                    ->disabled(),
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $hargaJual = $get('harga_jual');
                        $totalHarga = $state * $hargaJual;
                        $set('total_harga', $totalHarga);
                    })
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            $stokBarang = $get('stok_barang');
                            if ($value > $stokBarang) {
                                $fail("Tidak boleh melebihi Stok/Stok tidak cukup");
                            }
                        },
                    ])
                    ->validationAttribute('Quantity')
                    // ->validationMessages([
                    //     'exceed' => 'The :attribute cannot exceed the available stock.',
                    // ]),
                    ,
                TextInput::make('harga_jual')
                    ->numeric(),

                TextInput::make('total_harga')
                    ->translateLabel()
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventory.nama')
                    ->label('Nama Barang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventory.hargaJual')
                    ->label('Harga Jual')
                    ->money('Rp.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->money('Rp.')
                    ->sortable(),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
