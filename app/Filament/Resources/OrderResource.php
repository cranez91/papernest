<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Coupon;
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
use Filament\Tables\Enums\ActionsPosition;


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
                Grid::make(4)->schema([
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
                        ->email(),

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
                    
                    Select::make('coupon_id')
                        ->label('Discount')
                        ->options(function (callable $get) {
                            $subtotal = collect($get('items') ?? [])
                                ->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

                            return Coupon::where('status', 'active')
                                ->whereDate('start_date', '<=', now('America/Mexico_City')->timezone('UTC'))
                                ->whereDate('end_date', '>=', now('America/Mexico_City')->timezone('UTC'))
                                ->where('min_total', '<=', $subtotal)
                                ->pluck('description', 'id');
                        })
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $set('coupon_id', $state);

                            OrderResource::recalculateTotals($set, $get);
                        }),
                ]),

                Grid::make(2)->schema([
                    Repeater::make('items')
                        ->label('Detalles de la Orden')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('Producto')
                                ->options(Product::where('status', 'active')->get()->pluck('name', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('price', Product::find($state)?->price ?? 0)
                                ),
                            
                            TextInput::make('price')
                                ->label('Precio')
                                ->numeric()
                                ->prefix('$')
                                ->inputMode('decimal')
                                ->disabled()
                                ->required(),

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

                                    $set('total_item', $product->price * $get('quantity'));

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

                                    return "$available en stock";
                                }),

                            TextInput::make('total_item')
                                ->label('Total')
                                ->numeric()
                                ->prefix('$')
                                ->inputMode('decimal')
                                ->disabled()
                                ->dehydrated(false),
                        ])
                        ->columns(4)
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
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTimeTooltip()
                    ->dateTime('d M Y H:i'),

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
                
                TextColumn::make('coupon.discount_percentage')
                    ->label('Discount')
                    ->suffix('%')
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
                Tables\Actions\Action::make('Enviar Whats')
                    ->url(fn (Order $record): string => 'https://web.whatsapp.com/send?phone=+52' . $record->whatsapp . '&text=Hola, nos comunicamos para dar seguimiento a tu orden.')
                    ->openUrlInNewTab()
            ], position: ActionsPosition::BeforeCells)
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
        $items = collect($get('items') ?? []);
        $subtotal = $items->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $total = $subtotal;
        $shipping = $get('shipping_price') ?? 0;

        if ($get('coupon_id')) {
            $coupon = Coupon::find($get('coupon_id'));
            if (
                $coupon &&
                $coupon->status === 'active' &&
                now('America/Mexico_City')->timezone('UTC')->between($coupon->start_date, $coupon->end_date) &&
                $subtotal >= $coupon->min_total
            ) {
                $discount = ($subtotal * $coupon->discount_percentage) / 100;
                $total -= $discount;
            }
        }

        $set('subtotal', $subtotal);
        $set('total', $total + $shipping);
    }
}
