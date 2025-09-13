<template>
    <q-page padding>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Meu Site</h2>

                </div>

                <div class="ml-2 p-4 bg-white rounded-md">
                    <span class="">
                        <a target="_blank" :href="siteURL">{{siteURL}}</a>
                    </span>
                </div>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';
    import camelcaseKeys from 'camelcase-keys';

    const ownerCode = LocalStorage.getItem("ownerCode");
    let siteURL = ref<string>('');

    const getURL = async () => {
        const res = await api.get(`/site/get-url/${ownerCode}`);
        const data = camelcaseKeys(res.data, { deep: true });

        siteURL.value = data.data.siteUrl;
    };  

    onMounted(() => {
        getURL();
    });
</script>   