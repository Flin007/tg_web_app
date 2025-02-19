import { defineStore } from 'pinia';

export const useFilterStore = defineStore('filter', {
    state: () => ({
        isOpen: false,
        isLoading: false,
        //Города
        cities: [],
        selectedCity: null,
        //Бренды
        brands: [],
        selectedBrand: null,
        //Модели
        models: [],
        selectedModel: null,
    }),
    actions: {
        //Города
        async fetchCities() {
            const response = await axios.get('/api/cities');
            this.cities = response.data;
        },
        clearSelectedCity() {
            this.selectedCity = null;
        },
        //Бренды
        async fetchBrands() {
            const response = await axios.get('/api/brands');
            this.brands = response.data;
        },
        clearSelectedBrand() {
            this.selectedBrand = null;
        },
        //Модели
        async fetchModels() {
            const response = await axios.get('/api/models');
            this.models = response.data;
        },
        clearSelectedModel() {
            this.selectedCity = null;
        },
        //Сброс всех фильтров
        resetFilter(){
            this.clearSelectedCity();
            this.clearSelectedBrand();
            this.clearSelectedModel();
        }
    }
});
