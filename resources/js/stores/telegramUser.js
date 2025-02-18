import { defineStore } from 'pinia';

export const useTelegramUserStore = defineStore('telegramUser', {
    state: () => ({
        user: {},
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
    }
});
