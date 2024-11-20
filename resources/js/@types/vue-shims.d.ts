declare module '*.vue' {
    import { DefineComponent } from 'vue';
    const component: DefineComponent<{}, {}, any>;
    export default component;
}
declare module '@inertiajs/inertia-vue3';
declare module '@headlessui/vue';
declare module '@heroicons/vue/24/outline';
