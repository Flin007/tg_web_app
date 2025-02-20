import { defineStore } from 'pinia';
import axios from "axios";
import {data} from "autoprefixer";

export const useCarRequest = defineStore('carRequest', {
    state: () => ({
        //Открыто ли окно
        isOpen: false,
        //Загружается ли окно
        isLoading: false,
        //Можно ли жать кнопку далее
        isNextStepBtnDisabled: false,
        //Номер обращения
        requestId: null,
        //Текущей номер этапа в заполнении заявки
        currentStep: 0,
        //Предыдущий номер этапа
        prevStep: 0,
        //Выбранная машина
        selectedCar: {},
        //Тип покупки авто
        purchasingOption: null,
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
        //Контакты клиента
        contacts: {
            name: '',
            surname: '',
            phone: '',
            email: ''
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
                const result = await axios.post('/request/create', {car_id: car.id, user_id: userId});
                //TODO: для теста что б не спамить каждый раз в бд
                //const result = {data:{id:1}};
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
            this.currentStep = 0;
            this.prevStep = 0;
            this.isNextStepBtnDisabled = false;
            this.selectedCar = {};
            this.purchasingOption = null;
            this.creditDeposit = 0;
            this.shouldUseTradeIn = false;
            this.tradeInCar.brand = null;
            this.tradeInCar.model = null;
            this.tradeInCar.year = null;
            this.tradeInCar.vin = null;
            this.contacts.name = null;
            this.contacts.surname = null;
            this.contacts.phone = null;
            this.contacts.email = null;
            this.data = {};
        },
        updateData() {
            this.data = {
                selectedCarId: this.selectedCar.id,
                purchasingOption: this.purchasingOption,
                creditDeposit: this.creditDeposit,
                shouldUseTradeIn: this.shouldUseTradeIn,
                tradeInCar: this.tradeInCar,
                contacts: this.contacts
            };
            //TODO: вернуть синхронизацию перед релизом
            axios.post('/request/update', {request_id: this.requestId, data: JSON.stringify(this.data), finished: false});
        }
    }
});
