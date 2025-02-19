<template>
    <TransitionRoot as="template" :show="filterStore.isOpen">
        <Dialog class="relative z-10" @close="filterStore.isOpen = false">
            <div class="fixed inset-0" />

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <form @submit.prevent="filterCars" class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
                                    <div class="h-0 flex-1 overflow-y-auto">
                                        <div class="bg-blue-700 px-4 py-6 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <DialogTitle class="text-base font-semibold leading-6 text-white">Фильтры</DialogTitle>
                                                <div class="ml-3 flex h-7 items-center">
                                                    <button type="button" class="relative rounded-md bg-blue-700 text-blue-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white" @click="filterStore.isOpen = false">
                                                        <span class="absolute -inset-2.5 outline-none" />
                                                        <span class="sr-only">Close panel</span>
                                                        <svg fill="#fff" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M192 233.4L59.5 100.9 36.9 123.5 169.4 256 36.9 388.5l22.6 22.6L192 278.6 324.5 411.1l22.6-22.6L214.6 256 347.1 123.5l-22.6-22.6L192 233.4z"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm text-blue-300">Выберете интересующие вас параметры и нажмите применить.</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between m-4">
                                            <!-- Фильтр по городам -->
                                            <div>
                                                <div class="flex justify-between">
                                                    <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Город</label>
                                                    <span v-show="filterStore.selectedCity" @click="unsetFilter('city')" class="text-sm text-blue-600">Очистить</span>
                                                </div>
                                                <select v-model="filterStore.selectedCity" @change="updateFilter('city', filterStore.selectedCity)" id="city" name="city" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                                    <option v-for="city in filterStore.cities" :key="city.id" :value="city.id">
                                                        {{ city.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- Фильтр по Маркам -->
                                            <div class="mt-3">
                                                <div class="flex justify-between">
                                                    <label for="brand" class="block text-sm font-medium leading-6 text-gray-900">Марка</label>
                                                    <span v-show="filterStore.selectedBrand" @click="unsetFilter('brand')" class="text-sm text-blue-600">Очистить</span>
                                                </div>
                                                <select v-model="filterStore.selectedBrand" @change="onChangeBrand" id="brand" name="brand" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                                    <option v-for="brand in filterStore.brands" :key="brand.id" :value="brand.id">
                                                        {{ brand.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- Фильтр по Моделям -->
                                            <div class="mt-3">
                                                <div class="flex justify-between">
                                                    <label for="model" class="block text-sm font-medium leading-6 text-gray-900">Модель</label>
                                                    <span v-show="filterStore.selectedModel" @click="unsetFilter('model')" class="text-sm text-blue-600">Очистить</span>
                                                </div>
                                                <select v-model="filterStore.selectedModel" @change="updateFilter('model', filterStore.selectedModel)" id="model" name="model" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                                    <option v-for="model in filterStore.models" :key="model.id" :value="model.id">
                                                        {{ model.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-shrink-0 justify-between px-4 py-4">
                                        <button type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" @click="resetFilter">Сбросить фильтры</button>
                                        <div>
                                            <button type="submit" class="ml-4 inline-flex justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Применить</button>
                                        </div>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import {useFilterStore} from "../../stores/filter.js";
import {useCarStore} from "../../stores/car.js";

const filterStore = useFilterStore();
const carStore = useCarStore();

//Записываем примененный фильтр в хранилище для машин
const updateFilter = (key, value) => {
    carStore.setFilter(key, value);
}

//Сбрасываем фильтр по ключу
const unsetFilter = (key) => {
    //Сбрасываем в carStore
    carStore.unsetFilter(key);
    //Сбрасываем в filterStore
    switch (key) {
        case "city":
            filterStore.clearSelectedCity()
            break;
        case "brand":
            filterStore.clearSelectedBrand()
            break;
        case "model":
            filterStore.clearSelectedModel()
            break;
    }
}

//Сбрасываем все фильтры
const resetFilter = async () => {
    carStore.resetFilter();
    filterStore.resetFilter();
    await filterCars();
}

//Применяем фильтры
const filterCars = async () => {
    filterStore.isLoading = true;
    //Сбрасываем пагинациб для нового запроса с фильтрами
    carStore.currentPage = 1;
    //Дожидаемся загрузки
    await carStore.loadCars();
    //Закрываем модалку
    filterStore.isLoading = false;
    filterStore.isOpen = false;
};

//Функция для обработки смены бренда, т.к. от смены бренда зависят модели, которые можно выбрать
const onChangeBrand = () => {
    updateFilter('brand', filterStore.selectedBrand);
    //Сбросим модель при смене бренда
    unsetFilter('model');
    //Заполним массив моделей в зависимости от выбранного бренда
    filterStore.updateModelsByBrandId(filterStore.selectedBrand)
}
</script>
