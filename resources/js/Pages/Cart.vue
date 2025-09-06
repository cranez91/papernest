<template>
    <Header/>

    <!-- Banner -->
    <div class="w-full h-60 mt-20 mb-4 overflow-hidden relative">
        <img src="/images/papeleria-producto-banner-articulos.jpg"
             alt="Banner producto" 
             class="w-full h-full object-cover">
    </div>

    <div class="relative isolate px-6 pt-2 lg:px-8 bg-white"
         v-if="!productsInCart">
        <div class="mx-auto max-w-2xl py-30 sm:py-30 lg:py-30">
            <div class="text-center">
                <div class="md:shrink-0">
                    <img class="h-48 w-full object-cover md:h-full md:w-full"
                        src="/images/empty_cart.jpg"
                        alt="Empty Cart"/>
                </div>
                <p class="mt-4 text-lg font-semibold text-pretty text-black sm:text-xl/8">
                    Aún no has agredado productos
                </p>
                <p class="mt-4 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">
                    Una vez que hayas agregado artículos a tu pedido, aquí podrás visualizarlos.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-16 grid
                grid-cols-1 md:grid-cols-3 gap-8"
         v-else>
        <!-- Columna Izquierda -->
        <div class="md:col-span-2 space-y-6">
            <div class="flex items-center gap-4 border rounded-2xl
                        p-2 shadow-sm bg-white dark:bg-gray-900"
                 :key="item.id"
                 v-for="item in cart">

                <!-- Imagen -->
                <img class="w-24 h-24 object-cover rounded-xl"
                     :alt="item.product.name"
                     :src="`/products/${item.product.photo}`" />

                <!-- Detalles -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ item.product.name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        ${{ item.product.price }}
                    </p>
                </div>

                <!-- Cantidad -->
                <div class="flex items-center space-x-2">
                    <button class="w-10 h-10 flex items-center justify-center rounded-full bg-white
                                   border border-gray-300 dark:border-gray-700 text-gray-700 cursor-pointer
                                   dark:text-gray-200 hover:bg-gray-500 dark:hover:bg-gray-800"
                            @click="decrease(item)">
                        -
                    </button>

                    <input class="w-12 text-center border border-gray-300 dark:border-gray-700
                                  rounded-md bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           type="text"
                           readonly
                           :value="item.quantity"/>

                    <button class="w-10 h-10 flex items-center justify-center rounded-full border bg-sky-950
                                   border-gray-300 dark:border-gray-700 text-gray-100 dark:text-gray-200
                                   hover:bg-gray-500 dark:hover:bg-gray-800 cursor-pointer"
                            @click="increase(item)">
                        +
                    </button>
                </div>

                <!-- Remover -->
                <div class="flex items-center space-x-2">
                    <button class="w-10 h-10 flex items-center justify-center rounded-full bg-red-400
                                   border border-gray-300 dark:border-gray-700 text-white cursor-pointer
                                   dark:text-gray-200 hover:bg-red-600 dark:hover:bg-gray-800"
                            @click="remove(item)">
                        <!-- Heroicons Trash -->
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke-width="1.5" 
                             stroke="currentColor" 
                             class="w-6 h-6">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round" 
                                  d="M6 7.5V6a1.5 1.5 0 011.5-1.5h9A1.5 1.5 0
                                     0118 6v1.5M4.5 7.5h15m-1.5 0v12A1.5 1.5 0
                                     0116.5 21h-9A1.5 1.5 0 016 19.5v-12h12z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Columna Derecha -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-900 border rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Resumen del Pedido
                </h2>

                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <span>Subtotal</span>
                    <span>${{ subtotal }}</span>
                </div>

                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-4">
                    <span>Envío estimado</span>
                    <span>${{ shipping }}</span>
                </div>

                <div class="flex justify-between text-lg font-semibold text-gray-900 dark:text-gray-100 border-t pt-4">
                    <span>Total</span>
                    <span>${{ total }}</span>
                </div>
            </div>

            <!-- Customer Form -->
            <div class="bg-white dark:bg-gray-900 border rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Tu Información
                </h2>

                <form class="space-y-4"
                      @submit.prevent="submitOrder">
                    <div>
                        <label class="block text-sm font-medium text-gray-700
                                      dark:text-gray-300">
                            Nombre Completo *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               required
                               v-model="customer.customer_name"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="email"
                               v-model="customer.email"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Whatsapp *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               required
                               v-model="customer.whatsapp"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Domicilio *
                        </label>
                        <textarea class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                        dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                                  rows="3"
                                  placeholder="Incluye: Calle, Número, Colonia y Entre Calles"
                                  required
                                  v-model="customer.address">
                        </textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-lime-600 text-white py-3 px-4 rounded-xl
                                font-semibold hover:bg-lime-900 transition cursor-pointer">
                        Ordenar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<script setup>
    import { reactive, computed } from "vue";
    import { useCartStore } from "@/stores/cartStore.js";

    const cartStore = useCartStore();
    const cart = computed(() => cartStore.cart)

    const props = defineProps({
        shipping: {
            type: Number,
            required: true
        }
    })

    const subtotal = computed(() =>
        cart.value.reduce(
            (sum, item) => sum + (parseFloat(item.product.price) * item.quantity),
            0
        )
    );

    const total = computed(() => subtotal.value + parseFloat(props.shipping));

    const increase = (item) => {
        const newQuantity = item.quantity + 1;
        if (newQuantity <= item.product.stock ) {
            cartStore.updateQuantity(item.id, item.quantity + 1);
        }
    };

    const decrease = (item) => {
        if (item.quantity > 1) {
            cartStore.updateQuantity(item.id, item.quantity - 1);
        }
    };

    const remove = (item) => {
        cartStore.removeFromCart(item.id);
    }

    const productsInCart = computed(() => cartStore.totalCartItems);

    const customer = reactive({
        customer_name: "",
        email: "",
        whatsapp: "",
        address: "",
    });

    const submitOrder = () => {
        const order = {
            customer: { ...customer },
            items: cart,
            subtotal: subtotal.value,
            shipping: parseFloat(props.shipping),
            total: total.value,
        };

        console.log("Order submitted:", order);
        // Aquí podrías hacer una petición a tu backend con Inertia.post() o Axios
    }
</script>
