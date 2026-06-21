import '../css/app.css';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';

import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy';

// ADD PINIA
import { createPinia } from 'pinia'

// IMPORT STORE
import { useLoadingStore } from './pages/stores/LoadingPage';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),

    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {

        const pinia = createPinia()

        const vueApp = createApp({
            render: () => h(App, props)
        })

        vueApp
            .use(plugin)
            .use(pinia) // register pinia
            .use(ZiggyVue, Ziggy)
            .mount(el)

        //  AFTER APP MOUNT → use store
        const loading = useLoadingStore()

        //  Inertia events
        router.on('start', () => {
            loading.start()
        })

        router.on('finish', () => {
            loading.stop()
        })
    },

    progress: {
        color: '#4B5563',
    },
});

initializeTheme();