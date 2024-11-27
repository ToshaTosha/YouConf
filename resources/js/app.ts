import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import '../css/app.css';
// import { InertiaProgress } from '@inertiajs/progress';

import Layout from './Common/Layout.vue';

createInertiaApp({
    resolve: async name => {
        const parts = name.split('/');
        const pagePath = `./Pages/${parts.join('/')}.vue`;

        try {
            const page = await import(pagePath);
            return page.default;
        } catch (error) {
            console.error(`Component not found: ${pagePath}`, error);
            return null;
        }
    },
    setup({ el, app, props }) {
        const App = {
            render() {
                return h(Layout, null, {
                    default: () => h(app, props),
                });
            },
        };

        createApp(App).mount(el);
    },
});

// InertiaProgress.init();
