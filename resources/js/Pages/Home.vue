<script setup>
import {getCurrentInstance, onMounted, ref} from 'vue';
import Header from "../Components/Home/Header.vue";
import HomeTitle from "../Components/Home/HomeTitle.vue";
import Car from '../Components/Home/Car.vue';
import Filter from "../Components/Home/Filter.vue";
import NothingToShow from "../Components/Home/NothingToShow.vue";
import AccessDenied from "../Components/Home/AccessDenied.vue";
import CarRequest from "../Components/Home/CarRequest.vue";
import {useCarStore} from "../stores/car.js";
import {useFilterStore} from "../stores/filter.js";
import {useTelegramUserStore} from "../stores/telegramUser.js";
import {useCarRequest} from "../stores/carRequest.js";
//Телеграм web app
const {$tg} = getCurrentInstance().appContext.config.globalProperties
//Индикатор готовности страницы
const isReady = ref(false);
//Динамический компонент с таекстом на главной
const homeTitleData = ref(null);
//Хранилище для загруженных с бека машин
const carStore = useCarStore();
//Хранилище для фильтров
const filterStore = useFilterStore();
//Хранилище для данных телеграм юзера
const telegramUserStore = useTelegramUserStore();
//Хранилище для заявок
const carRequestStore = useCarRequest();

onMounted(async () => {
    // Проверим есть ли данные из ТГ
    if (!window.Telegram.WebApp.initData) {
        isReady.value = true;
        return;
    }

    //Проверим валидные ли данные нам пришли
    await telegramUserStore.verifyInitialData(window.Telegram.WebApp.initData);
    if (!telegramUserStore.isValidData) {
        isReady.value = true;
        return;
    }

    //Ищем юзера, если его нет, создаем из переданных данных и возвращаем данные юзера, которые точно есть в бд
    const userResult = await axios.post('/api/telegram/user/checkUserData', window.Telegram.WebApp.initDataUnsafe);
    if (userResult.status !== 200) {
        isReady.value = true;
        return;
    }
    //Сетим юзера
    telegramUserStore.setUser(userResult.data);

    //Проверяем дал ли юзера разрешение писать боту
    if (telegramUserStore.user.status !== 'member'){
        if (!await requestWritePermission()) {
            isReady.value = true;
            return;
        } else {
            telegramUserStore.user.status = 'member';
        }
    }

    //Проверяем забанен ли юзер
    if (telegramUserStore.user.is_blocked){
        isReady.value = true;
        return;
    }

    // Загружаем данные для HomeTitle
    axios.get('/api/content/home-title').then(response => {
        homeTitleData.value = response.data;
    }).catch(error => {
        console.error(error)
    });

    // Получаем параметры из URL
    const startParam = $tg.initDataUnsafe.start_param;
    //Проверяем передан ли id машины
    if (startParam && startParam.startsWith('car_')) {
        const carId = startParam.replace('car_', '');
        carStore.setFilter('car', carId);
    }

    //Загружаем необходимые данные
    await loadInitialData();

    // Устанавливаем флаг готовности после завершения всех операций
    isReady.value = true;
});

// Преобразуем requestWriteAccess в промис, запрашиваем разрешение писать для бота
const requestWritePermission = () => {
    return new Promise((resolve) => {
        // Для локального тестирования эмулируем результат
        //resolve(false);
        window.Telegram.WebApp.requestWriteAccess((allowed) => {
            resolve(allowed);
        });
    });
};

//Загружем необходимые данные
const loadInitialData = async () => {
    // Запускаем все запросы параллельно
    await Promise.all([
        // Загружаем первую страницу при монтировании компонента
        carStore.loadCars(),
        // Загрузка Городов
        filterStore.fetchCities(),
        // Загрузка Марок авто
        filterStore.fetchBrands(),
        // Загрузка Моделей авто
        filterStore.fetchModels()
    ]);
};
</script>

<template>
    <!-- preloader -->
    <div v-if="!isReady || filterStore.isLoading || carRequestStore.isLoading" class="fixed flex items-center justify-center w-full h-full bg-white z-20">
        <span class="spinner"></span>
    </div>


    <!-- Если доступ запрещен, показываем только этот блок -->
    <AccessDenied v-if="!telegramUserStore.isValidData || telegramUserStore.user.status !== 'member' || telegramUserStore.user.is_blocked"/>

    <!-- Тут уже отрисуем основное приложение -->
    <div v-else v-if="isReady">
        <Header/>
        <HomeTitle v-if="homeTitleData" :title="homeTitleData.value"/>
        <!-- Открытие фильтров -->
        <div class="m-4">
            <span @click="filterStore.isOpen = !filterStore.isOpen" class="flex text-gray-400 max-w-[480px] mx-auto">
            <svg class="mr-2" fill="#9ca3af" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path
                d="M144 256v87.2l64 44V256 244l7.9-9L320 116V96H32v20l104.1 119 7.9 9v12zm-32 0L0 128V96 64H32 320h32V96v32L240 256V409.2 448l-32-22-96-66V256zM384 80h16 96 16v32H496 400 384V80zM336 240H496h16v32H496 336 320V240h16zm0 160H496h16v32H496 336 320V400h16z"/></svg>
            Показать фильтры
        </span>
        </div>
        <Filter/>
        <!-- Отображение автомобилей с использованием компонента Car -->
        <Car v-if="carStore.cars.length > 0" v-for="car in carStore.cars" :key="car.id" :car="car"/>
        <!-- Иначе покажем блок что машины не найдены -->
        <NothingToShow v-else/>
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
        <!-- Модалка с формой заявки -->
        <CarRequest/>
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
