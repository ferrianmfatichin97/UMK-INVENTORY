<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalUMKResource\Pages;
use App\Filament\Resources\ApprovalUMKResource\RelationManagers;
use App\Models\ApprovalUMK;
use App\Models\PengajuanUMK;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\akun_master;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Support\Enums\Alignment;

class ApprovalUMKResource extends Resource
{
    protected static ?string $model = PengajuanUMK::class;
    protected static ?string $navigationParentItem = 'Notifications';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Approval UMK';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_pengajuan')
                    ->readOnly()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_pengajuan')
                    ->required()
                    ->date(),

                TableRepeater::make('pengajuan_detail')
                    ->relationship('pengajuan_detail')
                    ->headers([
                        Header::make('kode_akun')->width('80px'),
                        Header::make('nama_akun')->width('200px'),
                        Header::make('jumlah')->width('200px')->align(Alignment::Center)->markAsRequired(),
                        Header::make('keterangan')->width('150px'),
                    ])
                    ->schema([

                        Forms\Components\TextInput::make('nomor_pengajuan')
                            // ->hidden()
                            ->visible(false)
                            ->maxLength(255)
                            ->readOnly(),

                        Forms\Components\TextInput::make('kode_akun')
                            ->hiddenLabel()
                            ->live()
                            ->readOnly(),

                        Forms\Components\TextInput::make('nama_akun')
                            ->hiddenLabel()
                            ->live()
                            ->readOnly(),

                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->reactive()
                            ->prefix('Rp ')
                            ->numeric()
                            ->columnSpanFull()
                            ->minValue(0)
                            ->maxValue(10000000)
                            ->readOnly()
                            ->placeholder('Inpur Nominal'),

                        Forms\Components\TextInput::make('keterangan')
                            ->columnSpanFull()
                            ->readOnly(),
                    ])
                    ->defaultItems(1)
                    ->columnSpan('full'),

                Forms\Components\TextInput::make('total_pengajuan')
                    ->required()
                    ->reactive()
                    ->prefix('Rp ')
                    ->numeric()
                    ->readOnly()
                    ->columnSpanFull()
                    ->minValue(0)
                    ->maxValue(10000000)
                    ->placeholder('Input Nominal'),

                Forms\Components\TextInput::make('total_disetujui')
                   // ->default()
                    ->required()
                    ->reactive()
                    ->prefix('Rp ')
                    ->numeric()
                    //->readOnly()
                    ->columnSpanFull()
                    ->minValue(0)
                    ->maxValue(10000000),

                Forms\Components\ToggleButtons::make('status')
                    ->options([
                        'acc' => 'Approve',
                        'revisi' => 'Revisi'
                    ])
                    ->colors([
                        'revisi' => 'warning',
                        'acc' => 'success',
                    ])
                    ->icons([
                        'revisi' => 'heroicon-o-pencil',
                        'acc' => 'heroicon-o-check-circle',
                    ])
                    ->inline()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull()
                    ->autosize(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pengajuan'),
                Tables\Columns\TextColumn::make('tanggal_pengajuan')->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'waiting' => 'warning',
                        'acc' => 'success',
                        'revisi' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_pengajuan')
                    ->label('Total Pengajuan')
                    ->money('IDR', true)
                    ->prefix('Rp ')
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('total_disetujui')
                    ->label('Total Di Setujui')
                    ->money('IDR', true)
                    ->prefix('Rp ')
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
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
            'index' => Pages\ListApprovalUMKS::route('/'),
            'create' => Pages\CreateApprovalUMK::route('/create'),
            'edit' => Pages\EditApprovalUMK::route('/{record}/edit'),
        ];
    }
}
