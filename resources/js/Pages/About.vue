
<template>
    <Header/>

    <div class="max-w-4xl mx-auto py-12 px-6 space-y-10 mt-14">
        <!-- Título -->
        <h1 class="flex justify-center  text-3xl font-bold text-gray-900 dark:text-gray-100">
            {{ settings.business_name }}
        </h1>

        <div class="flex justify-center md:shrink-0 mt-2 mb-8">
            <img class="h-48 w-full object-cover md:h-75 md:w-75"
                 src="/images/papeleria-andy-logo.png"
                 alt="Empty Cart"/>
        </div>

        <!-- Descripción -->
        <p class="flex justify-center text-gray-700 dark:text-gray-300">
            {{ settings.description }}
        </p>

        <!-- Contacto -->
        <div class="flex flex-col md:flex-row gap-4">
            
            <a :href="settings.whatsapp_mobile"
               target="_blank"
               class="flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-green-500 text-white hover:bg-green-600 shadow"
               v-if="isMobile">
                <MessageCircle class="w-5 h-5" />
                WhatsApp
            </a>

            <a :href="settings.whatsapp_web"
               target="_blank"
               class="flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500 text-white hover:bg-green-600 shadow"
               v-else>
                <MessageCircle class="w-5 h-5" />
                WhatsApp
            </a>

            <a :href="settings.messenger"
               target="_blank"
               class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow">
                <Facebook class="w-5 h-5" />
                Messenger
            </a>
        </div>

        <!-- Dirección -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Dirección
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                {{ settings.location }}
            </p>

            <!-- Google Maps -->
            <div class="w-full h-80 rounded-xl overflow-hidden shadow-lg">
                <iframe width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        :src="settings.maps_link">
                </iframe>
            </div>
        </div>

        <!-- Horarios -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Horarios de atención
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300
                              dark:border-gray-700 rounded-lg">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-800 dark:text-gray-200">
                                Día
                            </th>
                            <th class="px-4 py-2 text-left text-gray-800 dark:text-gray-200">
                                Horario
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-gray-200 dark:border-gray-700"
                            :key="day"
                            v-for="(entry, day) in settings.schedule">
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">
                                {{ day }}
                            </td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">
                                {{ entry.from }} - {{ entry.to }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<script setup>
    import { MessageCircle, Facebook } from "lucide-vue-next";
    import { isMobile } from 'mobile-device-detect'

    const props = defineProps({
        settings: {
            required: true,
            type: Object
        }
    })
</script>
