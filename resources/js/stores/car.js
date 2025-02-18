import { defineStore } from 'pinia';

export const useCarStore = defineStore('car', {
    state: () => ({
        cars: [],
        currentPage: 1,
        totalPages: 1,
        isLoading: false,
    }),
    actions: {
        async loadCars() {
            if (this.isLoading) return; // Если уже идет загрузка, не начинаем новую
            this.isLoading = true; // Устанавливаем флаг загрузки в true
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
            } finally {
                this.isLoading = false; // Сбрасываем флаг загрузки после окончания
            }
        }
    },
    getters: {
        isShowMoreButtonAvailable: (state) => state.currentPage <= state.totalPages
    }
});
