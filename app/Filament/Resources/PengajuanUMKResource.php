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
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Numeric;
use Illuminate\Validation\Rules\Max;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Infolists\Components\TextEntry;

class PengajuanUMKResource extends Resource
{
    protected static ?string $model = PengajuanUMK::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pengajuan UMK';

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

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->maxWidth('1/2')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_pengajuan')
                            ->default($nomorPengajuan)
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tanggal_pengajuan')
                            ->required()
                            ->date(),
                    ]),

                TableRepeater::make('pengajuan_detail')
                    ->relationship('pengajuan_detail')
                    ->headers([
                        // Header::make('No Pengajuan')->width('250px')
                        //     ->align(Alignment::Center),
                        Header::make('Akun Master')->width('300px')
                            ->align(Alignment::Center),
                        Header::make('Kode Akun')->width('200px')
                            ->align(Alignment::Center),
                        Header::make('Nama Akun')->width('200px')
                            ->align(Alignment::Center),
                        Header::make('Jumlah')->width('200px')
                            ->align(Alignment::Center)->markAsRequired(),
                        // Header::make('Keterangan')->width('200px')
                        //     ->align(Alignment::Center),
                    ])
                    ->schema([
                        Hidden::make('nomor_pengajuan')
                            ->default(function ($get) {
                                $bulanTahun = date('m') . date('y');
                                $lastPengajuan = PengajuanUMK::orderBy('id', 'desc')->first();
                                $nomorUrut = $lastPengajuan ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1 : 1;
                                $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
                                return "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";
                            }),
                        Forms\Components\Select::make('akun_master')
                            ->label('Akun Master')
                            ->options(
                                akun_master::all()->mapWithKeys(function ($akun) {
                                    return [$akun->id => $akun->akun_bpr . ' - ' . $akun->nama_akun];
                                })
                            )
                            ->columnSpanFull()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $akun = akun_master::find($state);
                                if ($akun) {
                                    $set('kode_akun', $akun->akun_bpr);
                                    $set('nama_akun', $akun->nama_akun);
                                } else {
                                    $set('kode_akun', null);
                                    $set('nama_akun', null);
                                }
                            }),
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
                            // ->money('IDR', true)
                            ->numeric()
                            ->columnSpanFull()
                            ->minValue(0)
                            ->maxValue(10000000)
                            ->inputMode('decimal')
                            ->rules([
                                // 'numeric',
                                // 'max:10000000',
                                // 'min:0',
                                function ($get) {
                                    return function ($attribute, $value, $fail) use ($get) {
                                        $total = collect($get('pengajuan_detail'))->sum(fn($item) => $item['jumlah'] ?? 0);
                                        if ($total > 10000000) {
                                            $fail('Total pengajuan tidak boleh lebih dari Rp 10.000.000');
                                        }
                                    };
                                },
                            ])
                            ->validationMessages([
                                'max' => 'Jumlah Maksimal yang bisa diajukan hanya Rp 10.000.000',

                            ])
                            ->placeholder('Input Nominal')
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $total = collect($get('pengajuan_detail'))->sum(fn($item) => $item['jumlah'] ?? 0);
                                $set('total_pengajuan', $total);
                                self::updateTotals($get, $set);

                                if ($total >= 10000000) {
                                    Notification::make()
                                        ->title('Total Terpenuhi')
                                        ->success()
                                        ->body('Maksimal jumlah yang bisa di ajukan hanya Rp 10.000.0000')
                                        ->send();
                                }
                            }),
                        // Forms\Components\TextArea::make('keterangan')
                        //     ->columnSpanFull(),
                    ])
                    ->defaultItems(1)
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        self::updateTotals($get, $set);
                    })
                    ->deleteAction(fn($action) => $action->after(fn($get, $set) => self::updateTotals($get, $set)))
                    ->reorderable(false)
                    ->columns(3)
                    ->columnSpan('full'),

                Forms\Components\Section::make()
                    ->columns(1)
                    ->maxWidth('1/2')
                    ->schema([
                        Forms\Components\TextInput::make('total_pengajuan')
                            ->label('Total Pengajuan')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rp ')
                            ->minValue(10000000)
                            ->maxValue(10000000)
                            ->validationMessages([
                                'max' => 'Maksimal jumlah yang bisa diajukan hanya Rp 10.000.000',
                                'min' => 'Jumlah yang diajukan Harus Rp 10.000.000',
                            ])
                            ->afterStateHydrated(fn($get, $set) => self::updateTotals($get, $set)),
                        Forms\Components\TextInput::make('sisa')
                            ->label('Sisa Kuota')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rp')
                    ]),
            ]);
    }

    // public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             \Filament\Infolists\Components\Section::make('PDF Viewer')
    //                 ->description('Prevent the PDF from being downloaded')
    //                 ->collapsible()
    //                 ->schema([
    //                     PdfViewerEntry::make('file')
    //                         ->label('View the PDF')
    //                         ->minHeight('40svh')
    //                         ->fileUrl(Storage::url('LPJWB.pdf'))
    //                         ->columnSpanFull(),
    //                 ]),
    //         ]);
    // }

    public static function updateTotals($get, $set): void
    {
        $invoiceItems = collect($get('pengajuan_detail'))->filter(fn($item) => !empty($item['jumlah']));
        $subtotal = $invoiceItems->sum('jumlah');

        $set('total_pengajuan', number_format($subtotal, 2, '.', ''));
        $set('sisa', number_format(10000000 - $subtotal, 2, '.', ''));
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
                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                // Tables\Columns\TextColumn::make('status')
                //     ->badge()
                //     ->color(fn(string $state): string => match ($state) {
                //         'waiting' => 'warning',
                //         'acc' => 'success',
                //         'revisi' => 'danger',
                //     }),
            ])
            ->defaultSort('nomor_pengajuan', 'desc')
            ->filters([

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
            // Define relations if needed
        ];
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
