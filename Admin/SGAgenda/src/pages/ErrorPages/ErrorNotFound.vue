<template>
    <div class="fullscreen bg-blue text-white text-center q-pa-md flex flex-center">
        <div>
            <div style="font-size: 30vh">
                404
            </div>

            <div class="text-h2" style="opacity:.4">
                Oops. Nothing here...
            </div>

            <q-btn
                class="q-mt-xl"
                color="white"
                text-color="blue"
                unelevated
                to="dashboard"
                label="Go Home"
                no-caps
            />
        </div>
    </div>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { onMounted } from 'vue';
    onMounted(() => {
        LocalStorage.remove("lastURL");
        LocalStorage.set("lastCheck", 0);
    });
</script>
