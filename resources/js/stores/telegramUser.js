import { defineStore } from 'pinia';

export const useTelegramUserStore = defineStore('telegramUser', {
    state: () => ({
        user: {},
        //Доступ запрещен?
        accessDenied: true,
        //Валидные данные?
        isValidData: false,
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
        },
        async verifyInitialData(initData){
            try{
                await axios.post('api/telegram/user/verify', initData)
                this.isValidData = true;
            } catch (error) {
                console.log(error)
            }
        }
    }
});
