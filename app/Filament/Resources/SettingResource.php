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
                    ->label('Nombre del Negocio')
                    ->required()
                    ->maxLength(80),

                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->rows(5)
                    ->maxLength(200),

                TextInput::make('location')
                    ->label('Ubicación')
                    ->required()
                    ->maxLength(100),

                TextInput::make('whatsapp_contact')
                    ->label('Whatsapp')
                    ->prefix('+52')
                    ->length(10)
                    ->numeric()
                    ->required(),
                
                TextInput::make('facebook_id')
                    ->label('Facebook Page ID')
                    ->maxLength(150)
                    ->required(),
                
                TextInput::make('maps_link')
                    ->label('Google Maps Link')
                    ->maxLength(250)
                    ->required(),
                
                TextInput::make('shipping_price')
                    ->label('Costo de Envío')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),
                
                Fieldset::make('Horarios de atención')->schema([
                    Grid::make(2)->schema([
                        TimePicker::make('business_hours.lunes.from')
                            ->label('Lunes - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.lunes.to')
                            ->label('Lunes - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),

                        TimePicker::make('business_hours.martes.from')
                            ->label('Martes - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.martes.to')
                            ->label('Martes - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),

                        TimePicker::make('business_hours.miercoles.from')
                            ->label('Miércoles - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.miercoles.to')
                            ->label('Miércoles - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),

                        TimePicker::make('business_hours.jueves.from')
                            ->label('Jueves - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.jueves.to')
                            ->label('Jueves - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),

                        TimePicker::make('business_hours.viernes.from')
                            ->label('Viernes - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.viernes.to')
                            ->label('Viernes - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),

                        TimePicker::make('business_hours.sabado.from')
                            ->label('Sábado - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.sabado.to')
                            ->label('Sábado - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),

                        TimePicker::make('business_hours.domingo.from')
                            ->label('Domingo - Desde')
                            ->seconds(false)
                            ->format('h:i A'),
                        TimePicker::make('business_hours.domingo.to')
                            ->label('Domingo - Hasta')
                            ->seconds(false)
                            ->format('h:i A'),
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

                TextColumn::make('shipping_price')
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
