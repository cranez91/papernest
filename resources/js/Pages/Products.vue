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
                                        {{ category.name }} ({{ category.products_count}})
                                    </Link>
                                </li>
                            </ul>

                        </form>

                        <!-- Product grid -->
                        <div class="lg:col-span-3">
                            <div v-if="products.data.length">
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                    <article v-for="product in products.data"
                                             :key="product.sku"
                                             class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                                                    transition duration-200 hover:-translate-y-1 hover:shadow-xl">
                                        <Link :href="`/articulo/${product.sku}`"
                                              :title="product.name"
                                              class="block aspect-square bg-gray-50 p-4">
                                             <img :src="`/products/${product.photo}`"
                                                  :alt="product.name"
                                                  loading="lazy"
                                                  class="h-full w-full object-contain transition duration-200 group-hover:scale-[1.03]"/>
                                        </Link>

                                        <div class="flex flex-1 flex-col p-5">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                {{ product.brand }}
                                            </p>
                                            <h3 class="mt-2 min-h-10 text-base font-semibold text-gray-900">
                                                <Link :href="`/articulo/${product.sku}`"
                                                      :title="product.name"
                                                      class="transition hover:text-amber-700">
                                                    {{ product.short_name }}
                                                </Link>
                                            </h3>

                                            <div class="mt-5 flex items-end justify-between gap-3 border-t border-gray-100 pt-4">
                                                <div>
                                                    <p class="text-xs text-gray-500">
                                                        Precio
                                                    </p>
                                                    <p class="mt-1 text-lg font-bold text-lime-600">
                                                        ${{ product.price }}
                                                    </p>
                                                </div>
                                                <div class="shrink-0 [&>button]:mt-0 [&>button]:px-4 [&>button]:py-2.5">
                                                    <add-to-cart :product="product"></add-to-cart>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="mt-8 flex flex-wrap gap-2">
                                    <button class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
                                             :class="{
                                                'border-amber-500 bg-amber-500 text-white': link.active,
                                                'border-gray-200 bg-white text-gray-700 hover:border-amber-300 hover:bg-amber-50 cursor-pointer': !link.active && link.url,
                                                'border-gray-200 bg-gray-100 text-gray-400 opacity-50 cursor-not-allowed': !link.url,
                                             }"
                                             :key="link.label"
                                            :disabled="!link.url"
                                            v-html="link.label"
                                            v-for="link in products.links"
                                            @click.prevent="goToPage(link.url)"
                                    />
                                </div>
                            </div>
                            <div v-else
                                 class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-600 shadow-sm">
                                No se encontraron productos.
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
