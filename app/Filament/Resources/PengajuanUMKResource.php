<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanUMKResource\Pages;
use App\Models\akun_master;
use App\Models\PengajuanUMK;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
// use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Field;
use Filament\Support\RawJs;

class PengajuanUMKResource extends Resource
{
    protected static ?string $model = PengajuanUMK::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Pengajuan UMK';
    protected static ?string $navigationGroup = 'Pengajuan Uang Muka';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Total Pengajuan';
    }



    public static function form(Form $form): Form
    {
        $bulanTahun = date('my');

        $lastPengajuan = PengajuanUMK::where('nomor_pengajuan', 'like', "%/{$bulanTahun}")
            ->orderBy('nomor_pengajuan', 'desc')
            ->first();

        $nomorUrut = $lastPengajuan
            ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1
            : 1;

        $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);

        $nomorPengajuan = "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";

        return $form->schema([
            Forms\Components\Section::make('Informasi Pengajuan')
                ->columns(2)
                ->maxWidth('7xl')
                ->schema([
                    TextInput::make('nomor_pengajuan')
                        ->label('Nomor Pengajuan')
                        ->default($nomorPengajuan)
                        ->readOnly(),

                    Forms\Components\DatePicker::make('tanggal_pengajuan')
                        ->label('Tanggal Pengajuan')
                        ->required()
                        ->date(),
                ]),

            Forms\Components\Section::make('Pengajuan Detail')
                ->maxWidth('7xl')
                ->schema([
                    TableRepeater::make('pengajuan_detail')
                        ->relationship('pengajuan_detail')
                        ->columnSpanFull()
                        ->headers([
                            Header::make('Akun Master')->width('300px'),
                            Header::make('Kode Akun')->width('200px')->align(Alignment::Center),
                            Header::make('Nama Akun')->width('200px')->align(Alignment::Center),
                            Header::make('Jumlah')->width('200px')->align(Alignment::Center)->markAsRequired(),
                        ])
                        ->schema([
                            Hidden::make('nomor_pengajuan')->default($nomorPengajuan),

                            Select::make('akun_master')
                                ->label('Akun Master')
                                ->options(
                                    akun_master::all()->mapWithKeys(fn($akun) => [
                                        $akun->id => $akun->akun_bpr . ' - ' . $akun->nama_akun
                                    ])
                                )
                                ->searchable()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $akun = akun_master::find($state);
                                    $set('kode_akun', $akun?->akun_bpr);
                                    $set('nama_akun', $akun?->nama_akun);
                                })
                                ->columnSpan(2),

                            TextInput::make('kode_akun')
                                ->hiddenLabel()
                                ->readOnly()
                                ->columnSpan(1),

                            TextInput::make('nama_akun')
                                ->hiddenLabel()
                                ->readOnly()
                                ->columnSpan(1),

                            TextInput::make('jumlah')
                                ->label('Jumlah')
                                ->required()
                                ->prefix('Rp ')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->columnSpan(2),
                        ])
                        ->columns(6)
                        ->defaultItems(1)
                        ->reorderable(false),
                ]),

            // Forms\Components\Section::make('Total & Sisa Kuota')
            //     ->columns(2)
            //     ->maxWidth('7xl')
            //     ->schema([
            //         TextInput::make('total_pengajuan')
            //             ->label('Total Pengajuan')
            //             ->readOnly()
            //             ->prefix('Rp ')
            //             ->formatStateUsing(fn($state) => number_format((int) $state, 0, ',', '.')),

            //         TextInput::make('sisa')
            //             ->label('Sisa Kuota')
            //             ->readOnly()
            //             ->prefix('Rp ')
            //             ->formatStateUsing(fn($state) => number_format((int) $state, 0, ',', '.')),
            //     ]),
        ]);
    }

    // public static function updateTotals(Get $get, Set $set): void
    // {
    //     $items = collect($get('pengajuan_detail'))
    //         ->filter(fn($item) => !empty($item['jumlah']));

    //     $total = $items->sum(fn($item) => (int) preg_replace('/[^0-9]/', '', $item['jumlah'] ?? 0));

    //     $set('total_pengajuan', $total);
    //     $set('sisa', 10000000 - $total);

    //     if ($total > 10000000) {
    //         Notification::make()
    //             ->title('Total Melebihi Batas')
    //             ->body('Maksimal jumlah yang bisa diajukan adalah Rp 10.000.000')
    //             ->danger()
    //             ->send();
    //     }
    // }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pengajuan'),
                Tables\Columns\TextColumn::make('tanggal_pengajuan')->date(),
                Tables\Columns\TextColumn::make('total_pengajuan')
                    ->label('Total Pengajuan')
                    ->money('IDR', true)
                    ->prefix('Rp ')
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
            ])
            ->defaultSort('tanggal_pengajuan', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail')->icon('heroicon-o-eye'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanUMKS::route('/'),
            'create' => Pages\CreatePengajuanUMK::route('/create'),
            'edit' => Pages\EditPengajuanUMK::route('/{record}/edit'),
        ];
    }
}
