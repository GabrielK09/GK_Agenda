<template>
    <router-view />
</template>

<style src="@quasar/quasar-ui-qcalendar/QCalendarDay.min.css"></style>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { onMounted } from 'vue';

    onMounted(() => {
        LocalStorage.setItem("width", window.innerWidth);
    });
</script>
