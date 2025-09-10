<template>
    <Link :href="route('cart')"
          class="text-sm/6 font-semibold text-gray-900">
        <svg viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="1.5"
             data-slot="icon"
             aria-hidden="true"
             class="size-6 shrink-0 text-gray-400 group-hover:text-gray-500">
            <path d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263
                     12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0
                     1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0
                     1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0
                     0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
        </svg>
        <span class="ml-2 text-sm font-medium text-gray-700
                     group-hover:text-gray-800">
            {{ productsInCart }}
        </span>
        <span class="sr-only">
            items in cart, view bag
        </span>
    </Link>
</template>

<script setup>
    import { computed, onMounted } from "vue";
    import { useCartStore } from '@/stores/cartStore.js';

    const cartStore = useCartStore();
    const productsInCart = computed(() => cartStore.totalCartItems);

    onMounted(() => {
        cartStore.fetchCart();
        
    })
</script>