<template>
    <q-layout view="hHr LpR lFf">
        <q-btn @click="drawerLeft = !drawerLeft" class="bg-transparent" icon="menu" />

        <q-drawer
            v-model="drawerLeft"
            show-if-above
            :width="210"
            class="bg-[#03202e] text-white rounded-r-md"
        >
            <q-toolbar class="mt-8">
                <img
                    class="rounded mb-4"  
                    :src="surprise ? '../../public/images/GK_agenda.png' : '../../public/images/logo-branca.png'"
                    @click="surprise = !surprise"
                >

            </q-toolbar>

            <Links/>

            <div class="fixed ml-16 bottom-0 mb-2 ">
                <span
                    class="p-2 rounded-md cursor-pointer"
                    @click="logout"
                >
                    Sair
                </span>
            </div>
        </q-drawer>

        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>    
</template>

<script setup lang="ts">
    import Links from 'src/components/LinksSideBar/Links.vue';
    import { onMounted, ref } from 'vue';
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { useRouter } from 'vue-router';

    const router = useRouter();
    const width = LocalStorage.getItem("width") as number;

    let surprise = ref<boolean>(true);
    let drawerLeft = ref<boolean>(true);

    const logout = async () => {
        const res = await api.post('/auth/logout');
        if(res.data) 
        {
            LocalStorage.remove("authToken");
            LocalStorage.remove("siteName");            
            LocalStorage.remove("lastCheck"); 

            router.replace({ path: '/' });   
        };
    };

    onMounted(() => {
        width >= 1100 ? drawerLeft.value = true : drawerLeft.value = false;

    });
</script>