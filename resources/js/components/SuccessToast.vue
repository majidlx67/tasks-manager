<script setup lang="ts">

const [message] = defineProps();
</script>

<template>
    <div class="fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded shadow">
        {{ message }}
    </div>
</template>

<style scoped>

</style>
