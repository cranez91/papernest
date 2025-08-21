<?php

namespace App\Filament\Resources;

use App\Models\Order;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ShoppingCartItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\{Grid, Repeater, Select, TextInput};
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Support\Facades\Log;


class SaleResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-storefront';

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

                            SaleResource::recalculateTotals($set, $get);
                        }),
                ]),

                Grid::make(1)->schema([
                    Repeater::make('items')
                        ->label('Productos')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('Producto')
                                ->options(Product::where('status', 'active')->get()->pluck('name', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, $set) => $set('price', Product::find($state)?->price ?? 0)),

                            TextInput::make('price')
                                ->label('Precio')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->required(),

                            TextInput::make('quantity')
                                ->label('Cantidad')
                                ->numeric()
                                ->default(0)
                                ->minValue(1)
                                ->debounce(100)
                                ->dehydrated(true)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $productId = $get('product_id');

                                    if (!$productId) return;

                                    $product = Product::find($productId);
                                    
                                    if (!$product) return;
                                    $stock = $product->stock;
                                    $cartCount = ShoppingCartItem::where('product_id', $productId)->sum('quantity');
                                    $available = $stock - $cartCount;
                                    // Solo ajusta la cantidad si es necesario
                                    if ($state > $available) {
                                        $set('quantity', $available);
                                    }
                                    $set('total_item', $product->price * $get('quantity'));
                                    SaleResource::recalculateTotals($set, $get);
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
                        ->reorderable()
                        ->defaultItems(0) // 👈 no mostrar ningún item al inicio
                        ->createItemButtonLabel('Agregar producto')
                        ->afterStateUpdated(fn ($set, $get) => self::recalculateTotals($set, $get)),
                ]),

                Grid::make(2)->schema([
                    TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->prefix('$')
                        ->disabled(),

                    TextInput::make('total')
                        ->label('Total')
                        ->numeric()
                        ->prefix('$')
                        ->disabled(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
                
                SelectColumn::make('status')
                    ->searchable()
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'sent' => 'Sent',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'paid' => 'Paid',
                    ])
                    ->disabled(),

                TextColumn::make('subtotal')
                    ->numeric(decimalPlaces: 2)
                    ->money()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->numeric(decimalPlaces: 2)
                    ->money()
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('coupon.discount_percentage')
                    ->label('Discount')
                    ->suffix('%')
                    ->searchable()
                    ->sortable()
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
        $items = collect($get('items') ?? []);
        $subtotal = $items->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $total = $subtotal;

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
        $set('total', $total);
    }
}
