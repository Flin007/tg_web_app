import { defineStore } from 'pinia';

export const useCarStore = defineStore('car', {
    state: () => ({
        cars: [],
        currentPage: 1,
        totalPages: 1, // Установите это значение при загрузке первой страницы
    }),
    actions: {
        async loadCars() {
            try {
                const response = await axios.get(`/api/cars?page=${this.currentPage}`);
                const { data, last_page } = response.data;
                if (this.currentPage === 1) {
                    this.cars = data;
                } else {
                    this.cars.push(...data);
                }
                this.totalPages = last_page;
                this.currentPage++;
            } catch (error) {
                console.error('Failed to load cars:', error);
            }
        }
    },
    getters: {
        isShowMoreButtonAvailable: (state) => state.currentPage <= state.totalPages
    }
});
