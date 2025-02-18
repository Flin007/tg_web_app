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
    }),
    actions: {
        async fetchCities() {
            const response = await axios.get('/api/cities');
            this.cities = response.data;
        },
        clearSelectedCity() {
            this.selectedCity = null;
        },
        async fetchBrands() {
            const response = await axios.get('/api/brands');
            this.brands = response.data;
        },
        clearSelectedBrand() {
            this.selectedBrand = null;
        },
        resetFilter(){
            this.clearSelectedCity();
            this.clearSelectedBrand();
        }
    }
});
