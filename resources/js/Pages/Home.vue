<template>
    <SeoHead title="Inicio"
             description="En Papelería Andy encontrarás todo lo que necesitas para estudiar, trabajar y crear. Atención cercana, precios accesibles y productos de calidad, ¡todo en un solo lugar!"
             image="/images/preview-cart.jpg"/>

    <Header/>

    <div class="relative isolate px-6 pt-8 lg:px-8 bg-gray-200 bg-[url('/images/fondo_papeleria.png')] bg-cover bg-center bg-no-repeat">
        <div class="mx-auto max-w-2xl py-30 sm:py-30 lg:py-30">
            <div class="text-center backdrop-blur-sm rounded-2xl p-6">
                <h1 class="text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-6xl">
                    Todo lo que necesitas para estudiar, trabajar y crear
                </h1>
                <p class="mt-8 text-lg font-medium text-pretty text-gray-700 sm:text-xl/8">
                    En Papelería Andy encontrarás útiles escolares, materiales de oficina, regalos y más sorpresas.
                    Atención cercana, precios accesibles y productos de calidad, ¡todo en un solo lugar!
                </p>
            </div>
        </div>
    </div>

    <section class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-amber-700">
                        Selección de la tienda
                    </p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Los más populares
                    </h2>
                    <p class="mt-3 max-w-2xl text-gray-600">
                        Descubre artículos para estudiar, trabajar y crear todos los días.
                    </p>
                </div>

                <Link :href="route('products.list')"
                      class="inline-flex items-center justify-center rounded-xl border border-amber-300 bg-white px-5 py-3
                             text-sm font-semibold text-amber-700 shadow-sm transition hover:border-amber-400
                             hover:bg-amber-50 hover:text-amber-800">
                    Ver todos los artículos
                </Link>
            </div>

            <div v-if="products.length"
                 class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article v-for="product in products"
                         :key="product.sku"
                         class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                                transition duration-200 hover:-translate-y-1 hover:shadow-xl">
                    <Link :href="`/articulo/${ product.sku }`"
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
                            <Link :href="`/articulo/${ product.sku }`"
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

            <div v-else
                 class="mt-10 rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-600 shadow-sm">
                No se encontraron productos.
            </div>
        </div>
    </section>

    <HomeChatBot/>

    <Footer/>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import HomeChatBot from '@/components/HomeChatBot.vue';
    import SeoHead from '@/components/layout/SeoHead.vue';

    const props = defineProps({
        products: Array
    })
</script>
