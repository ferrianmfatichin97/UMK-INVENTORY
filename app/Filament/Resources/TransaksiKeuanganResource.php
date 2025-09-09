<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiKeuanganResource\Pages;
use App\Models\TransaksiKeuangan;
use App\Models\akun_master;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Carbon;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\RawJs;

class TransaksiKeuanganResource extends Resource
{
    protected static ?string $model = TransaksiKeuangan::class;

    // protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?string $navigationLabel = 'Transaksi Keuangan';
    protected static ?string $slug = 'transaksi-keuangan';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('akun_master')
                    ->label('Kode Akun (COA)')
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

                Forms\Components\TextInput::make('kode_akun')
                    ->hiddenLabel()
                    ->readOnly()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('nama_akun')
                    ->hiddenLabel()
                    ->readOnly()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah')
                    ->required()
                    ->prefix('Rp ')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(','),

                Forms\Components\DatePicker::make('trans_date')
                    ->label('Tanggal Transaksi')
                    ->default(now())
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Keterangan')
                    ->rows(2),

                Forms\Components\Checkbox::make('is_recurring')
                    ->label('Jadikan Transaksi Rutin')
                    ->reactive(),

                Forms\Components\Select::make('status')
                    ->label('Status Transaksi')
                    ->options([
                        'posting' => 'Posting',
                        'delete' => 'Delete',
                    ])
                    ->default('posting')
                    ->visible(fn() => \Illuminate\Support\Facades\Auth::user()?->roles === 'admin'),

                Forms\Components\Section::make('Pengaturan Recurring')
                    ->visible(fn($get) => $get('is_recurring'))
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->default(now()),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Berakhir')
                            ->nullable(),

                        Forms\Components\Select::make('day_of_month')
                            ->label('Hari Setiap Bulan')
                            ->options(collect(range(1, 28))->mapWithKeys(fn($v) => [$v => $v])->toArray())
                            ->default(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trans_id')->label('ID Transaksi')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('kode_akun')->label('COA'),
                Tables\Columns\TextColumn::make('amount')->money('IDR'),
                Tables\Columns\TextColumn::make('trans_date')->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (string $state) {
                        return $state === 'posting' ? 'Aktif' : 'Delete';
                    })
                    ->color(function (string $state) {
                        return $state === 'posting' ? 'success' : 'danger';
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => \Illuminate\Support\Facades\Auth::user()?->roles === 'admin'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kode_akun')
                    ->label('Filter COA')
                    ->searchable()
                    ->options(
                        akun_master::all()->mapWithKeys(fn($akun) => [
                            $akun->akun_bpr => $akun->akun_bpr . ' - ' . $akun->nama_akun
                        ])
                    ),

                Tables\Filters\SelectFilter::make('source')
                    ->label('Filter Divisi')
                    ->options([
                        'umum' => 'Umum',
                        'bisnis' => 'Bisnis',
                        'akunting' => 'Akunting',
                    ]),

                Tables\Filters\Filter::make('trans_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Start')
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('until')
                            ->label('End')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('trans_date', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('trans_date', '<=', $date));
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)

            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksiKeuangans::route('/'),
            'create' => Pages\CreateTransaksiKeuangan::route('/create'),
            'edit' => Pages\EditTransaksiKeuangan::route('/{record}/edit'),
        ];
    }
}
