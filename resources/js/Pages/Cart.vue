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
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-1">
                        {{ item.product.name }}
                    </h3>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        ${{ item.product.price }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ item.product.stock }} diponible(s) | <strong>${{ item.product.price * item.quantity }}</strong>
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

                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-2"
                     v-if="validCoupons.length">
                    <span>Cupón</span>
                    <span>
                        <select class="border-1 border-gray-600 cursor-pointer"
                                v-model="form.customer.coupon_code">
                            <option value=""
                                    selected>
                                Elije un cupón
                            </option>
                            <option :key="coupon.code"
                                    :value="coupon.code"
                                    v-for="coupon in validCoupons">
                                {{ coupon.description }}
                            </option>
                        </select>
                    </span>
                </div>

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
                            Nombre *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               placeholder="Nombre(s) y Apellido(s)"
                               required
                               v-model="form.customer.name"/>
                        <div v-if="form.errors['customer.name']">
                            {{ form.errors['customer.name'] }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="email"
                               v-model="form.customer.email"/>
                        <div v-if="form.errors['customer.email']">
                            {{ form.errors['customer.email'] }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Whatsapp *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               placeholder="Número a 10 dígitos"
                               minlength="10"
                               maxlength="10"
                               required
                               v-model="form.customer.whatsapp"/>
                        <div v-if="form.errors['customer.whatsapp']">
                            {{ form.errors['customer.whatsapp'] }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Domicilio *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               placeholder="Calle y Número"
                               required
                               v-model="form.customer.address"/>
                        <div v-if="form.errors['customer.address']">
                            {{ form.errors['customer.address'] }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Municipio *
                        </label>
                        <select class="border-1 border-gray-600 cursor-pointer mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                                v-model="form.customer.city">
                            <option value="uriangato">
                                Uriangato
                            </option>
                            <option value="moroleon">
                                Moroleon
                            </option>
                        </select>
                        <div v-if="form.errors['customer.city']">
                            {{ form.errors['customer.city'] }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Colonia *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               required
                               v-model="form.customer.neighborhood"/>
                        <div v-if="form.errors['customer.neighborhood']">
                            {{ form.errors['customer.neighborhood'] }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Entre Calles *
                        </label>
                        <input class="mt-1 w-full border rounded-md px-3 py-2 text-sm
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                               type="text"
                               required
                               v-model="form.customer.streets"/>
                        <div v-if="form.errors['customer.streets']">
                            {{ form.errors['customer.streets'] }}
                        </div>
                    </div>

                    <!-- Checkbox de aceptación -->
                    <div class="flex items-start space-x-2">
                        <input id="accept_terms"
                               class="mt-1 h-4 w-4 text-lime-600 border-gray-300 rounded cursor-pointer"
                               type="checkbox"
                               required
                               v-model="form.accepted"/>
                        <label for="accept_terms"
                               class="text-sm text-gray-700 dark:text-gray-300">
                            Acepto los
                            <a :href="route('tos')"
                               class="text-lime-700 dark:text-lime-400 hover:underline"
                               target="_blank">
                                Términos y Condiciones
                            </a>
                            y la
                            <a :href="route('privacy')"
                               class="text-lime-700 dark:text-lime-400 hover:underline"
                               target="_blank">
                                Política de Privacidad
                            </a>.
                        </label>
                    </div>

                    <button type="submit"
                            :disabled="form.processing"
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
    import { computed } from 'vue';
    import { useCartStore } from '@/stores/cartStore.js';
    import Swal from 'sweetalert2';
    import { v4 as uuidv4 } from 'uuid';
    import { useForm, router } from '@inertiajs/vue3';
    import axios from 'axios';

    const cartStore = useCartStore();
    const cart = computed(() => cartStore.cart)

    const props = defineProps({
        shipping: {
            type: Number,
            required: true
        },
        coupons: {
            type: Array,
            required: true
        }
    })

    const subtotal = computed(() =>
        cart.value.reduce(
            (sum, item) => sum + (parseFloat(item.product.price) * item.quantity),
            0
        )
    );

    const total = computed(() => {
        const subtotalShipping = subtotal.value + parseFloat(props.shipping);
        let discount = 0;

        if (form.customer.coupon_code) {
            const coupon = props.coupons.find((coupon) => coupon.code == form.customer.coupon_code);
            discount = (coupon ? coupon.discount_percentage / 100 : 0) * subtotal.value;
        }

        return subtotalShipping - discount;
    });

    const validCoupons = computed(() => 
        props.coupons.filter((coupon) => coupon.min_total <= subtotal.value)
    );

    const increase = (item) => {
        form.customer.coupon_code = '';
        const newQuantity = item.quantity + 1;
        if (newQuantity <= item.product.stock ) {
            cartStore.updateQuantity(item.id, item.quantity + 1);
        }
    };

    const decrease = (item) => {
        form.customer.coupon_code = '';
        if (item.quantity > 1) {
            cartStore.updateQuantity(item.id, item.quantity - 1);
        }
    };

    const remove = (item) => {
        cartStore.removeFromCart(item.id);
    }

    const productsInCart = computed(() => cartStore.totalCartItems);

    const orderUuid = uuidv4()

    const form = useForm({
        //_method: 'PUT',
        customer: {
            name: '',
            email: '',
            whatsapp: '',
            address: '',
            neighborhood: '',
            city: 'uriangato',
            streets: '',
            coupon_code: '',
        },
        items: [], // se llenará desde el carrito
        subtotal: 0,
        shipping: 0,
        total: 0,
        accepted: false,
        processing: false,
    })

    const submitOrder = async () => {
        if (!validForm() || !form.accepted) {
            return;
        }

        if (!form.accepted) {
            Swal.fire({
                icon: 'warning',
                title: 'Espera',
                text: 'Debes aceptar nuestros términos y condiciones y el aviso de privacidad para poder continuar.',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            return;
        }

        // mapear el carrito
        form.items = cart.value.map(item => ({
            id: item.id,
            quantity: item.quantity,
            shopping_cart: item.shopping_cart,
            product: {
                sku: item.product.sku,
                name: item.product.name,
                price: item.product.price,
            }
        }));
        form.subtotal = subtotal.value;
        form.shipping = parseFloat(props.shipping);
        form.total = total.value;

        try {
            const response = await axios.put(`/orders/${orderUuid}`, form);

            Swal.fire({
                icon: 'success',
                title: 'Orden Creada',
                text: 'Estás siendo redireccionado a la confirmación de tu pedido...',
                timer: 3000,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            await cartStore.clearCart();

            // Redireccionar a la página de confirmación
            // Redirigir con Inertia
            router.visit(response.data.confirmation_url)
        } catch (error) {
            if (error.response?.status === 422) {
                // Validación fallida
                const html = Object.values(error.response.data.errors)
                    .flat()
                    .join('<br>');

                Swal.fire({
                    icon: 'error',
                    title: 'Ocurrió un error',
                    html,
                });
            } else {
                const msg = error.response?.data?.error || 'Ocurrió un error inesperado';
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: msg,
                });
            }
        }
    }

    const validForm = () => {
        let valid = true;

        // Validación básica: todos excepto email son obligatorios
        if (!form.customer.name ||
            !form.customer.whatsapp ||
            !form.customer.address ||
            !form.customer.neighborhood ||
            !form.customer.streets) {

            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos',
                text: 'Por favor completa todos los campos obligatorios.',
                confirmButtonText: 'Ok',
                allowOutsideClick: false
            });

            valid = false;
        }

        // Validar email si no está vacío
        if (form.customer.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.customer.email)) {
            Swal.fire({
                icon: 'error',
                title: 'Email inválido',
                text: 'Por favor ingresa un email válido.',
                confirmButtonText: 'Ok',
                allowOutsideClick: false
            });

            valid = false;
        }

        return valid;
    }
</script>
