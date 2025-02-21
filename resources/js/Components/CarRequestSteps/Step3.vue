<template>
    <!-- Контакты -->
    <div>
        <h2 class="text-2xl text-gray-800 font-light">Ваши контакты:</h2>
        <!-- Имя -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
            </div>
            <div class="mt-2">
                <input
                    type="text"
                    @input="checkNextBtnStatus"
                    v-model="carRequestStore.contacts.name"
                    name="name"
                    id="name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    placeholder="Иванов"
                    aria-describedby="name-optional"
                />
            </div>
        </div>
        <!-- Фамилия -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="surname" class="block text-sm font-medium leading-6 text-gray-900">Фамилия</label>
            </div>
            <div class="mt-2">
                <input
                    type="text"
                    @input="checkNextBtnStatus"
                    v-model="carRequestStore.contacts.surname"
                    name="surname"
                    id="surname"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    placeholder="Иванов"
                    aria-describedby="surname-optional"
                />
            </div>
        </div>
        <!-- Номер -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Номер телефона</label>
            </div>
            <div class="mt-2">
                <input
                    type="text"
                    @input="restrictToNumbers"
                    v-model="formattedPhone"
                    name="phone"
                    id="phone"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    placeholder="+7 (999) 999-99-99"
                    aria-describedby="phone-optional"
                />
            </div>
        </div>
        <!-- Email -->
        <div class="mt-3">
            <div class="flex justify-between">
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <span class="text-sm leading-6 text-gray-500" id="email-optional">Необязательное</span>
            </div>
            <div class="mt-2">
                <input
                    type="text"
                    v-model="carRequestStore.contacts.email"
                    name="email"
                    id="email"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    placeholder="example@mail.ru"
                    aria-describedby="email-optional"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useCarRequest } from '../../stores/carRequest.js';
import { useTelegramUserStore } from "../../stores/telegramUser.js";

const carRequestStore = useCarRequest();
const telegramUserStore = useTelegramUserStore();

// Форматирование номера телефона
const formatPhoneNumber = (digits) => {
    // Удаляем все нечисловые символы
    let cleaned = digits.replace(/\D/g, '');

    // Если строка не пуста, заменяем первый символ на 7 (если это не 7 уже)
    if (cleaned.length > 0 && cleaned[0] !== '7') {
        cleaned = '7' + cleaned.slice(1);
    }

    // Обрезаем до 11 цифр (максимум для номера с кодом страны)
    if (cleaned.length > 11) {
        cleaned = cleaned.slice(0, 11);
    }

    // Форматируем в зависимости от длины
    const match = cleaned.match(/^(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})$/);
    if (!match) return cleaned;

    let formatted = '';
    if (match[1]) formatted += `+${match[1]}`;
    if (match[2]) formatted += ` (${match[2]}`;
    if (match[3]) formatted += `) ${match[3]}`;
    if (match[4]) formatted += `-${match[4]}`;
    if (match[5]) formatted += `-${match[5]}`;

    return formatted.trim();
};

// Локальная переменная для отображения телефона
const formattedPhone = computed({
    get() {
        return carRequestStore.contacts.phone ? formatPhoneNumber(carRequestStore.contacts.phone) : '';
    },
    set(newValue) {
        // Оставляем только цифры
        carRequestStore.contacts.phone = newValue.replace(/\D/g, '');
        checkNextBtnStatus(); // Проверяем статус кнопки после ввода
    }
});

// Ограничение ввода только цифр
const restrictToNumbers = (event) => {
    const value = event.target.value.replace(/\D/g, ''); // Удаляем все нечисловые символы
    event.target.value = formatPhoneNumber(value); // Отображаем отформатированное значение
    carRequestStore.contacts.phone = value; // Сохраняем только цифры в сторе
};

// Проверка заполненности полей для кнопки "Далее"
const checkNextBtnStatus = () => {
    carRequestStore.isNextStepBtnDisabled = isNextStepDisabled();
};

const isNextStepDisabled = () => {
    const contacts = carRequestStore.contacts;
    if (
        !contacts.name || contacts.name === '' || contacts.name.length < 3
        || !contacts.surname || contacts.surname === '' || contacts.surname.length < 3
        || !contacts.phone || contacts.phone === '' || contacts.phone.length < 11
    ) {
        return true;
    }
    return false;
};

// Синхронизация со стором при загрузке компонента
onMounted(() => {
    carRequestStore.contacts.name = telegramUserStore.user.first_name || '';
    carRequestStore.contacts.surname = telegramUserStore.user.last_name || '';
    checkNextBtnStatus();
});
</script>
