import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import '../css/app.css';
// import { InertiaProgress } from '@inertiajs/progress';

import DefaultLayout from '@/Common/Layout.vue'; // Основной макет по умолчанию
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
    // Если страница указывает на использование другого макета, используем его
    if (page.layout) {
        return page.layout;
    }
    // Иначе используем макет по умолчанию
    return DefaultLayout;
};

createInertiaApp({
    resolve: async (name) => {
        const page = await resolvePageComponent(name);

<<<<<<< HEAD
        try {
            const page = await import(pagePath);
            page.default.layout = page.default.layout || Layout
            return page.default;
        } catch (error) {
            console.error(`Component not found: ${pagePath}`, error);
            return null;
        }
    },
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
          .use(plugin)
          .mount(el)
      },
=======
        // Если страница не найдена, возвращаем компонент для ошибки 404
        // if (page === NotFound) {
        //     return {
        //         default: NotFound,
        //         layout: DefaultLayout, // Используем макет по умолчанию для ошибки 404
        //     };
        // }

        // Возвращаем страницу с указанным макетом
        return {
            default: page,
            layout: resolveLayout(page),
        };
    },
    setup({ el, app, props, plugin }) {
        const App = {
            render() {
                const Layout = props.initialPage.component.layout || DefaultLayout;
                return h(Layout, null, {
                    default: () => h(app, props),
                });
            },
        };

        const vueApp = createApp(App);
        vueApp.use(plugin);
        vueApp.mount(el);
    },
>>>>>>> some-fix
});

// InertiaProgress.init();