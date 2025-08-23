@extends('layouts.app')

@section('content')
    @include('layouts.header')
    
    <div class="bg-white">
        <!-- Banner -->
        <div class="w-full h-60 mb-8 overflow-hidden relative">
            <img src="/images/papeleria-producto-banner-articulos.jpg"
                 alt="Banner producto" 
                 class="w-full h-full object-cover">
        </div>

        <!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
        <div class="bg-white">
            <!-- Mobile filter dialog -->
            <el-dialog>
                <dialog id="mobile-filters"
                        class="overflow-hidden backdrop:bg-transparent lg:hidden">
                    <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity
                                               duration-300 ease-linear data-closed:opacity-0">
                    </el-dialog-backdrop>

                    <div tabindex="0"
                         class="fixed inset-0 flex focus:outline-none">
                        <el-dialog-panel class="relative ml-auto flex size-full max-w-xs transform
                                                flex-col overflow-y-auto bg-white pt-4 pb-6 shadow-xl
                                                transition duration-300 ease-in-out data-closed:translate-x-full">
                            <div class="flex items-center justify-between px-4">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Filters
                                </h2>
                                <button type="button"
                                        command="close"
                                        commandfor="mobile-filters"
                                        class="relative -mr-2 flex size-10 items-center justify-center rounded-md
                                               bg-white p-2 text-gray-400 hover:bg-gray-50 focus:ring-2
                                               focus:ring-indigo-500 focus:outline-hidden">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">
                                        Close menu
                                    </span>
                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         data-slot="icon"
                                         aria-hidden="true"
                                         class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12"
                                              stroke-linecap="round"
                                              stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Filters -->
                            <form class="mt-4 border-t border-gray-200">
                                <h3 class="sr-only">
                                    Categories
                                </h3>
                                <ul role="list"
                                    class="px-2 py-3 font-medium text-gray-900">
                                    <li>
                                        <a href="#" class="block px-2 py-3">Totes</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-2 py-3">Backpacks</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-2 py-3">Travel Bags</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-2 py-3">Hip Bags</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-2 py-3">Laptop Sleeves</a>
                                    </li>
                                </ul>
                            </form>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>

            <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-baseline justify-between border-b border-gray-200 pt-6 pb-6">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        Te ofrecemos
                    </h1>

                    <div class="flex items-center">
                        <el-dropdown class="relative inline-block text-left">
                            <button class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                Ordenar
                                <svg viewBox="0 0 20 20"
                                     fill="currentColor"
                                     data-slot="icon"
                                     aria-hidden="true"
                                     class="-mr-1 ml-1 size-5 shrink-0 text-gray-400 group-hover:text-gray-500">
                                    <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                          clip-rule="evenodd"
                                          fill-rule="evenodd"/>
                                </svg>
                            </button>

                            <el-menu anchor="bottom end"
                                     popover
                                     class="w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1
                                            ring-black/5 transition transition-discrete [--anchor-gap:--spacing(2)]
                                            focus:outline-hidden data-closed:scale-95 data-closed:transform
                                            data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out
                                            data-leave:duration-75 data-leave:ease-in">
                                <div class="py-1">
                                    <!-- Selected: "font-medium text-gray-900", Not Selected: "text-gray-500" -->
                                    <a href="{{ route('products.list', ['categoria' => $selected_category, 'sortBy' => 'created_at', 'order' => 'desc']) }}"
                                       class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden">
                                        Más recientes
                                    </a>
                                    <a href="{{ route('products.list', ['categoria' => $selected_category, 'sortBy' => 'price', 'order' => 'asc']) }}"
                                       class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden">
                                        Precio: Menor a Mayor
                                    </a>
                                    <a href="{{ route('products.list', ['categoria' => $selected_category, 'sortBy' => 'price', 'order' => 'desc']) }}"
                                       class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden">
                                        Precio: Mayor a Menor
                                    </a>
                                </div>
                            </el-menu>
                        </el-dropdown>

                        <button type="button"
                                command="show-modal"
                                commandfor="mobile-filters"
                                class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden">
                            <span class="sr-only">
                                Filters
                            </span>
                            <svg viewBox="0 0 20 20"
                                 fill="currentColor"
                                 data-slot="icon"
                                 aria-hidden="true"
                                 class="size-5">
                                <path d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z"
                                      clip-rule="evenodd"
                                      fill-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <section aria-labelledby="products-heading"
                         class="pt-6 pb-24">
                    <h2 id="products-heading"
                        class="sr-only">
                        Products
                    </h2>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <!-- Filters -->
                        <form class="hidden lg:block">
                            <h3 class="sr-only">
                                Categories
                            </h3>
                            <ul role="list"
                                class="space-y-4 border-b border-gray-200 pb-6 text-sm
                                       font-medium text-gray-900">
                                    <li>
                                        <a href="{{ route('products.list', ['categoria' => null]) }}">
                                            <strong>Ver Todos</strong>
                                        </a>
                                    </li>
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('products.list', ['categoria' => $category->sku]) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                        </form>

                        <!-- Product grid -->
                        <div class="lg:col-span-3">
                            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-3 xl:gap-x-8">
                                    @foreach ($products as $product)
                                        <div class="group relative">
                                            <a href="/articulo/{{ $product->sku }}">
                                                <img src="/products/{{ $product->photo }}"
                                                     alt="{{ $product->name }}"
                                                     class="aspect-square w-full rounded-md bg-gray-200 object-cover
                                                            group-hover:opacity-75 lg:aspect-auto lg:h-80" />
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
                                                <button class="mt-4 bg-lime-600 hover:bg-lime-700 text-white
                                                               font-semibold py-3 px-6 rounded-xl shadow-md
                                                               transition duration-200">
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
                                <div class="mt-8">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    @include('layouts.footer')
@endsection