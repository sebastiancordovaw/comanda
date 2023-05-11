import './bootstrap';
import '../css/app.css';
import '../css/plantilla.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { store } from '@/Store/index.js';
import VueSweetalert2 from 'vue-sweetalert2';
import PrimeVue from 'primevue/config';
import 'sweetalert2/dist/sweetalert2.min.css';

import TreeSelect from 'primevue/treeselect';
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

//PRIMEVUE
//theme
import "primevue/resources/themes/lara-light-indigo/theme.css";

//core
import "primevue/resources/primevue.min.css";

export const eventBus = window.vue;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(store)
            //.use(router)
            .use(VueSweetalert2)
            .use(PrimeVue)
            .component('TreeSelect', TreeSelect)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
