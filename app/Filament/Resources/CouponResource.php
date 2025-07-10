<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Catalogos';
    protected static ?string $pluralModelLabel = 'Cupones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->required()
                    ->minLength(5)
                    ->maxLength(10),

                Textarea::make('description')
                    ->rows(3)
                    ->required(),

                Select::make('status')
                    ->required()
                    ->default('inactive')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                ]),

                TextInput::make('min_total')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                DatePicker::make('start_date')
                    ->required(),

                DatePicker::make('end_date')
                    ->required(),
                
                TextInput::make('discount_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->minValue(1)
                    ->maxValue(100)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('discount_percentage')
                    ->searchable()
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('min_total')
                    ->prefix('$')
                    ->numeric()
                    ->sortable(),
                
                SelectColumn::make('status')
                    ->searchable()
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                ]),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
