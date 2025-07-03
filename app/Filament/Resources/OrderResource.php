<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShoppingCartItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\{TextInput, Select, Repeater, Hidden, Grid};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Ordenes';
    protected static ?string $navigationGroup = 'Ordenes y Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    TextInput::make('customer_name')
                        ->label('Customer Name')
                        ->required(),

                    TextInput::make('whatsapp')
                        ->label('Whatsapp')
                        ->prefix('+52')
                        ->length(10)
                        ->numeric()
                        ->required(),

                    TextInput::make('email')
                        ->label('E-mail')
                        ->email()
                        ->required(),

                    TextInput::make('address')
                        ->label('Address')
                        ->required(),

                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'pending' => 'En espera',
                            'confirmed' => 'Confirmado',
                            'sent' => 'Enviada',
                            'delivered' => 'Entregada',
                            'cancelled' => 'Cancelada',
                        ])
                        ->default('pending')
                        ->required(),

                    Select::make('payment_type')
                        ->label('Payment Type')
                        ->options([
                            'cash_on_delivery' => 'Cash on delivery',
                        ])
                        ->default('cash_on_delivery')
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    Repeater::make('items')
                        ->label('Detalles de la Orden')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('Producto')
                                ->options(Product::where('status', 'active')->get()->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('price', Product::find($state)?->price ?? 0)
                                ),

                            TextInput::make('quantity')
                                ->label('Cantidad')
                                ->numeric()
                                ->default(0)
                                ->minValue(1)
                                ->required()
                                ->debounce(200)
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
                                                OrderResource::recalculateTotals($set, $get);
                                            }
                                        );
                                    } else {
                                        OrderResource::recalculateTotals($set, $get);
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
                                ->prefix('$')
                                ->inputMode('decimal')
                                ->disabled()
                                ->required(),
                        ])
                        ->columns(3)
                        ->columnSpan(2)
                        ->reorderable()
                        ->reactive()
                        ->defaultItems(0) // 👈 no mostrar ningún item al inicio
                        ->createItemButtonLabel('Agregar producto')
                        ->afterStateUpdated(fn (callable $set, callable $get) => self::recalculateTotals($set, $get)),
                ]),
                
                Grid::make(3)->schema([
                    TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->disabled()
                        ->prefix('$')
                        ->reactive(),

                    TextInput::make('shipping_price')
                        ->label('Envío')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->debounce(1000) // calcular el total despues de un segundo
                        ->prefix('$')
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set, callable $get) => self::recalculateTotals($set, $get)),

                    TextInput::make('total')
                        ->label('Total')
                        ->numeric()
                        ->disabled()
                        ->prefix('$')
                        ->reactive(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->limit(8)
                    ->searchable(),

                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('whatsapp')
                    ->searchable()
                    ->sortable(),
                
                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'sent' => 'Sent',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'paid' => 'Paid',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->numeric(decimalPlaces: 2)
                    ->money()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('shipping_price')
                    ->label('Shipping')
                    ->numeric(decimalPlaces: 2)
                    ->money()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->numeric(decimalPlaces: 2)
                    ->money()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static function recalculateTotals(callable $set, callable $get): void
    {
        $subtotal = collect($get('items') ?? [])
            ->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

        $shipping = $get('shipping_price') ?? 0;

        $set('subtotal', $subtotal);
        $set('total', $subtotal + $shipping);
    }

    public static function beforeSave($record, $data)
    {
        $subtotal = collect($data['items'] ?? [])
            ->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

        $record->subtotal = $subtotal;
        $record->total = $subtotal + ($data['shipping_price'] ?? 0);
    }
}
