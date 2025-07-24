<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReminderSettingResource\Pages;
use App\Filament\Resources\ReminderSettingResource\RelationManagers;
use App\Models\ReminderSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReminderSettingResource extends Resource
{
    protected static ?string $model = ReminderSetting::class;

    protected static ?string $navigationGroup = 'Inventaris Kendaraan';
    protected static ?string $navigationLabel = 'Setting Pengingat';
    protected static ?string $navigationIcon = 'heroicon-m-truck';
    //protected static ?string $slug = 'inventaris-kendaraan/kendaraan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {

        return $form->schema([
            TimePicker::make('reminder_time')
                ->label('Jam Pengiriman Reminder')
                ->required(),

            TextInput::make('days_before')
                ->label('Hari Sebelum Jatuh Tempo (pisahkan dengan koma, contoh: 30,20,10)')
                ->required()
                ->afterStateHydrated(function ($component, $state) {
                    if (is_array($state)) {
                        $component->state(implode(',', $state));
                    } elseif (is_string($state)) {
                        $component->state($state);
                    } else {
                        $component->state('');
                    }
                })
                ->dehydrateStateUsing(fn($state) => array_map('intval', explode(',', $state))),

            Toggle::make('send_email')
                ->label('Aktifkan Pengiriman Email'),

            Toggle::make('send_whatsapp')
                ->label('Aktifkan Pengiriman WhatsApp'),
        ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('reminder_time')
                ->label('Jam Reminder')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('days_before')
                ->label('Hari Sebelum Jatuh Tempo')
                ->formatStateUsing(function ($state) {
                    if (is_array($state)) {
                        return implode(', ', $state);
                    }
                    return $state;
                }),

            Tables\Columns\IconColumn::make('send_email')
                ->label('Email')
                ->boolean(),

            Tables\Columns\IconColumn::make('send_whatsapp')
                ->label('WhatsApp')
                ->boolean(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Terakhir Diperbarui')
                ->dateTime('d M Y H:i'),
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
            'index' => Pages\ListReminderSettings::route('/'),
            'create' => Pages\CreateReminderSetting::route('/create'),
            'edit' => Pages\EditReminderSetting::route('/{record}/edit'),
        ];
    }
}
