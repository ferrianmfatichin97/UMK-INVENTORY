<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanUMKResource\Pages;
use App\Models\PengajuanUMK;
use App\Models\akun_master;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;

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
        $bulanTahun = date('m') . date('y');
        $lastPengajuan = PengajuanUMK::orderBy('nomor_pengajuan', 'desc')->first();
        $nomorUrut = $lastPengajuan ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1 : 1;
        $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
        $nomorPengajuan = "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";

        return $form->schema([
            Forms\Components\Section::make()
                ->columns(2)
                ->maxWidth('1/2')
                ->schema([
                    TextInput::make('nomor_pengajuan')
                        ->default($nomorPengajuan)
                        ->readOnly(),

                    Forms\Components\DatePicker::make('tanggal_pengajuan')
                        ->required()
                        ->date(),
                ]),

            TableRepeater::make('pengajuan_detail')
                ->relationship('pengajuan_detail')
                ->headers([
                    Header::make('Akun Master')->width('300px')->align(Alignment::Center),
                    Header::make('Kode Akun')->width('200px')->align(Alignment::Center),
                    Header::make('Nama Akun')->width('200px')->align(Alignment::Center),
                    Header::make('Jumlah')->width('200px')->align(Alignment::Center)->markAsRequired(),
                ])
                ->schema([
                    Hidden::make('nomor_pengajuan')->default($nomorPengajuan),

                    Forms\Components\Select::make('akun_master')
                        ->label('Akun Master')
                        ->options(
                            akun_master::all()->mapWithKeys(fn($akun) => [$akun->id => $akun->akun_bpr . ' - ' . $akun->nama_akun])
                        )
                        ->columnSpanFull()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $akun = akun_master::find($state);
                            $set('kode_akun', $akun?->akun_bpr);
                            $set('nama_akun', $akun?->nama_akun);
                        }),

                    TextInput::make('kode_akun')->hiddenLabel()->readOnly(),
                    TextInput::make('nama_akun')->hiddenLabel()->readOnly(),

                    TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->required()
                        ->live()
                        ->debounce(500)
                        ->reactive()
                        ->prefix('Rp ')
                        ->numeric()
                        ->inputMode('decimal')
                        ->placeholder('Input Nominal')
                        ->dehydrateStateUsing(fn($state) => preg_replace('/[^0-9]/', '', $state))
                        ->formatStateUsing(fn($state) => $state !== null ? number_format((int) $state, 0, ',', '.') : null)
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            $total = collect($get('pengajuan_detail'))->sum(fn($item) => (int) preg_replace('/[^0-9]/', '', $item['jumlah'] ?? 0));
                            $set('total_pengajuan', $total);
                            $set('sisa', 10000000 - $total);

                            if ($total > 10000000) {
                                Notification::make()
                                    ->title('Total Melebihi Batas')
                                    ->body('Maksimal jumlah yang bisa diajukan adalah Rp 10.000.000')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])
                ->defaultItems(1)
                ->reorderable(false)
                ->columns(3)
                ->columnSpan('full')
                ->deleteAction(
                    fn($action) => $action->after(fn($get, $set) => self::updateTotals($get, $set))
                ),

            Forms\Components\Section::make()
                ->columns(1)
                ->maxWidth('1/2')
                ->schema([
                    TextInput::make('total_pengajuan')
                        ->label('Total Pengajuan')
                        ->readOnly()
                        ->prefix('Rp ')
                        ->reactive()
                        ->live()
                        ->formatStateUsing(fn($state) => number_format((int) $state, 0, ',', '.')),

                    TextInput::make('sisa')
                        ->label('Sisa Kuota')
                        ->readOnly()
                        ->prefix('Rp ')
                        ->reactive()
                        ->live()
                        ->formatStateUsing(fn($state) => number_format((int) $state, 0, ',', '.')),
                ]),
        ]);
    }

    public static function updateTotals($get, $set): void
    {
        $invoiceItems = collect($get('pengajuan_detail'))->filter(fn($item) => !empty($item['jumlah']));
        $subtotal = $invoiceItems->sum(fn($item) => (int) preg_replace('/[^0-9]/', '', $item['jumlah'] ?? 0));

        $set('total_pengajuan', $subtotal);
        $set('sisa', 10000000 - $subtotal);
    }

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
            ->defaultSort('nomor_pengajuan', 'desc')
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
