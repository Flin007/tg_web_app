<script setup>
import {useCarRequest} from "../../stores/carRequest.js";
import {formatPrice} from "../../utils/formattingUtils.js";
import {ref} from "vue";

const carRequestStore = useCarRequest();
const car = ref(carRequestStore.selectedCar);
</script>

<template>
    <div>
        <img :src="'/storage/' + car.photos[0].path" :alt="`Фото ${car.brand.name} ${car.model.name}`"/>
        <h2 class="text-3xl text-gray-800 font-light">{{ car.title && car.title.length > 0 ? car.title : car.brand.name + ' ' + car.model.name }}</h2>
        <p class="text-gray-400">{{ car.trim }}, {{ car.year }}</p>
        <p v-if="car.description" class="text-gray-400">{{ car.description }}</p>

        <div class="mt-2">
            <p class="text-gray-400"><span class="font-semibold">Номер в базе:</span> #{{ car.id }}</p>
            <p class="text-gray-400"><span class="font-semibold">Мрака:</span> {{ car.brand.name }}</p>
            <p class="text-gray-400"><span class="font-semibold">Модель:</span> {{ car.model.name }}</p>
            <p class="text-gray-400"><span class="font-semibold">Двигатель:</span> {{ car.engine }}</p>
            <p class="text-gray-400"><span class="font-semibold">Коробка:</span> {{ car.transmission }}</p>
            <p class="text-gray-400"><span class="font-semibold">Привод:</span> {{ car.drive }}</p>
            <p class="text-gray-400"><span class="font-semibold">Интерьер:</span> {{ car.interior }}</p>
            <p class="text-gray-400"><span class="font-semibold">VIN:</span> {{ car.vin }}</p>
            <p class="text-gray-400"><span class="font-semibold">Стоимость от:</span> {{ formatPrice(car.price) }} ₽</p>
        </div>
    </div>
</template>
