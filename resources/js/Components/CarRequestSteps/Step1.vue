<template>
    <div>
        <h2 class="text-2xl text-gray-800 font-light">Варианты приобретения</h2>
        <fieldset class="mt-2" aria-label="Варианты приобретения">
            <RadioGroup v-model="localPurchasingOption" class="relative -space-y-px rounded-md bg-white">
                <RadioGroupOption
                    as="template"
                    v-for="(option, optionIdx) in purchasingOptions"
                    :key="option.name"
                    :value="option"
                    v-slot="{ checked, active }"
                >
                    <div :class="[optionIdx === 0 ? 'rounded-tl-md rounded-tr-md' : '', optionIdx === purchasingOptions.length - 1 ? 'rounded-bl-md rounded-br-md' : '', checked ? 'z-10 border-blue-200 bg-blue-50' : 'border-gray-200', 'relative flex cursor-pointer flex-col border p-4 focus:outline-none md:grid md:grid-cols-3 md:pl-4 md:pr-6']">
            <span class="flex items-center text-sm">
              <span :class="[checked ? 'bg-blue-600 border-transparent' : 'bg-white border-gray-300', active ? 'ring-2 ring-offset-2 ring-blue-600' : '', 'h-4 w-4 rounded-full border flex items-center justify-center']" aria-hidden="true">
                <span class="rounded-full bg-white w-1.5 h-1.5" />
              </span>
              <span :class="[checked ? 'text-blue-900' : 'text-gray-900', 'ml-3 font-medium']">{{ option.ruName }}</span>
            </span>
                        <span :class="[checked ? 'text-blue-700' : 'text-gray-500', 'ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right']">{{ option.desc }}</span>
                    </div>
                </RadioGroupOption>
            </RadioGroup>
        </fieldset>
        <h2 class="mt-8 text-2xl text-gray-800 font-light">Программа Trade-In?</h2>
        <Switch
            v-model="carRequestStore.shouldUseTradeIn"
            :class="[carRequestStore.shouldUseTradeIn ? 'bg-blue-600' : 'bg-gray-200', 'mt-2 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']"
        >
            <span class="sr-only">Use setting</span>
            <span :class="[carRequestStore.shouldUseTradeIn ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">
        <span :class="[carRequestStore.shouldUseTradeIn ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
          <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
            <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
        <span :class="[carRequestStore.shouldUseTradeIn ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
          <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 12 12">
            <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
          </svg>
        </span>
      </span>
        </Switch>
        <p class="text-gray-400">Готовы принять ваш автомобиль в Трейд-ин для получения дополнительной скидки от производителя.</p>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { RadioGroup, RadioGroupOption, Switch } from '@headlessui/vue';
import { useCarRequest } from '../../stores/carRequest.js';

const carRequestStore = useCarRequest();

const purchasingOptions = ref([
    { name: 'Credit', ruName: 'Кредит', desc: 'Без первоначального взноса.' },
    { name: 'Installment', ruName: 'Рассрочка', desc: 'Первоначальный взнос от 30%.' },
    { name: 'Cash', ruName: 'Наличные', desc: 'Без спец. программ.' },
]);

// Локальная переменная для v-model
const localPurchasingOption = ref(null);

// Синхронизация со стором при загрузке компонента
onMounted(() => {
    if (carRequestStore.purchasingOption !== null) {
        // Находим объект из purchasingOptions, который соответствует значению в сторе по свойству name
        const matchingOption = purchasingOptions.value.find(
            option => option.name === carRequestStore.purchasingOption.name
        );
        if (matchingOption) {
            localPurchasingOption.value = matchingOption;
        }
    }
    carRequestStore.isNextStepBtnDisabled = localPurchasingOption.value === null;
});

// Отслеживание изменений локального значения и обновление стора
watch(localPurchasingOption, (newOption) => {
    carRequestStore.purchasingOption = newOption;
    carRequestStore.isNextStepBtnDisabled = newOption === null;
});
</script>
