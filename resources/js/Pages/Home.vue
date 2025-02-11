<template>
    <div>
        <h1>Hello world!</h1>
        <div v-if="$tg.initDataUnsafe">
            <h2>User Data:</h2>
            <pre>{{ JSON.stringify($tg.initDataUnsafe, null, 2) }}</pre>
        </div>
        <div v-else>
            <p>No user data received</p>
        </div>
    </div>
</template>

<script setup>
import {getCurrentInstance} from 'vue'
const {$tg} = getCurrentInstance().appContext.config.globalProperties


</script>
