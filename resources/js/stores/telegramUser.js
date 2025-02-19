import { defineStore } from 'pinia';

export const useTelegramUserStore = defineStore('telegramUser', {
    state: () => ({
        user: {},
        //Доступ запрещен?
        accessDenied: true,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        async checkTelegramUserStatus(){
            try{
                await axios.get(`/api/telegram/user/checkStatus?user_id=${this.user.id}`);
                this.accessDenied = false;
            } catch (error) {
                console.log(error)
            }
        }
    }
});
