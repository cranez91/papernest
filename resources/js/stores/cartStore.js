import { defineStore } from 'pinia';
import axios from 'axios';

export const useCartStore = defineStore('cart', {
    state: () => ({
        cart: JSON.parse(localStorage.getItem('cart')) || []
    }),
    getters: {
        totalCartPrice(state) {
            return state.cart.reduce((sum, item) => sum + item.product.price , 0)
        },
        totalCartItems(state) {
            return state.cart.reduce((sum, item) => sum + item.quantity, 0)
        }
    },
    actions: {
        async fetchCart() {
            const { data } = await axios.get('/cart');
            this.cart = data;
            this.saveCart();
        },
        async addToCart(sku, quantity = 1) {
            const { data } = await axios.post('/cart/items', { product_sku: sku, quantity });
            await this.fetchCart();
        },
        async updateQuantity(itemId, quantity) {
            await axios.patch(`/cart/items/${itemId}`, { quantity });
            await this.fetchCart();
        },
        async removeFromCart(itemId) {
            await axios.delete(`/cart/items/${itemId}`);
            await this.fetchCart();
        },
        async clearCart() {
            await axios.delete("/cart");
            this.cart = [];
            localStorage.removeItem('cart');
        },
        saveCart() {
            localStorage.setItem('cart', JSON.stringify(this.cart));
        }
    }
})