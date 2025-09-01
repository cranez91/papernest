<template>
    <Header/>

    <div class="container-fluid mx-auto p-6">
        <!-- Banner -->
        <div class="w-full h-60 mb-8 overflow-hidden relative">
            <img src="/images/papeleria-producto-banner.jpg"
                 alt="Banner producto" 
                 class="w-full h-full object-cover">
        </div>

        <Detail :product="product"></Detail>
    </div>

    <Footer/>
</template>

<script setup>
    import Detail from '@/components/products/Detail.vue';

    const props = defineProps({
        product: Object
    })
</script>