<template>
    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Imagen producto -->
        <div class="flex items-center justify-center">
            <img class="rounded-xl max-h-[500px] object-cover"
                 :src="`/products/${product.photo}`" 
                 :alt="product.name">
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

            <p class="text-sm text-gray-500">
                Etiquetas: <span v-html="tagsList"></span>
            </p>

            <add-to-cart :product="product"
                         :icon-type="false">
            </add-to-cart>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        product: Object
    })

    const tagsList = computed(function() {
        let list = '';

        for (let index = 0; index < props.product.tags.length; index++) {
            const tag = props.product.tags[index];
            list += `<a href='/articulos?search=${tag.name}'
                        class="font-medium underline underline-offset-6">
                        ${tag.name}
                     </a>`;
            if (index < (props.product.tags.length - 1)) {
                list += ' | ';
            }
        }

        return list;
    });
</script>