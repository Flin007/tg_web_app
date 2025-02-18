import { defineStore } from 'pinia';

export const useFilterStore = defineStore('filter', {
    state: () => ({
        isLoading: false,
        cities: [],
        selectedCity: null,
    }),
    actions: {
        async fetchCities() {
            const response = await axios.get('/api/cities');
            this.cities = response.data;
        },
        clearSelectedCity() {
            this.selectedCity = null;
        },
        resetFilter(){
            this.clearSelectedCity();
        }
    }
});
