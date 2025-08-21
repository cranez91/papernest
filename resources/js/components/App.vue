<template>
    <div>
        <!-- Rendering the component -->
        <component :is="currentComponent" />
    </div>
</template>

<script>
    import Contact from './Contact.vue';
    import Home from './Home.vue';
    import About from './About.vue';
    import Order from './Order.vue';
    import Cart from './Cart.vue';

    export default {
        name: 'App',
        data() {
            return {
                // Mapping the routes for each component
                routes: {
                    '/': Home,
                    '/contact': Contact,
                    '/about': About,
                    '/order': Order,
                    '/cart': Cart
                },
                currentComponent: null, // Initilize the current component
            };
        },
        created() {
            // Determine the current component according to the route
            this.currentComponent = this.routes[window.location.pathname] || Home;
        },
    };
</script>
