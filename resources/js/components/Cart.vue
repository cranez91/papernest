<template>
    <div>
        <Header/>


        <Footer />
    </div>
</template>

<script>
    import Header from './layout/Header.vue';
    import Footer from './layout/Footer.vue';
    // import { useCartStore } from '../cartStore';

    export default {
        name: 'Cart',
        components: {
            Header,
            Footer
        },
        setup() {
            //const cartStore = useCartStore();
            //return { cartStore };
        }
    };
</script>
