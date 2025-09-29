<template>
    <router-view />
</template>

<script setup lang="ts">
    import { onMounted, ref } from 'vue';
    import { api } from './boot/axios';
    import { LocalStorage } from 'quasar';
    import camelcaseKeys from 'camelcase-keys';

    interface SiteSettings {
        themeColor: string,
        siteColor: string,
        bgCardColor: string,
        bgBtnColor: string,
        contactPhone: string,
        slogan: string,
    };

    const siteSettings = ref<SiteSettings>({
        themeColor: '',
        siteColor: '',
        bgCardColor: '',
        bgBtnColor: '',
        contactPhone: '',
        slogan: '',
    });

    onMounted(async () => {   
        const url = document.URL.split('/');
        const urlName = url[url.length-1] as string;
        
        LocalStorage.set("urlName", urlName);
        
        const res = await api.get(`/site/get-site-settings/${LocalStorage.getItem("urlName")}`);
        const data: SiteSettings = camelcaseKeys(res.data.data, { deep: true });
        
        siteSettings.value = data;
        
        LocalStorage.set("themeColor", data.themeColor)
        LocalStorage.set("siteColor", data.siteColor)
        LocalStorage.set("contactPhone", data.contactPhone)
        LocalStorage.set("slogan", data.slogan);
    
        document.getElementById('q-app').style.backgroundColor = LocalStorage.getItem('themeColor')
    });
</script>