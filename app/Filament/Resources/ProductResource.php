<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\TagsInput;
use App\Models\Tag;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Catalogos';
    protected static ?string $pluralModelLabel = 'Productos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->readOnly()
                    ->unique(ignoreRecord: true),

                TextInput::make('sku')
                    ->unique(ignoreRecord: true)
                    ->readOnly()
                    ->nullable(),

                TextInput::make('brand')
                    ->maxLength(255)
                    ->nullable(),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),

                TextInput::make('cost')
                    ->numeric()
                    ->step(0.01)
                    ->required(),

                TextInput::make('price')
                    ->numeric()
                    ->step(0.01)
                    ->required(),

                TextInput::make('stock')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),

                Select::make('status')
                    ->options([
                        'paused' => 'Paused',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required(),
                
                Select::make('tags')
                    ->label('Etiquetas')
                    ->relationship('tags', 'name') // ← Aquí se usa la relación many-to-many
                    ->multiple()
                    ->preload() // precarga las opciones
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre de etiqueta')
                            ->required(),
                    ])
                    ->createOptionAction(function (\Filament\Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Crear nueva etiqueta')
                            ->modalSubmitActionLabel('Crear');
                    }),

                FileUpload::make('photo')
                    ->image()
                    //->imageEditor()
                    ->storeFileNamesIn('attachment_file_name')
                    ->disk('products') // Usa el disco 'products'
                    ->visibility('public')
                    ->previewable(true)
                    ->required(),

                Textarea::make('description')
                    ->maxLength(1000)
                    ->nullable(),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('products')
                    ->toggleable()
                    ->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->money('USD', true)
                    ->sortable(),

                SelectColumn::make('status')
                    ->searchable()
                    ->options([
                        'paused' => 'Paused',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                ]),

                TextColumn::make('stock')
                    ->sortable(),

                TextColumn::make('brand')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
            ])
            ->filters([
                // Puedes agregar filtros aquí si lo deseas
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
