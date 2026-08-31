<template>
    <SeoHead title="Aprende"
             description="Próximamente actividades educativas en Papelería Andy. Aprende jugando."/>

    <Header/>

    <div class="bg-gray-50 min-h-screen">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8 mt-14">
            <div class="text-center">
                <h1 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                    Aprende
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    Actividades educativas para que los más pequeños aprendan mientras juegan.
                </p>
            </div>

            <div class="mt-12 bg-white rounded-2xl shadow-md p-10 text-center">
                <img src="/images/papeleria-andy-logo-dino.png"
                     alt="Papelería Andy"
                     class="h-32 w-auto object-contain mx-auto"/>
                <h2 class="mt-6 text-2xl font-bold text-gray-900">
                    Próximamente
                </h2>
                <p class="mt-4 text-gray-600">
                    Estamos preparando actividades educativas para reforzar el aprendizaje de forma
                    divertida. Muy pronto encontrarás aquí retos, juegos y dinámicas para aprender
                    jugando.
                </p>
                <div class="mt-8 inline-flex items-center gap-2 rounded-full bg-amber-100 px-6 py-3
                            text-sm font-semibold text-amber-800">
                    ¡Vuelve pronto!
                </div>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<script setup>
    import SeoHead from '@/components/layout/SeoHead.vue';
</script>
