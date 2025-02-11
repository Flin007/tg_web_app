import './bootstrap';
import "../css/app.css";
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
//Телеграм апп
import TelegramWebApp from '@twa-dev/sdk';

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

        // Остальные плагины и компоненты
        VueApp.use(plugin)
            .mount(el);
    },
    progress: {
        color: "#2563eb",
        showSpinner: true,
    },
})
