import { createApp } from 'vue';
import { createPinia } from 'pinia';
import axios from 'axios';

// Importar componentes
// import App from './components/App.vue';
import Contact from './components/Contact.vue';
import HomeChatBot from './components/HomeChatBot.vue';
import About from './components/About.vue';
import Order from './components/Order.vue';
import Cart from './components/Cart.vue';
import ProductDetail from './components/products/ProductDetail.vue';

// Configurar Axios
axios.defaults.baseURL = '/api'; // Cambia según tus necesidades

// Crear la app de Vue
//const app = createApp(App);
//const app = createApp({})
//app.use(createPinia());

//app.component('contact', Contact)
//app.component('home', Home)
//app.component('about', About)
//app.component('order', Order)
//app.component('cart', Cart)
//app.component('product-detail', ProductDetail)

const components = {
  'home-chat-bot': HomeChatBot,
  'contact': Contact,
  'about': About,
  'order': Order,
  'cart': Cart,
  'product-detail': ProductDetail
}

/*
// Montar el componente correcto basado en la ruta
const appDiv = document.getElementById('app');
if (window.location.pathname === '/contact') {
    // Si estamos en la ruta /contact, asignar el componente Contact
    app.component('contact', Contact);
    app.mount(appDiv);
} else {
    // Si estamos en la ruta principal o cualquier otra, asignar el componente App
    app.mount(appDiv);
}
*/
/*
const appDiv = document.getElementById('app');
if (appDiv) {
    app.mount(appDiv);
}
*/
// Busca en el DOM etiquetas con esos nombres y las monta automáticamente
Object.entries(components).forEach(([name, component]) => {
  document.querySelectorAll(name).forEach(el => {
    const props = {}

    // convierte dataset a props válidas
    Object.entries(el.dataset).forEach(([key, value]) => {
      try {
        props[key] = JSON.parse(value) // intenta parsear JSON
      } catch {
        props[key] = value // si no es JSON, lo deja como string
      }
    })

    const app = createApp(component, props)
    app.use(createPinia())
    app.mount(el)
  })
})