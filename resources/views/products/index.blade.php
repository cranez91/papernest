@extends('layouts.app')

@section('content')
    @include('layouts.header')
    
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                Los más populares
            </h2>
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach ($products as $product)
                    <div class="group relative">
                        <a href="/articulo/{{ $product->sku }}">
                            <img src="/products/{{ $product->photo }}"
                                 alt="{{ $product->name }}"
                                 class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                        </a>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="/articulo/{{ $product->sku }}">
                                        {{ $product->short_name }}
                                    </a>
                                </h3>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block 
                                            bg-gray-900 text-white text-xs rounded px-2 py-1 shadow-lg">
                                    {{ $product->name }}
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $product->brand }}
                                </p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">
                                ${{ $product->price }}
                            </p>
                        </div>
                        <div class="mt-2 flex justify-between">
                            <button class="mt-4 bg-lime-600 hover:bg-lime-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition duration-200">
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection