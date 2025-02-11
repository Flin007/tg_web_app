<template>
    <div class="bg-gray-100 pt-5 py-5">
        <h1 v-if="title" class="text-5xl text-center font-extralight mb-4">{{ title }}</h1>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';

const title = ref(null);

onMounted(async () => {
    try {
        const response = await fetch('/api/content/home-title');
        if (response.ok) {
            const data = await response.json();
            title.value = data.value;
        }
    } catch (error) {
        console.error('Error fetching content settings:', error);
    }
});
</script>
