<template>
    <!-- Сумма первоначального взноса -->
    <div v-if="['Credit', 'Installment'].includes(carRequestStore.purchasingOption?.name)">
        <h2 class="text-2xl text-gray-800 font-light">Рассчитаем платежи:</h2>
        <div class="mt-3">
            <label for="creditDeposit" class="block text-sm font-medium leading-6 text-gray-900">Сумма первоначального платежа:</label>
            <div class="relative mt-2 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm">₽</span>
                </div>
                <input type="text"
                       v-model="formattedCreditDeposit"
                       @input="restrictToNumbers"
                       name="creditDeposit"
                       id="creditDeposit"
                       class="block w-full rounded-md border-0 py-1.5 pl-7 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                       placeholder="0.00"
                       aria-describedby="price-currency"
                />
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <span class="text-gray-500 sm:text-sm" id="price-currency">РУБ</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Информация по TradeIn -->
    <div class="mt-8" v-if="carRequestStore.shouldUseTradeIn">
        <h2 class="text-2xl text-gray-800 font-light">Рассчитаем стоимость авто:</h2>
        <!-- Марка -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="brand" class="block text-sm font-medium leading-6 text-gray-900">Марка</label>
            </div>
            <div class="mt-2">
                <input type="text"
                       v-model="carRequestStore.tradeInCar.brand"
                       @input="checkNextBtnStatus"
                       name="brand"
                       id="brand"
                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                       placeholder="Mazda"
                       aria-describedby="brand-optional"
                />
            </div>
        </div>
        <!-- Модель -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="model" class="block text-sm font-medium leading-6 text-gray-900">Модель</label>
            </div>
            <div class="mt-2">
                <input type="text"
                       v-model="carRequestStore.tradeInCar.model"
                       @input="checkNextBtnStatus"
                       name="model"
                       id="model"
                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                       placeholder="CX 5"
                       aria-describedby="model-optional"
                />
            </div>
        </div>
        <!-- Год -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="year" class="block text-sm font-medium leading-6 text-gray-900">Год выпуска</label>
            </div>
            <div class="mt-2">
                <input type="text"
                       v-model="carRequestStore.tradeInCar.year"
                       @input="checkNextBtnStatus"
                       name="year"
                       id="year"
                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                       placeholder="2008"
                       aria-describedby="year-optional"
                />
            </div>
        </div>
        <!-- VIN -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="vin" class="block text-sm font-medium leading-6 text-gray-900">VIN</label>
                <span class="text-sm leading-6 text-gray-500" id="vin-optional">Необязательное</span>
            </div>
            <div class="mt-2">
                <input type="text"
                       v-model="carRequestStore.tradeInCar.vin"
                       name="vin"
                       id="vin"
                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                       placeholder="LVVDB2130R145688"
                       aria-describedby="vin-optional"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { RadioGroup, RadioGroupOption, Switch } from '@headlessui/vue';
import { useCarRequest } from '../../stores/carRequest.js';
import { formatPrice } from "../../utils/formattingUtils.js";

const carRequestStore = useCarRequest();

const skipCurrentStep = () => {
    if (carRequestStore.prevStep > carRequestStore.currentStep) {
        carRequestStore.prevStep = carRequestStore.currentStep;
        carRequestStore.currentStep--;
    } else {
        carRequestStore.prevStep = carRequestStore.currentStep;
        carRequestStore.currentStep++;
    }
}

const checkNextBtnStatus = () => {
    carRequestStore.isNextStepBtnDisabled = isNextStepDisabled();
}

// Проверка заполненности всех обязательных полей
const isNextStepDisabled = () => {
    // Проверка для Credit или Installment
    if (['Credit', 'Installment'].includes(carRequestStore.purchasingOption?.name)) {
        const deposit = carRequestStore.creditDeposit;
        if (deposit === null || deposit === undefined || deposit === '' || deposit === 0) {
            return true;
        }
    }

    // Проверка для Trade-In
    if (carRequestStore.shouldUseTradeIn) {
        const tradeIn = carRequestStore.tradeInCar;
        if (
            !tradeIn.brand || tradeIn.brand === '' ||
            !tradeIn.model || tradeIn.model === '' ||
            //!tradeIn.vin || tradeIn.vin === '' ||
            !tradeIn.year || tradeIn.year === '' || tradeIn.year === 0
        ) {
            return true;
        }
    }

    return false; // Все поля заполнены, кнопка активна
};

// Синхронизация со стором при загрузке компонента
onMounted(() => {
    if (!['Credit', 'Installment'].includes(carRequestStore.purchasingOption?.name) && !carRequestStore.shouldUseTradeIn) {
        skipCurrentStep();
    } else {
        checkNextBtnStatus();
    }
});


// Локальная переменная для управления вводом
const localCreditDeposit = ref(carRequestStore.creditDeposit || 0);

// Вычисляемое свойство для форматированного отображения
const formattedCreditDeposit = computed({
    get() {
        return formatPrice(localCreditDeposit.value);
    },
    set(newValue) {
        // Удаляем все нечисловые символы и преобразуем в число
        const numericValue = parseInt(newValue.replace(/\D/g, '')) || 0;
        localCreditDeposit.value = numericValue;
        carRequestStore.creditDeposit = numericValue;
        checkNextBtnStatus();
    }
});

// Ограничение ввода только цифр
const restrictToNumbers = (event) => {
    const value = event.target.value.replace(/\D/g, ''); // Удаляем все нечисловые символы
    event.target.value = formatPrice(value || 0); // Форматируем и обновляем значение в input
    localCreditDeposit.value = parseInt(value) || 0; // Обновляем числовое значение
    carRequestStore.creditDeposit = localCreditDeposit.value; // Синхронизация со стором
};
</script>
