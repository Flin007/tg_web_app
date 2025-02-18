import { defineStore } from 'pinia';

export const useCarStore = defineStore('car', {
    state: () => ({
        cars: [],
        currentPage: 1,
        totalPages: 1,
        isLoading: false,
        filters: {},
    }),
    actions: {
        async loadCars() {
            if (this.isLoading) return; // Если уже идет загрузка, не начинаем новую
            this.isLoading = true; // Устанавливаем флаг загрузки в true
            try {
                // Формируем строку запроса для фильтров
                const queryString = new URLSearchParams(this.filters).toString();
                console.log('queryString')
                console.log(queryString)
                const response = await axios.get(`/api/cars?page=${this.currentPage}&${queryString}`);
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
        },
        setFilter(key, value) {
            if (value === null || value === '') {
                delete this.filters[key]; // Удаляем фильтр, если значение пустое или null
            } else {
                this.filters[key] = value;
            }
        },
        unsetFilter(key) {
            delete this.filters[key];
        },
        resetFilter() {
            this.filters = {};
        }
    },
    getters: {
        isShowMoreButtonAvailable: (state) => state.currentPage <= state.totalPages
    }
});
