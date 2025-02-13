<template>
    <header class="p-4 flex justify-between items-center border-b border-gray-300 bg-white">
        <!-- Профиль юзера -->
        <div class="flex items-center">
            <!-- Аватар, если нет ? -->
            <img
                v-if="user && user.photo_url"
                :src="user.photo_url"
                alt="User avatar"
                class="w-10 h-10 rounded-full"
            />
            <span v-else class="w-10 h-10 rounded-full inline-flex items-center justify-center bg-gray-300">
                <span class="text-gray-700 text-xl">?</span>
            </span>

            <!-- ФИ или username -->
            <div class="ml-2.5">
                <p v-if="user" class="text-sm font-bold">
                    {{ user.first_name ? `${user.first_name} ${user.last_name || ''}` : user.username }}
                </p>
                <p v-else class="text-sm font-bold">Гость</p>
            </div>
        </div>
        <!-- ссылка на разраба -->
        <a target="_blank" href="https://t.me/notcollector" class="font-light text-sm text-blue-400">.dev</a>
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
