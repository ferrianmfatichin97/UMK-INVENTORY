<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengadaanBarangResource\Pages;
use App\Models\Divisi;
use App\Models\PengadaanBarang;
use App\Models\PengadaanBarangDetail;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PengadaanBarangResource extends Resource
{
    protected static ?string $model = PengadaanBarang::class;
    //protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pengadaan Barang';
    protected static ?string $navigationGroup = 'Manajemen Pengadaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Umum')
                ->columns(2)
                ->schema([
                    Select::make('divisi')
                        ->options(Divisi::all()->pluck('nama', 'nama'))
                        ->label('Divisi')
                        ->searchable()
                        ->required(),
                    TextInput::make('nota_dinas')->required(),

                    Select::make('urgensi')
                        ->required()
                        ->options([
                            'tinggi' => 'Tinggi',
                            'sedang' => 'Sedang',
                            'rendah' => 'Rendah',
                        ]),
                    DatePicker::make('tanggal_dibutuhkan')->required(),
                ]),

            TableRepeater::make('details')
                ->relationship('details')
                ->label('Daftar Barang')
                ->columns(2)
                ->columnSpanFull()
                ->headers([
                    Header::make('Nama Barang')->width('200px'),
                    Header::make('Jumlah')->width('100px'),
                    Header::make('Spesifikasi')->width('200px'),
                    Header::make('Link')->width('200px'),
                    Header::make('Catatan')->width('200px'),
                ])
                ->schema([
                    TextInput::make('nama_barang')->label('Nama Barang')->required(),
                    TextInput::make('jumlah')->numeric()->required()->minValue(1),
                    TextInput::make('harga')
    ->label('Harga')
    ->required()
    ->numeric()  // validasi dan casting sebagai angka
    ->prefix('Rp ')
    ->mask(fn ($mask) => $mask
        ->numeric()
        ->decimalPlaces(0)         // tanpa desimal
        ->thousandsSeparator('.')  // pemisah ribuan
        ->mapToDecimalSeparator([',']) // masukkan koma sebagai tens separator jika pengguna salah ketik
        ->normalizeZeros()         // ekuilibrium angka seperti "1.0" → "1"
    )
    ->stripCharacters(['Rp', '.', ',']), 
                    TextInput::make('spesifikasi')->nullable(),
                    TextInput::make('link')->label('Link Toko')->nullable(),
                    TextInput::make('catatan')->nullable(),
                ])
                ->defaultItems(1)
                ->reorderable(false),

            Forms\Components\Section::make('Lampiran')
                ->columns(3)
                ->schema([
                    FileUpload::make('lampiran_nodin')->label('Lampiran Nota Dinas')->nullable(),
                    FileUpload::make('lampiran_1')->nullable(),
                    FileUpload::make('lampiran_2')->nullable(),
                    Textarea::make('note')->label('Catatan Tambahan')->nullable()->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('divisi'),
                Tables\Columns\TextColumn::make('nota_dinas'),
                Tables\Columns\TextColumn::make('urgensi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'sedang' => 'warning',
                        'tinggi' => 'danger',
                        'rendah' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal_dibutuhkan')->date(),
                IconColumn::make('lampiran_nodin')
                    ->label('Nodin')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => Storage::url($record->lampiran_nodin))
                    ->openUrlInNewTab()
                    ->tooltip('Lihat Nota Dinas'),
                IconColumn::make('lampiran_1')
                    ->label('Lampiran 1')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => Storage::url($record->lampiran_1))
                    ->openUrlInNewTab()
                    ->tooltip('Lihat Lampiran 1'),
                IconColumn::make('lampiran_2')
                    ->label('Lampiran 2')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => Storage::url($record->lampiran_2))
                    ->openUrlInNewTab()
                    ->tooltip('Lihat Lampiran 2'),
                //Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'diproses' => 'warning',
                        'ditolak' => 'danger',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('divisi')
                    ->label('Filter Divisi')
                    ->options(Divisi::all()->pluck('nama', 'nama')),

                SelectFilter::make('status')
                    ->label('Status Pengadaan')
                    ->options([
                        'diproses' => 'Diproses',
                        'ditolak' => 'Ditolak',
                        'selesai'  => 'Selesai',
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengadaanBarangs::route('/'),
            'create' => Pages\CreatePengadaanBarang::route('/create'),
            'edit' => Pages\EditPengadaanBarang::route('/{record}/edit'),
            'view' => Pages\ViewPengadaanBarang::route('/{record}'),
        ];
    }
}
