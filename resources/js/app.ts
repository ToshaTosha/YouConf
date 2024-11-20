import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
// import { InertiaProgress } from '@inertiajs/progress';

import Layout from './Common/Layout.vue';

createInertiaApp({
    resolve: async name => {
        const page = await import(`./Pages/${name}.vue`);
        return page.default;
    },
    setup({ el, app, props }) {
        // Оборачиваем приложение в лаяут
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
