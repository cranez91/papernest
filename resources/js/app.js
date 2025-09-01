import './bootstrap';
import '../css/app.css';
import '@tailwindplus/elements'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import Header from '@/components/layout/Header.vue';
import Footer from '@/components/layout/Footer.vue';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => 
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob("./Pages/**/*.vue")
    ),
  setup({ el, App, props, plugin}) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)

    app.component('Header', Header)
    app.component('Footer', Footer)

    app.mount(el)
  },
});