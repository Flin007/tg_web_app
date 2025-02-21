import './bootstrap';
import "../css/app.css";
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
//Телеграм апп
import TelegramWebApp from '@twa-dev/sdk';
//Хранилище для удобной подгрузки авто и рендера
import { createPinia } from 'pinia';
const pinia = createPinia();
//Уведомления
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        const VueApp = createApp({render: () => h(App, props)});
        // Создаем глобальный объект TelegramWebApp
        const tg = TelegramWebApp;
        // Добавляем TelegramWebApp как глобальную свойство
        VueApp.config.globalProperties.$tg = tg;
        // Вызываем tg.ready() для инициализации
        tg.ready();

        // Плагин для уведомлений
        VueApp.use(Vue3Toastify,{
            autoClose: 2000,
            "position": "top-right",
            "newestOnTop": true
        });

        // Остальные плагины и компоненты
        VueApp.use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: "#2563eb",
        showSpinner: true,
    },
})
