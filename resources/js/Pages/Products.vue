<template>
    <Header/>

    <div class="bg-white">
        <!-- Banner -->
        <div class="w-full h-60 mb-8 overflow-hidden relative">
            <img src="/images/papeleria-producto-banner-articulos.jpg"
                 alt="Banner producto" 
                 class="w-full h-full object-cover">
        </div>

        <div class="bg-white">
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
                                    <a class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                       :href="route('products.list', { category: selectedCategory, sortBy: 'created_at', order: 'desc' })"
                                       :active="route().current('products.list')">
                                        Más recientes
                                    </a>
                                    <a class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                       :href="route('products.list', { category: selectedCategory, sortBy: 'price', order: 'asc' })"
                                       :active="route().current('products.list')">
                                        Precio: Menor a Mayor
                                    </a>
                                    <a class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-hidden"
                                       :href="route('products.list', { category: selectedCategory, sortBy: 'price', order: 'desc' })"
                                       :active="route().current('products.list')">
                                        Precio: Mayor a Menor
                                    </a>
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
                                    <a class="text-sm/6 font-semibold text-gray-900"
                                       :href="route('products.list', { category: null })"
                                       :active="route().current('products.list')">
                                        Ver Todos
                                    </a>
                                </li>
                                <li v-for="category in categories">
                                    <a class="text-sm/6 font-semibold text-gray-900"
                                       :href="route('products.list', { category: category.sku })"
                                       :active="route().current('products.list')">
                                        {{ category.name }}
                                    </a>
                                </li>
                            </ul>

                        </form>

                        <!-- Product grid -->
                        <div class="lg:col-span-3">
                            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-3 xl:gap-x-8">
                                    <div class="group relative"
                                         v-for="product in products.data">
                                        <a :href="`/articulo/${product.sku}`">
                                            <img :src="`/products/${product.photo}`"
                                                 :alt="product.name"
                                                 class="aspect-square w-full rounded-md bg-gray-200 object-cover
                                                        group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                                        </a>
                                        <div class="mt-4 flex justify-between">
                                            <div>
                                                <h3 class="text-sm text-gray-700">
                                                    <a :href="`/articulo/${product.sku}`">
                                                        {{ product.short_name }}
                                                    </a>
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
                                </div>
                                <!-- Pagination -->
                                <div class="mt-4 flex space-x-2">
                                    <button class="px-3 py-1 rounded border bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white"
                                            :class="{
                                                'bg-blue-500 text-white': link.active,
                                                'text-gray-600': !link.active,
                                                'opacity-50 cursor-not-allowed': !link.url,
                                            }"
                                            :key="link.label"
                                            :disabled="!link.url"
                                            v-html="link.label"
                                            v-for="link in products.links"
                                            @click.prevent="goToPage(link.url)"
                                    />
                                </div>
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
    import { router } from "@inertiajs/vue3";
    import { onMounted } from "vue";

    const props = defineProps({
        products: Object,
        categories: Array,
        selectedCategory: String
    })

    onMounted(() => {
        console.log('products', props.products);
    })

    const goToPage = (url) => {
        if (!url) return

        const relativeUrl = url.replace(window.location.origin, '')
        console.log('relativeUrl', relativeUrl);
        router.get(relativeUrl, {}, { preserveState: true, replace: true });
    };
</script>