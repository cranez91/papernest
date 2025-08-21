<template>
    <footer class="bg-gray-900 text-white">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
            <!-- Logo and description -->
            <div>
                <h2 class="text-lg font-semibold">TuMarca</h2>
                <p class="mt-4 text-sm text-gray-400">
                Creamos experiencias digitales que conectan personas. Inspirados en la simplicidad y el impacto.
                </p>
            </div>

            <!-- Links -->
            <div class="mt-10 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                <div>
                <h3 class="text-sm font-semibold leading-6 text-white">Empresa</h3>
                <ul role="list" class="mt-4 space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Acerca de</a></li>
                    <li><a href="#" class="hover:text-white">Blog</a></li>
                    <li><a href="#" class="hover:text-white">Trabaja con nosotros</a></li>
                    <li><a href="#" class="hover:text-white">Contacto</a></li>
                </ul>
                </div>
                <div>
                <h3 class="text-sm font-semibold leading-6 text-white">Legal</h3>
                <ul role="list" class="mt-4 space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Términos</a></li>
                    <li><a href="#" class="hover:text-white">Privacidad</a></li>
                    <li><a href="#" class="hover:text-white">Cookies</a></li>
                </ul>
                </div>
            </div>
            </div>

            <!-- Bottom -->
            <div class="mt-12 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
            <p class="text-sm text-gray-400">&copy; 2025 TuMarca. Todos los derechos reservados.</p>
            <div class="mt-4 flex space-x-6 md:mt-0">
                <a href="https://www.facebook.com/profile.php?id=100063889492107"
                   class="text-gray-400 hover:text-white"
                   target="_blank">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22 12c0-5.522-4.478-10-10-10S2 6.478 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.988H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z"/>
                    </svg>
                </a>
            </div>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {
        name: 'Footer'
    }
</script>