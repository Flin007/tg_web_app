import { defineStore } from 'pinia';

export const useTelegramUserStore = defineStore('telegramUser', {
    state: () => ({
        user: {},
        //Доступ запрещен?
        accessDenied: false,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        async checkTelegramUserStatus(){
            try{
                await axios.get(`/api/telegram/user/checkStatus?user_id=${this.user.id}`);
            } catch (error) {
                this.accessDenied = true;
            }
        }
    }
});
