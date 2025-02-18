<script setup>
import {getCurrentInstance, onMounted, ref} from 'vue';
import Header from "../Components/Home/Header.vue";
import HomeTitle from "../Components/Home/HomeTitle.vue";
import Car from '../Components/Home/Car.vue';
import Filter from "../Components/Home/Filter.vue";
import {useCarStore} from "../stores/car.js";
//Рзрешаем доступ к сайту только если юзер зашел через web app tg
const accessDenied = ref(false);
//Телеграм web app
const {$tg} = getCurrentInstance().appContext.config.globalProperties
//Наш юзер
const user = ref(null);
//Индикатор готовности страницы
const isReady = ref(false);
//Динамический компонент с таекстом на главной
const homeTitleData = ref(null);
//Открыты ли фильтры
const isFilterOpen = ref(false);
//Хранилище для загруженных с бека машин
const carStore = useCarStore();

onMounted(async () => {
    // Получаем данные пользователя из TelegramWebApp
    if ($tg && $tg.initDataUnsafe && $tg.initDataUnsafe.user) {
        user.value = $tg.initDataUnsafe.user;
        accessDenied.value = false
    } else {
        //Если ничего не получили, возвращаемся
        accessDenied.value = true
        return;
    }

    // Загружаем данные для HomeTitle
    try {
        const response = await fetch('/api/content/home-title');
        if (response.ok) {
            homeTitleData.value = await response.json();
        }
    } catch (error) {
        console.error('Error fetching HomeTitle data:', error);
    }

    // Загружаем первую страницу при монтировании компонента
    await carStore.loadCars();

    // Устанавливаем флаг готовности после завершения всех операций
    isReady.value = true;
});

</script>

<template>
    <!-- preloader -->
    <div v-if="!isReady" class="fixed flex items-center justify-center w-full h-full bg-white z-10">
        <span class="spinner"></span>
    </div>


    <!-- Если доступ запрещен, показываем только этот блок -->
    <div v-if="accessDenied" class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full justify-center p-4 text-center items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                <div>
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                        <svg fill="#dc2626" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M402.7 425.3l-316-316C52.6 148.6 32 199.9 32 256c0 123.7 100.3 224 224 224c56.1 0 107.4-20.6 146.7-54.7zm22.6-22.6C459.4 363.4 480 312.1 480 256C480 132.3 379.7 32 256 32c-56.1 0-107.4 20.6-146.7 54.7l316 316zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                            Доступ на сайт запрещён
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Запустите приложение в нашем телеграм боте для доступа к
                                сайту.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <a href="https://t.me/t3zusauto_bot"
                       class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Перейти в бота
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Тут уже отрисуем основное приложение -->
    <div v-else v-if="isReady">
        <Header :user="user"/>
        <HomeTitle v-if="homeTitleData" :title="homeTitleData.value"/>
        <!-- Открытие фильтров -->
        <div class="m-4">
            <span @click="isFilterOpen = !isFilterOpen" class="flex text-gray-400 max-w-[480px] mx-auto">
            <svg class="mr-2" fill="#9ca3af" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path
                d="M144 256v87.2l64 44V256 244l7.9-9L320 116V96H32v20l104.1 119 7.9 9v12zm-32 0L0 128V96 64H32 320h32V96v32L240 256V409.2 448l-32-22-96-66V256zM384 80h16 96 16v32H496 400 384V80zM336 240H496h16v32H496 336 320V240h16zm0 160H496h16v32H496 336 320V400h16z"/></svg>
            Показать фильтры
        </span>
        </div>
        <Filter :open="isFilterOpen"/>
        <!-- Отображение автомобилей с использованием компонента Car -->
        <Car v-for="car in carStore.cars" :key="car.id" :data="car"/>
        <!-- Кнопка показать ещё -->
        <div class="flex justify-center mb-4">
            <button v-if="carStore.isShowMoreButtonAvailable"
                    @click="carStore.loadCars()"
                    :disabled="carStore.isLoading"
                    type="button"
                    class="rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
                <span v-if="carStore.isLoading" class="flex">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"
                        ></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Загрузка...
                </span>
                <span v-else>Показать ещё</span>
            </button>
        </div>
    </div>

</template>

<style>
html {
    background-color: #f3f4f6;
}

.spinner {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: radial-gradient(farthest-side, #007aff 94%, #0000) top/9px 9px no-repeat,
    conic-gradient(#0000 30%, #007aff);
    -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 9px), #000 0);
    animation: spinner-c7wet2 1s infinite linear;
}

@keyframes spinner-c7wet2 {
    100% {
        transform: rotate(1turn);
    }
}
</style>
