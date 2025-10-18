<template>
    <button class="mt-4 cursor-pointer bg-lime-600 hover:bg-lime-700
                   font-semibold py-3 px-6 rounded-xl shadow-md
                   text-white transition duration-200"
            v-if="iconType"
            @click="addProduct">
        <!-- Ícono carrito con Tailwind -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="1.5" 
             stroke="currentColor" 
             class="w-6 h-6 text-white"
             v-if="!loading">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  d="M2.25 2.25h1.386c.51 0 .955.343 1.087.835l.383 1.437m0 
                     0L6.75 13.5h10.878a1.125 1.125 0 001.1-.862l1.5-6A1.125 
                     1.125 0 0018.25 5.25H5.108m0 0L4.5 2.25M6 16.5a1.5 
                     1.5 0 100 3 1.5 1.5 0 000-3zm9 0a1.5 1.5 0 100 3 
                     1.5 1.5 0 000-3z" />
        </svg>
        <span v-else>...</span>
    </button>
    <button class="mt-4 cursor-pointer bg-lime-600 hover:bg-lime-700
                   font-semibold py-3 px-6 rounded-xl shadow-md
                   transition duration-200 text-white"
            v-else
            @click="addProduct">
        <span v-if="!loading">
            Agregar al carrito
        </span>
        <span v-else>...</span>
    </button>
</template>

<script setup>
    import { ref } from 'vue'
    import { useCartStore } from '@/stores/cartStore.js';
    import Swal from 'sweetalert2';

    const cartStore = useCartStore();

    let loading = ref(false);

    const props = defineProps({
        product: {
            type: Object,
            required: true
        },
        iconType: {
            type: Boolean,
            default: true
        }
    })

    const addProduct = async () => {
        if (loading.value) {
            return;
        }

        loading.value = true;
        try {
            await cartStore.addToCart(props.product.sku);

            Swal.fire({
                icon: 'success',
                title: 'Añadido',
                text: 'El producto se agregó al carrito.',
                showConfirmButton: true,
                allowOutsideClick: false
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar el producto al carrito. Intenta de nuevo.',
                showConfirmButton: true,
                allowOutsideClick: false,
            });
        } finally {
            loading.value = false
        }
    }
</script>