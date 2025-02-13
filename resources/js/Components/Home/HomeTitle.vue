<template>
    <div class="bg-gray-100 pt-5 py-5">
        <h1 v-if="title" class="text-5xl text-center font-extralight">{{ title }}</h1>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>
