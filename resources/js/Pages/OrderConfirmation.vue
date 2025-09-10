<template>
    <Header/>

    <!-- Banner -->
    <div class="w-full h-60 mt-20 mb-4 overflow-hidden relative">
        <img src="/images/papeleria-producto-banner-articulos.jpg"
             alt="Banner producto" 
             class="w-full h-full object-cover">
    </div>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-6">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <div class="flex justify-center md:shrink-0 mt-2 mb-8">
                <img class="h-48 w-full object-cover md:h-75 md:w-75"
                    src="/images/papeleria-andy-logo.png"
                    alt="Empty Cart"/>
            </div>

            <!-- Icono de éxito -->
            <div class="flex justify-center">
                <div class="w-16 h-16 flex items-center justify-center
                            rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-300"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <!-- Mensaje -->
            <div class="text-center mt-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    ¡Gracias por comprar con nosotros!
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Tu pedido se ha generado correctamente.
                </p>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Te contactaremos por whatsapp para mantenerte al tanto del envío.
                </p>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Te recomendamos contar con cambio al momento de pagar al repartidor.
                </p>
            </div>

            <!-- Resumen de la orden -->
            <div class="mt-8 border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Resumen del Pedido
                </h2>

                <div class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <p>
                        <span class="font-semibold">ID:</span> {{ order.id }}
                    </p>
                    <p>
                        <span class="font-semibold">Cliente:</span> {{ order.customer_name }}
                    </p>
                    <p>
                        <span class="font-semibold">Subtotal:</span> ${{ order.subtotal }}
                    </p>
                    <p v-if="order.coupon">
                        <span class="font-bold">Descuento: {{ order.coupon.discount_percentage }}%</span> 
                    </p>
                    <p v-if="order.coupon">
                        <span class="font-semibold">Subtotal con Descuento: ${{ parseFloat(order.subtotal) - ( (parseFloat(order.coupon.discount_percentage) / 100) * parseFloat(order.subtotal)) }}</span> 
                    </p>
                    <p>
                        <span class="font-semibold">Envío:</span> ${{ order.shipping_price }}
                    </p>
                    <p>
                        <span class="font-bold">Total:</span> ${{ order.total }}
                    </p>
                </div>
            </div>

            <!-- Productos -->
            <div class="mt-6 border-t pt-6">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                    Artículos
                </h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700 mt-3">
                    <li class="flex justify-between py-3"
                        :key="item.id"
                        v-for="item in order.items">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                {{ item.product.name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Precio: ${{ item.product.price }} | Cantidad: {{ item.quantity }}
                            </p>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            ${{ (item.price * item.quantity).toFixed(2) }}
                        </p>
                    </li>
                </ul>
            </div>

            <!-- Botón regresar -->
            <div class="mt-8 text-center">
                <button class="px-6 py-3 rounded-xl bg-lime-600 text-white cursor-pointer
                               font-medium hover:bg-lime-700 transition"
                        @click="$inertia.visit(route('products.list'))">
                    Seguir Comprando
                </button>
            </div>
        </div>
    </div>

    <Footer/>
</template>


<script setup>
    const props = defineProps({
        order: Object
    });
</script>