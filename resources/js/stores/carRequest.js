import { defineStore } from 'pinia';

export const useCarRequest = defineStore('carRequest', {
    state: () => ({
        selectedCar: {},
        shouldUseCredit: false,
        creditDeposit: 0,
        shouldUseTradeIn: false,
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
        resetAll() {
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
                selectedCar: this.selectedCar,
                useCredit: this.shouldUseCredit,
                creditDeposit: this.creditDeposit,
                useTradeIn: this.shouldUseTradeIn,
                tradeInCar: this.tradeInCar
            };
        }
    }
});
