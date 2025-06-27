<?php

namespace App\Filament\Resources;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShoppingCartItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\{Grid, Repeater, Select, TextInput};
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Support\Str;

class SaleResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Ventas Físicas';

    protected static ?string $label = 'Venta';

    protected static ?string $pluralLabel = 'Ventas';

    protected static ?string $navigationGroup = 'Ordenes y Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Select::make('payment_type')
                        ->label('Tipo de pago')
                        ->options([
                            'cash_on_delivery' => 'Efectivo',
                        ])
                        ->default('cash_on_delivery')
                        ->required(),
                ]),

                Grid::make(1)->schema([
                    Repeater::make('items')
                        ->label('Productos')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('Producto')
                                ->options(Product::all()->pluck('name', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, $set) => $set('price', Product::find($state)?->price ?? 0)),

                            TextInput::make('quantity')
                                ->label('Cantidad')
                                ->numeric()
                                ->default(1)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $productId = $get('product_id');

                                    if (!$productId) return;

                                    $product = Product::find($productId);
                                    if (!$product) return;

                                    $stock = $product->stock;
                                    $cartCount = ShoppingCartItem::where('product_id', $productId)->sum('quantity');
                                    $available = $stock - $cartCount;

                                    $newQuantity = min($state, $available);

                                    // Solo seteamos si hay diferencia
                                    if ($state != $newQuantity) {
                                        $set('quantity', $newQuantity);

                                        // Recalcular después de un micro-delay forzado
                                        \Filament\Support\Facades\FilamentView::registerRenderHook(
                                            'scripts::after',
                                            function () use ($set, $get) {
                                                SaleResource::recalculateTotals($set, $get);
                                            }
                                        );
                                    } else {
                                        SaleResource::recalculateTotals($set, $get);
                                    }
                                })
                                ->rule(function (callable $get) {
                                    $productId = $get('product_id');

                                    if (!$productId) return null;

                                    $product = Product::find($productId);

                                    if (!$product) return null;

                                    $stock = $product->stock;

                                    $cartCount = ShoppingCartItem::where('product_id', $productId)->count();

                                    $available = $stock - $cartCount;

                                    return "max:$available";
                                })
                                ->hint(function (callable $get) {
                                    $productId = $get('product_id');

                                    if (!$productId) return null;

                                    $product = Product::find($productId);
                                    $cartCount = ShoppingCartItem::where('product_id', $productId)->count();

                                    if (!$product) return null;

                                    $available = $product->stock - $cartCount;

                                    return "Disponibles: $available en stock";
                                }),

                            TextInput::make('price')
                                ->label('Precio')
                                ->numeric()
                                ->disabled()
                                ->required(),
                        ])
                        ->columns(3)
                        ->reorderable()
                        ->defaultItems(0) // 👈 no mostrar ningún item al inicio
                        ->createItemButtonLabel('Agregar producto')
                        ->afterStateUpdated(fn ($set, $get) => self::recalculateTotals($set, $get)),
                ]),

                Grid::make(2)->schema([
                    TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('total')
                        ->label('Total')
                        ->disabled()
                        ->dehydrated(false),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime(),
                Tables\Columns\TextColumn::make('total')->money(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }

    protected static function recalculateTotals(callable $set, callable $get): void
    {
        $subtotal = collect($get('items') ?? [])->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $set('subtotal', $subtotal);
        $set('total', $subtotal);
    }

    public static function beforeCreate($record, $data): void
    {
        $record->source = 'admin';
        $record->status = 'paid';
        $record->id = (string) Str::uuid();
        $record->subtotal = collect($data['items'] ?? [])->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $record->total = $record->subtotal;
    }
}
