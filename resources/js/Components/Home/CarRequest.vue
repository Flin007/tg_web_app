<template>
    <TransitionRoot as="template" :show="carRequestStore.isOpen">
        <Dialog class="relative z-10" @close="carRequestStore.isOpen = false">
            <div class="fixed inset-0" />

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <form @submit.prevent class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
                                    <div class="bg-blue-700 px-4 py-4 sm:px-4">
                                        <div class="flex items-center justify-between">
                                            <DialogTitle class="text-base font-semibold leading-6 text-white">{{ steps[carRequestStore.currentStep].title }}</DialogTitle>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button type="button" class="relative rounded-md bg-blue-700 text-blue-200 hover:text-white" @click="carRequestStore.isOpen = false">
                                                    <span class="absolute -inset-2.5 outline-none" />
                                                    <span class="sr-only">Close panel</span>
                                                    <svg fill="#fff" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M192 233.4L59.5 100.9 36.9 123.5 169.4 256 36.9 388.5l22.6 22.6L192 278.6 324.5 411.1l22.6-22.6L214.6 256 347.1 123.5l-22.6-22.6L192 233.4z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm text-blue-300">{{ steps[carRequestStore.currentStep].description }}</p>
                                        </div>
                                    </div>
                                    <div class="h-0 flex-1 overflow-y-auto">
                                        <div class="flex flex-1 flex-col justify-between m-4">
                                            <Step0 v-if="carRequestStore.currentStep === 0"/>
                                            <Step1 v-if="carRequestStore.currentStep === 1"/>
                                            <Step2 v-if="carRequestStore.currentStep === 2"/>
                                            <Step3 v-if="carRequestStore.currentStep === 3"/>
                                        </div>
                                    </div>
                                    <div class="flex flex-shrink-0 justify-between px-4 py-4">
                                        <!-- Кнопка назад -->
                                        <button @click="prevStep"
                                                :disabled="carRequestStore.currentStep === 0"
                                                type="button"
                                                class="disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                        >
                                            Назад
                                        </button>
                                        <!-- Навигация на какой странице находимся -->
                                        <nav class="flex items-center justify-center" aria-label="Progress">
                                            <ol role="list" class="flex items-center space-x-5">
                                                <li v-for="step in steps" :key="step.name">
                                                    <span v-if="step.id < carRequestStore.currentStep" class="block h-2.5 w-2.5 rounded-full bg-blue-600 hover:bg-blue-900">
                                                    </span>
                                                    <span v-else-if="step.id === carRequestStore.currentStep" class="relative flex items-center justify-center" aria-current="step">
                                                        <span class="absolute flex h-5 w-5 p-px" aria-hidden="true">
                                                            <span class="h-full w-full rounded-full bg-blue-200" />
                                                        </span>
                                                        <span class="relative block h-2.5 w-2.5 rounded-full bg-blue-600" aria-hidden="true" />
                                                    </span>
                                                    <span v-else class="block h-2.5 w-2.5 rounded-full bg-gray-200 hover:bg-gray-400">
                                                    </span>
                                                </li>
                                            </ol>
                                        </nav>
                                        <!-- Кнопка далее или отправить -->
                                        <button
                                            @click="nextStep"
                                            :disabled="carRequestStore.isNextStepBtnDisabled"
                                            :type="steps.length === carRequestStore.currentStep + 1 ? 'submit' : 'button'"
                                            class="disabled:cursor-not-allowed disabled:opacity-30 inline-flex justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                        >
                                            {{ steps.length === carRequestStore.currentStep+1 ? 'Отправить' : 'Далее'}}
                                        </button>
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
<script setup lang="ts">
import {computed} from 'vue';
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {useCarRequest} from "../../stores/carRequest";
import {toast} from "vue3-toastify";
import Step0 from "../CarRequestSteps/Step0.vue";
import Step1 from "../CarRequestSteps/Step1.vue";
import Step2 from "../CarRequestSteps/Step2.vue";
import Step3 from "../CarRequestSteps/Step3.vue";

const carRequestStore = useCarRequest();

const nextStep = () => {
    //Отправка формы
    if (steps.value.length === carRequestStore.currentStep+1){
        sendResults();
        carRequestStore.isOpen = false;
        return;
    }
    //переключение страницы
    carRequestStore.updateData();
    carRequestStore.prevStep = carRequestStore.currentStep;
    carRequestStore.currentStep++;
}
const prevStep = () => {
    carRequestStore.isNextStepBtnDisabled = false;
    if(0 === carRequestStore.currentStep) {
        return;
    }
    carRequestStore.prevStep = carRequestStore.currentStep;
    carRequestStore.currentStep--;
}

const steps = computed(() => [
    {
        id: 0,
        title: 'Номер вашего обращения: #' + (carRequestStore.requestId || ''),
        description: 'Ознакомьтесь с дополнительной информацией по выбранному автомобилю и жмите Далее',
    },
    {
        id: 1,
        title: 'Уже взяли в работу ваше обращение',
        description: 'Пока что выберете на каких условиях планируется приобретение автомобиля.',
    },
    {
        id: 2,
        title: 'Выбиваем лучшие условия',
        description: 'Нужно ещё немного информации для подсчета всех скидок.',
    },
    {
        id: 3,
        title: 'Согласуем лучшее предложение!',
        description: 'Оставьте свои контактные данные для обратной связи.',
    },
]);

//Отправим финальное обновление
const sendResults = () => {
    axios.post('/request/update', {
        request_id: carRequestStore.requestId,
        data: JSON.stringify(carRequestStore.data),
        finished: true
    })
        .then(() => toast.success('Мы получили ваше обращение'))
        .catch(error => {
            console.error(error)
        });
}
</script>
