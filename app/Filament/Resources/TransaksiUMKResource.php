<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\akun_master;
use App\Models\PengajuanUMK;
use App\Models\TransaksiUMK;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiUMKResource\Pages;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

class TransaksiUMKResource extends Resource
{
    protected static ?string $model = TransaksiUMK::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // $data = PengajuanUMK::all()->pluck('nomor_pengajuan', 'id'),
                // dd($data),
                Fieldset::make('Form Input Transaksi UMK')
                    ->schema([
                        Forms\Components\Select::make('no_pengajuan')
                            ->label('Nomor Pengajuan')
                            ->options(PengajuanUMK::all()->pluck('nomor_pengajuan', 'nomor_pengajuan'))
                            //->options(PengajuanUMK::all()->pluck('nomor_pengajuan', 'id')->toArray())
                            ->required()
                            ->columnSpanFull()
                            ->afterStateUpdated(function ($state) {
                                Log::info('Selected no_pengajuan: ' . $state);
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
                                    $set('akun_bpr', $akun->akun_bpr);
                                    $set('nama_akun', $akun->nama_akun);
                                } else {
                                    $set('akun_bpr', null);
                                    $set('nama_akun', null);
                                }
                            }),

                        Forms\Components\TextInput::make('akun_bpr')
                            ->readOnly(),

                        Forms\Components\TextInput::make('nama_akun')
                            ->readOnly(),

                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->default(now())
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (is_null($state)) {
                                    $set('tanggal', now());
                                }
                            }),

                        Forms\Components\TextInput::make('qty')
                            ->placeholder('Boleh dikosongkan')
                            ->maxLength(255),

                        Forms\Components\Select::make('satuan')
                            ->searchable()
                            ->placeholder('Boleh dikosongkan')
                            ->options([
                                'pcs' => 'PCS',
                                'lusin' => 'Lusin',
                                'kodi' => 'Kodi',
                                'gross' => 'Gross',
                                'rim' => 'Rim',
                                'set' => 'SET',
                            ])
                            ->live()
                            ->native(false)
                            ->reactive()
                            ->label('Satuan'),

                        Forms\Components\TextInput::make('nominal')
                            ->required()
                            ->reactive()
                            ->prefix('Rp ')
                            ->numeric(),

                        Forms\Components\Textarea::make('keterangan')
                            ->required()
                            ->columnSpanFull(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('no_pengajuan')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('akun_bpr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_akun')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR', true)
                    ->prefix('Rp ')
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTransaksiUMKS::route('/'),
            'create' => Pages\CreateTransaksiUMK::route('/create'),
            'edit' => Pages\EditTransaksiUMK::route('/{record}/edit'),
        ];
    }
}
