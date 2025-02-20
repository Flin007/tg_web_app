import { defineStore } from 'pinia';
import axios from "axios";

export const useCarRequest = defineStore('carRequest', {
    state: () => ({
        //Открыто ли окно
        isOpen: false,
        //Загружается ли окно
        isLoading: false,
        //Номер обращения
        requestId: null,
        //Выбранная машина
        selectedCar: {},
        //Берем ли в кредит?
        shouldUseCredit: false,
        //Первоначальный взнос
        creditDeposit: 0,
        //Сдаем ли авто в трейдИн
        shouldUseTradeIn: false,
        //Инфо о машине в трейдИн
        tradeInCar: {
            brand: null,
            model: null,
            year: null,
            vin: null,
        },
        //Для удобной отправки на бэк
        data: {}
    }),
    actions: {
        async createRequest(car, userId) {
            if (!car || !userId) {
                return;
            }
            this.isLoading = true;
            this.resetAll();
            this.selectedCar = car;

            //Пробуем создать реквест в бд
            try {
                //const result = await axios.post('/request/create', {car_id: car.id, user_id: userId});
                //TODO: для теста что б не спамить каждый раз в бд
                const result = {data:{id:123}};
                this.requestId = result.data.id;
                this.isOpen = true;
            } catch (e) {
                this.isOpen = false;
            } finally {
                this.isLoading = false;
            }
        },
        resetAll() {
            this.requestId = null;
            this.selectedCar = {};
            this.shouldUseCredit = false;
            this.creditDeposit = 0;
            this.shouldUseTradeIn = false;
            this.tradeInCar.brand = null;
            this.tradeInCar.model = null;
            this.tradeInCar.year = null;
            this.tradeInCar.vin = null;
            this.data = {};
        },
        updateData() {
            this.data = {
                requestId: this.requestId,
                selectedCar: this.selectedCar,
                useCredit: this.shouldUseCredit,
                creditDeposit: this.creditDeposit,
                useTradeIn: this.shouldUseTradeIn,
                tradeInCar: this.tradeInCar
            };
        }
    }
});
