<template>
    <SeoHead title="Juega"
             description="Juega gratis en Papelería Andy. Diviértete con Dino Runner y más juegos para toda la familia."/>

    <Header/>

    <div class="bg-gray-50 min-h-screen">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 mt-14">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                    Juega
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    Elige un juego y diviértete mientras corres con el dinosaurio de Papelería Andy.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="group bg-white rounded-2xl shadow-md overflow-hidden
                            flex flex-col transition hover:shadow-xl">
                    <div class="bg-amber-50 flex items-center justify-center p-6">
                        <img src="/images/papeleria-andy-logo-dino.png"
                             alt="Dino Runner"
                             class="h-48 w-auto object-contain"/>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h2 class="text-xl font-bold text-gray-900">
                            Dino Runner
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 flex-1">
                            Corre con el dinosaurio de Papelería Andy, salta y agáchate para esquivar
                            los útiles escolares que se cruzan en tu camino. ¿Cuántos puntos puedes hacer?
                        </p>
                        <Link :href="route('juega.dino-runner')"
                              class="mt-6 inline-flex items-center justify-center rounded-xl
                                     bg-amber-500 px-6 py-3 text-base font-semibold text-white
                                     shadow hover:bg-amber-600">
                            Jugar
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import SeoHead from '@/components/layout/SeoHead.vue';
</script>
