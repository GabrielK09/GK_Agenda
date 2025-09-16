<template>
    <div class="text-5xl flex justify-center">
        <div class="mt-12">
            <h3 class="text-center mb-4">Bem vindo(a)!</h3>
            <section class="text-3xl">
                <span>Parece que você caiu aqui pro acidente kk</span>
                <span>Você pode acompanhar esses sites de prestadores de serviço</span>
                <pre>{{ myLocale }}</pre>
            </section>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    
    let myLocale = ref<unknown>(navigator.geolocation);

    onMounted(() => {
        
    });
</script>