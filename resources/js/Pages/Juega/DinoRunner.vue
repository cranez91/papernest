<template>
    <SeoHead title="Dino Runner"
             description="Juega Dino Runner de Papelería Andy. Corre, salta y agáchate para esquivar los útiles escolares."/>

    <Header/>

    <div class="bg-gray-50 min-h-screen">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 mt-14">
            <div class="mb-6">
                <Link :href="route('juega')"
                      class="text-sm font-semibold text-amber-600 hover:text-amber-700">
                    ← Volver a Juega
                </Link>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                Dino Runner
            </h1>
            <p class="mt-2 mb-8 text-gray-600">
                Corre con el dinosaurio de Papelería Andy. Usa el teclado para saltar, agacharte
                y controlar la velocidad.
            </p>

            <DinoRunnerGame/>
        </div>
    </div>

    <Footer/>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import SeoHead from '@/components/layout/SeoHead.vue';
    import DinoRunnerGame from '@/components/games/DinoRunner/DinoRunnerGame.vue';
</script>
