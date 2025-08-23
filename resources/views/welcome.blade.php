@extends('layouts.app')

@section('content')
      @include('layouts.header')

      <div class="relative isolate px-6 pt-8 lg:px-8 bg-[#e2f8d5]">
            <div class="mx-auto max-w-2xl py-30 sm:py-30 lg:py-30">
                <div class="text-center">
                    <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">
                        Todo lo que necesitas para estudiar, trabajar y crear
                    </h1>
                    <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">
                        En Papelería Andy encontrarás útiles escolares, materiales de oficina, regalos y más sorpresas.
                        Atención cercana, precios accesibles y productos de calidad, ¡todo en un solo lugar!
                    </p>
                </div>
            </div>
      </div>

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
                                          <button class="mt-4 bg-lime-600 hover:bg-lime-700
                                                         text-white font-semibold py-3 px-6
                                                         rounded-xl shadow-md transition duration-200">
                                                <!-- Ícono carrito con Tailwind -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                                         fill="none" 
                                                         viewBox="0 0 24 24" 
                                                         stroke-width="1.5" 
                                                         stroke="currentColor" 
                                                         class="w-6 h-6 text-white">
                                                        <path stroke-linecap="round" 
                                                              stroke-linejoin="round" 
                                                              d="M2.25 2.25h1.386c.51 0 .955.343 1.087.835l.383 1.437m0 
                                                                0L6.75 13.5h10.878a1.125 1.125 0 001.1-.862l1.5-6A1.125 
                                                                1.125 0 0018.25 5.25H5.108m0 0L4.5 2.25M6 16.5a1.5 
                                                                1.5 0 100 3 1.5 1.5 0 000-3zm9 0a1.5 1.5 0 100 3 
                                                                1.5 1.5 0 000-3z" />
                                                    </svg>
                                          </button>
                                    </div>
                              </div>
                        @endforeach
                  </div>
            </div>
      </div>

      <home-chat-bot></home-chat-bot>

      @include('layouts.footer')
@endsection