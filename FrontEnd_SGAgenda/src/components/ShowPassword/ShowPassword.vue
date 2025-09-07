<template>
    <div v-if="!show">
        <!-- Disabled -->
        <svg 
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 16 16" 
            fill="currentColor" 
            class="size-5 cursor-pointer"
            @click="showPassword"
        >
            <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 6V4.5a2 2 0 1 0-4 0V7h4Z" clip-rule="evenodd" />
        </svg>
    </div>

    <div v-else>
        <!-- Show -->
        <svg 
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 16 16" 
            fill="currentColor" 
            class="size-5 cursor-pointer"
            @click="showPassword"
        >
            <path d="M11.5 1A3.5 3.5 0 0 0 8 4.5V7H2.5A1.5 1.5 0 0 0 1 8.5v5A1.5 1.5 0 0 0 2.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 9.5 7V4.5a2 2 0 1 1 4 0v1.75a.75.75 0 0 0 1.5 0V4.5A3.5 3.5 0 0 0 11.5 1Z" />
        </svg>

    </div>
</template>
<script setup lang="ts">
    import { ref } from 'vue';

    const emits = defineEmits<{
        (e: 'show', value: boolean): void
        
    }>();

    let show = ref<boolean>(false);

    const showPassword = () => {
        show.value = !show.value;
        emits('show', show.value);
        
    };
</script>