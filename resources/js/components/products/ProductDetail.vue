<template>
    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Imagen producto -->
        <div class="flex items-center justify-center">
            <img :src="`/products/${product.photo}`" 
                 :alt="product.name" 
                 class="rounded-xl max-h-[500px] object-cover">
        </div>

        <!-- Info producto -->
        <div class="flex flex-col gap-4">
            <!-- SKU -->
            <span class="text-sm text-gray-500">
                SKU: {{ product.sku }}
            </span>

            <!-- Nombre -->
            <h1 class="text-3xl font-bold text-gray-800">
                {{ product.name }}
            </h1>

            <!-- Precio -->
            <p class="text-2xl font-semibold text-lime-600">
                $ {{ product.price }}
            </p>

            <!-- Descripción -->
            <p class="text-gray-600 leading-relaxed">
                {{ product.description }}
            </p>

            <p class="text-sm text-gray-500">
                Marca: <span class="font-medium"> {{ product.brand }} </span>
            </p>

            <p class="text-sm text-gray-500">
                En existencia: <span class="font-medium"> {{ product.stock }} </span>
            </p>

            <!-- Botón Add to Cart -->
            <button class="mt-4 bg-lime-600 hover:bg-lime-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition duration-200">
                Agregar al carrito
            </button>
        </div>
    </div>
</template>

<script>
    import { ref } from "vue";

    export default {
        data () {
			return {
				
			};
		},
        methods:  {
            addToCart() {
                // Aquí va tu lógica para agregar al carrito
                console.log("Producto agregado:", props.product);
                alert(`Agregado ${props.product.name} al carrito`);
            }
        },
        props: {
            product: {
                type: Object,
                required: true,
            }
        }
    }
</script>