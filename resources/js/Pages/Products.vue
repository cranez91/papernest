<template>
    <SeoHead title="Artículos"
             description="En Papelería Andy encontrarás útiles escolares, materiales de oficina, regalos y más sorpresas."
             image="/images/preview-cart.jpg"/>

    <Header/>

    <div class="bg-white">
        <!-- Banner -->
        <div class="w-full h-60 mt-20 mb-8 overflow-hidden relative">
            <img src="/images/papeleria-producto-banner-articulos.jpg"
                 alt="Banner producto" 
                 class="w-full h-full object-cover">
        </div>

        <div class="bg-white">
            <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-baseline
                            border-b border-gray-200 pt-6 pb-6 justify-between">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-3">
                        Te ofrecemos
                    </h1>

                    <div class="flex w-sm mb-3">
                            <input class="flex-1 rounded-l-md border border-gray-300 px-3 py-2
                                        text-sm focus:outline-none focus:ring-2 focus:ring-lime-600"
                                   type="text"
                                   placeholder="Buscar..."
                                   v-model="search"/>

                            <button class="rounded-r-md bg-lime-600 px-3 text-white hover:bg-lime-700
                                        flex items-center justify-center cursor-pointer"
                                    type="button"
                                    @click="searchProducts">
                                <!-- Icono de búsqueda -->
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-5 w-5" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5
                                             18a7.5 7.5 0 006.15-3.35z" />
                                </svg>
                            </button>
                    </div>

                    <div class="flex items-center mb-3">
                        <el-dropdown class="block lg:hidden relative inline-block text-left mr-6">
                            <button class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                Categorias
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
                                    <Link class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                          :href="route('products.list', { category: null })">
                                        Ver Todos
                                    </Link>
                                    <Link class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                          :href="`/articulos/${category.sku}`"
                                          v-for="category in categories">
                                        {{ category.name }}
                                    </Link>
                                </div>
                            </el-menu>
                        </el-dropdown>

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
                                    <Link class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                       :href="route('products.list', { category: selectedCategory, sortBy: 'created_at', order: 'desc' })">
                                        Más recientes
                                    </Link>
                                    <Link class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                       :href="route('products.list', { category: selectedCategory, sortBy: 'price', order: 'asc' })">
                                        Precio: Menor a Mayor
                                    </Link>
                                    <Link class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                       :href="route('products.list', { category: selectedCategory, sortBy: 'price', order: 'desc' })">
                                        Precio: Mayor a Menor
                                    </Link>
                                </div>
                            </el-menu>
                        </el-dropdown>
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
                                    <Link class="text-sm/6 font-semibold text-gray-900"
                                       :href="route('products.list', { category: null })">
                                        Ver Todos
                                    </Link>
                                </li>
                                <li :key="category.sku"
                                    v-for="category in categories">
                                    <Link class="text-sm/6 font-semibold text-gray-900"
                                          :class="{
                                            'bg-amber-200 p-3 text-black': page.url.includes(category.sku), 
                                            'text-gray-500 hover:bg-gray-100': !page.url.includes(category.sku)
                                          }"
                                          :href="`/articulos/${category.sku}`">
                                        {{ category.name }}
                                    </Link>
                                </li>
                            </ul>

                        </form>

                        <!-- Product grid -->
                        <div class="lg:col-span-3">
                            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8"
                                 v-if="products.data.length">
                                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-3 xl:gap-x-8">
                                    <div class="group relative"
                                         v-for="product in products.data">
                                        <Link :href="`/articulo/${product.sku}`">
                                            <img :src="`/products/${product.photo}`"
                                                 :alt="product.name"
                                                 class="aspect-square w-full rounded-md bg-gray-200 object-cover
                                                        group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                                        </Link>
                                        <div class="mt-4 flex justify-between">
                                            <div>
                                                <h3 class="text-sm text-gray-700">
                                                    <Link :href="`/articulo/${product.sku}`">
                                                        {{ product.short_name }}
                                                    </Link>
                                                </h3>
                                                <!-- Tooltip -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block 
                                                            bg-gray-900 text-white text-xs rounded px-2 py-1 shadow-lg">
                                                    {{ product.name }}
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ product.brand }}
                                                </p>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900">
                                                ${{ product.price }}
                                            </p>
                                        </div>
                                        <div class="mt-2 flex justify-between">
                                            <add-to-cart :product="product"></add-to-cart>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pagination -->
                                <div class="mt-4 flex space-x-2">
                                    <button class="px-3 py-1 rounded border text-white dark:text-white"
                                            :class="{
                                                'bg-gray-400 text-white': link.active,
                                                'bg-sky-950 text-gray-900 cursor-pointer': !link.active && link.url,
                                                'bg-sky-950 text-gray-900 opacity-50 cursor-not-allowed': !link.url,
                                            }"
                                            :key="link.label"
                                            :disabled="!link.url"
                                            v-html="link.label"
                                            v-for="link in products.links"
                                            @click.prevent="goToPage(link.url)"
                                    />
                                </div>
                            </div>
                            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8"
                                 v-else>
                                <h1 class="font-bold">No se encontraron productos</h1>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <Footer/>
</template>

<script setup>
    import { ref } from 'vue';
    import { Link, router, usePage } from '@inertiajs/vue3';
    import SeoHead from '@/components/layout/SeoHead.vue';
    import Swal from 'sweetalert2';

    const page = usePage()
    const search = ref('')

    const props = defineProps({
        products: Object,
        categories: Array,
        selectedCategory: String
    })

    const goToPage = (url) => {
        if (!url) return

        const relativeUrl = url.replace(window.location.origin, '')
        router.get(relativeUrl, {}, { preserveState: true, replace: true });
    };

    const searchProducts = async () => {
        if (!search.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Espera un momento',
                text: 'Debes ingresar el producto que deseas buscar',
                showConfirmButton: true,
                allowOutsideClick: false
            });
            return;
        }
        Swal.fire({
            icon: 'info',
            title: 'Espera un momento',
            text: 'Estamos obteniendo los resultados para tu búsqueda...',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false
        }).then((result) => {
            redirectWithSearch(search.value);
        });
    }

    const redirectWithSearch = (value) => {
        const params = new URLSearchParams(window.location.search);
        params.set('search', value);

        const newUrl = `${window.location.pathname}?${params.toString()}`;
        router.visit(newUrl);
    }
</script>