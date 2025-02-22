import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import '../css/app.css';
// import { InertiaProgress } from '@inertiajs/progress';

import DefaultLayout from '@/Common/DefaultLayout.vue'; // Основной макет по умолчанию
// import NotFound from './Pages/Errors/NotFound.vue'; // Компонент для ошибки 404

/**
 * Загружает компонент страницы по имени.
 * Если страница не найдена, возвращает компонент для ошибки 404.
 */
const resolvePageComponent = async (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue');

    const pagePath = `./Pages/${name.split('/').join('/')}.vue`;

    if (pages[pagePath]) {
        const page = await pages[pagePath]();
        return page.default;
    } else {
        console.error(`Component not found: ${pagePath}`);
        // return NotFound; // Возвращаем компонент для ошибки 404
    }
};

/**
 * Возвращает макет для страницы.
 * Если макет не указан, используется макет по умолчанию.
 */
const resolveLayout = (page) => {
    if (page.meta?.layout) {
        return page.meta?.layout;
    }
    return DefaultLayout;
};

createInertiaApp({
    resolve: async (name) => {
        const page = await resolvePageComponent(name);
        return {
            default: page,
            layout: resolveLayout(page),
        };
    },
    setup({ el, app, props, plugin }) {
        const App = {
            render() {
                const Layout = props.initialComponent.meta?.layout || DefaultLayout;
                return h(Layout, null, {
                    default: () => h(app, props),
                });
            },
        };

        const vueApp = createApp(App);
        vueApp.use(plugin);
        vueApp.mount(el);
    },
});

// InertiaProgress.init();