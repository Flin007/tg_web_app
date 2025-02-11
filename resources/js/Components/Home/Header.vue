<template>
    <header class="p-4 flex items-center space-x-4 border-b border-gray-300">
        <img
            v-if="user && user.photo_url"
            :src="user.photo_url"
            alt="User avatar"
            class="w-10 h-10 rounded-full"
        />
        <span v-else class="w-10 h-10 rounded-full inline-flex items-center justify-center bg-gray-300">
      <!-- Замените на иконку по умолчанию или текст -->
      <span class="text-gray-700 text-xl">?</span>
    </span>

        <div>
            <p v-if="user" class="text-sm font-bold">
                {{ user.first_name ? `${user.first_name} ${user.last_name || ''}` : user.username }}
            </p>
            <p v-else class="text-sm font-bold">Гость</p>
        </div>
    </header>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        default: null
    }
});

const displayUserName = computed(() => {
    if (props.user) {
        return props.user.first_name
            ? `${props.user.first_name} ${props.user.last_name || ''}`
            : props.user.username;
    }
    return 'Гость';
});
</script>
