<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengadaanResource\Pages;
use App\Models\Divisi;
use App\Models\Pengadaan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FilterLayout;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Storage;

class PengadaanResource extends Resource
{
    protected static ?string $model = Pengadaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(fn() => auth()->id())
                    ->required(),
                Forms\Components\Select::make('divisi_id')
                    ->label('Divisi')
                    ->required()
                    ->options(Divisi::pluck('nama', 'id')->toArray())
                    ->searchable()
                    ->placeholder('Pilih Divisi'),
                Forms\Components\TextInput::make('nama_barang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nota_dinas')
                    ->label('Nota Dinas')
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('spesifikasi')
                    ->maxLength(255),
                Forms\Components\Textarea::make('alasan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('urgensi')
                    ->label('Urgensi')
                    ->options([
                        'tinggi' => 'Tinggi',
                        'sedang' => 'Sedang',
                        'rendah' => 'Rendah',
                    ])
                    ->required()
                    ->searchable(false)
                    ->placeholder('Pilih Urgensi'),
                Forms\Components\DatePicker::make('tanggal_dibutuhkan')
                    ->required(),
                FileUpload::make('lampiran_file')
                    ->label('Upload PDF')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->directory('documents')
                    ->storeFileNamesIn('original_filename')
                    ->downloadable()
                    ->preserveFilenames()
                    ->required(),
                Forms\Components\Textarea::make('link_toko_online')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nota_dinas')
                    ->label('Nota Dinas')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('divisi.nama')
                    ->label('Divisi')
                    ->sortable('divisi.nama'),
                TextColumn::make('nama_barang')
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable()
                    ->alignment('center'),
                TextColumn::make('spesifikasi')
                    ->searchable(),
                TextColumn::make('urgensi')
                    ->badge()
                    ->label('Urgensi')
                    ->color(fn(string $state): string => match ($state) {
                        'tinggi' => 'danger',
                        'sedang' => 'warning',
                        'rendah' => 'success',
                    }),
                TextColumn::make('tanggal_dibutuhkan')
                    ->label('Tanggal Dibutuhkan')
                    ->formatStateUsing(fn($record) => date('d M Y', strtotime($record->tanggal_dibutuhkan)) . ' (Sisa ' . floor((strtotime($record->tanggal_dibutuhkan) - time()) / 86400) . ' hari)')
                    ->sortable('tanggal_dibutuhkan'),

                IconColumn::make('lampiran_file')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => Storage::url($record->lampiran_file))
                    ->openUrlInNewTab()
                    ->tooltip('Lihat PDF'),
                    
                TextColumn::make('status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('divisi_id')
                    ->label('Divisi')
                    ->options(Divisi::pluck('nama', 'id')->toArray())
                    ->placeholder('Semua Divisi'),

                SelectFilter::make('urgensi')
                    ->label('Urgensi')
                    ->options([
                        'tinggi' => 'Tinggi',
                        'sedang' => 'Sedang',
                        'rendah' => 'Rendah',
                    ])
                    ->placeholder('Semua Urgensi'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->placeholder('Semua Status'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengadaans::route('/'),
            'create' => Pages\CreatePengadaan::route('/create'),
            'edit' => Pages\EditPengadaan::route('/{record}/edit'),
        ];
    }
}
