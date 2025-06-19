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
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $pluralModelLabel = 'Products';

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
                    ->unique(ignoreRecord: true),

                TextInput::make('sku')
                    ->unique(ignoreRecord: true)
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

                FileUpload::make('photo')
                    ->image()
                    //->imageEditor()
                    ->storeFileNamesIn('attachment_file_name')
                    ->disk('public') // Usa el disco 'public', ya que la URL generada es "/storage/..."
                    //->directory('ingredients') // Guarda en "storage/app/public/ingredients"
                    //->visibility('public')
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
                    ->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->sortable(),

                TextColumn::make('price')
                    ->money('USD', true)
                    ->sortable(),

                TextColumn::make('brand')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('stock')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'paused',
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
            ])
            ->filters([
                // Puedes agregar filtros aquí si lo deseas
            ])
            ->actions([
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
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
