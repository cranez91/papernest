<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Fieldset;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('business_name')
                    ->label('Name')
                    ->required()
                    ->maxLength(80),

                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(5)
                    ->maxLength(200),

                TextInput::make('location')
                    ->label('Location')
                    ->required()
                    ->maxLength(100),

                TextInput::make('whatsapp_contact')
                    ->label('Whatsapp')
                    ->prefix('+52')
                    ->length(10)
                    ->numeric()
                    ->required(),

                TextInput::make('latitude')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),

                TextInput::make('longitude')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),
                
                Fieldset::make('Horarios de atención')->schema([
                    Grid::make(2)->schema([
                        TimePicker::make('business_hours.monday.from')
                            ->label('Lunes - Desde'),
                        TimePicker::make('business_hours.monday.to')
                            ->label('Lunes - Hasta'),

                        TimePicker::make('business_hours.tuesday.from')
                            ->label('Martes - Desde'),
                        TimePicker::make('business_hours.tuesday.to')
                            ->label('Martes - Hasta'),

                        TimePicker::make('business_hours.wednesday.from')
                            ->label('Miércoles - Desde'),
                        TimePicker::make('business_hours.wednesday.to')
                            ->label('Miércoles - Hasta'),

                        TimePicker::make('business_hours.thursday.from')
                            ->label('Jueves - Desde'),
                        TimePicker::make('business_hours.thursday.to')
                            ->label('Jueves - Hasta'),

                        TimePicker::make('business_hours.friday.from')
                            ->label('Viernes - Desde'),
                        TimePicker::make('business_hours.friday.to')
                            ->label('Viernes - Hasta'),

                        TimePicker::make('business_hours.saturday.from')
                            ->label('Sábado - Desde'),
                        TimePicker::make('business_hours.saturday.to')
                            ->label('Sábado - Hasta'),

                        TimePicker::make('business_hours.sunday.from')
                            ->label('Domingo - Desde'),
                        TimePicker::make('business_hours.sunday.to')
                            ->label('Domingo - Hasta'),
                    ]),
                ])
                ->columns(1)
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business_name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('whatsapp_contact')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'view' => Pages\ViewSetting::route('/{record}'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
