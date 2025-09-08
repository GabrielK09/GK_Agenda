<template>
    <router-view />
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { onMounted } from 'vue';

    onMounted(() => {
        
        LocalStorage.setItem("width", window.innerWidth);
    });
</script>
