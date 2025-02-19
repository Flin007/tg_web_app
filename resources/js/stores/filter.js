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
        allModels: [],
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
            //Если сбросили выбранный бренд, значит снова доступны все модели
            this.models = this.allModels;
        },
        //Модели
        async fetchModels() {
            const response = await axios.get('/api/models');
            this.models = response.data;
            this.allModels = response.data;
        },
        clearSelectedModel() {
            this.selectedModel = null;
        },
        updateModelsByBrandId(brandId) {
            if (brandId !== null) {
                this.models = this.allModels.filter(model => model.brand_id === brandId);
            } else {
                // Если бренд не выбран, очищаем список моделей
                this.models = this.allModels;
            }
        },
        //Сброс всех фильтров
        resetFilter(){
            this.clearSelectedCity();
            this.clearSelectedBrand();
            this.clearSelectedModel();
        }
    }
});
