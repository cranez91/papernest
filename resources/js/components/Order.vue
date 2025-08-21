<template>
    <div>
        <Header/>

        
        <Footer />
    </div>
</template>

<script>
    import Header from './layout/Header.vue';
    import Footer from './layout/Footer.vue';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    //import { useCartStore } from '../cartStore';

    export default {
        name: 'Order',
        components: {
            Header,
            Footer
        },
        setup() {
            //const cartStore = useCartStore();
            //return { cartStore };
        },
        data() {
            return {
                ingredients: {},
                selectedIngredients: {},
                loading: true,
                singleIngredients: ['Pan', 'Carne']
            };
        },
        mounted() {
            axios.get('/orders')
                .then(response => {
                    this.ingredients = response.data;
                })
                .catch(error => {
                    console.error('Error while fetching the ingredients:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        methods: {
            toggleIngredient(event, category, ingredient) {
                // Get the checkbox
                const checkbox = event.target;

                if (!this.selectedIngredients[category]) {
                    this.selectedIngredients[category] = [];
                }

                if (this.singleIngredients.includes(category)) {
                    // Only one ingrediente for this category
                    const index = this.selectedIngredients[category].findIndex(item => item.id === ingredient.id);
                    
                    if (index === -1 && this.selectedIngredients[category].length) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Solo puedes seleccionar un ingrediente en esta categoría ('+ category +').',
                        });

                        checkbox.checked = false;
                        return;
                    } else if (index >= 0) {
                        this.selectedIngredients[category] = [];
                    } else {
                        this.selectedIngredients[category] = [ingredient];
                    }
                } else {
                    const index = this.selectedIngredients[category].findIndex(item => item.id === ingredient.id);
                    if (index === -1) {
                        this.selectedIngredients[category].push(ingredient);
                    } else {
                        this.selectedIngredients[category].splice(index, 1);
                    }
                }
            },

            isChecked(category, ingredient) {
                return this.selectedIngredients[category] &&
                    this.selectedIngredients[category].some(item => item.id === ingredient.id);
            },

            addToCart() {
                if (!Object.keys(this.selectedIngredients).length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selecciona ingredientes',
                        text: 'Debes elegir al menos un ingrediente antes de agregar al carrito.',
                    });
                    return;
                }

                const newBurger = {
                    id: Date.now(),
                    ingredients: JSON.parse(JSON.stringify(this.selectedIngredients)),
                    totalPrice: this.calculateTotal()
                };

                this.cartStore.addToCart(newBurger);

                Swal.fire({
                    icon: 'success',
                    title: 'Añadido al carrito',
                    text: 'Tu hamburguesa ha sido agregada al carrito.',
                });

                // Limpiar la selección
                this.selectedIngredients = {};
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
            },

            calculateTotal() {
                return Object.values(this.selectedIngredients)
                    .flat()
                    .reduce((sum, ingredient) => sum + parseFloat(ingredient.price), 0);
            }
        }
    };
</script>
