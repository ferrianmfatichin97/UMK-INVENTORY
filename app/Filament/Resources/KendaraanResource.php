<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Kendaraan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KendaraanResource\Pages;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KendaraanResource\RelationManagers;
use App\Models\Data_kendaraan;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;

class KendaraanResource extends Resource
{
    protected static ?string $model = Data_kendaraan::class;
    protected static ?string $navigationGroup = 'Inventaris Kendaraan';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_kendaraan')
                    ->required(),
                Forms\Components\TextInput::make('merk')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('no_rangka')
                    ->required(),
                Forms\Components\TextInput::make('no_registrasi')
                    ->required(),
                Forms\Components\TextInput::make('no_bpkb')
                ->label('No BPKB')
                    ->required(),
                Forms\Components\TextInput::make('kantor_cabang')
                    ->required(),
                Forms\Components\DatePicker::make('jadwal_pajak')
                    ->required(),
                Forms\Components\TextInput::make('perusahaan_asuransi')
                    ->nullable(),
                Forms\Components\DatePicker::make('asuransi_mulai')
                    ->nullable(),
                Forms\Components\DatePicker::make('asuransi_akhir')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('merk')->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('type')->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('no_rangka')
                    ->label('No Rangka')
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('no_registrasi')->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('no_bpkb')
                    ->label('No BPKB')
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('kantor_cabang')->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('jadwal_pajak')
                    ->date()
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('perusahaan_asuransi')
                ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('asuransi_mulai')
                    ->date()
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('asuransi_akhir')
                    ->date()
                    ->alignment(Alignment::Center),
            ])
            ->filters([
                //Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('jenis_kendaraan')->label(' Jenis Kendaraan')->options([
                    'Mobil' => 'MOBIL',
                    'Motor' => 'MOTOR',
                ]),
            ],layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
