<template>
    <q-layout view="hHr LpR lFf">
        <q-btn @click="drawerLeft = !drawerLeft" flat class="rounded btn-menu" icon="menu" />

        <q-drawer
            v-model="drawerLeft"
            show-if-above
            :width="210"
            class="bg-[#03202e] text-white rounded-r-md"
        >
            <q-toolbar class="mt-8" v-if="width > 1100">
                <img
                    class="rounded mb-4"  
                    :src="surprise ? '../../public/images/GK_agenda.png' : '../../public/images/logo-branca.png'"
                    @click="surprise = !surprise"
                >

            </q-toolbar>

            <Links/>

            <div class="fixed mt-5 bottom-0 mb-2 bg-[#03202e] w-full ml-auto text-center">
                <span
                    class="p-2 rounded-md cursor-pointer "
                    @click="confirm"
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
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { useRouter } from 'vue-router';

    const $q = useQuasar();
    const router = useRouter();
    const width = LocalStorage.getItem("width") as number;

    let surprise = ref<boolean>(true);
    let drawerLeft = ref<boolean>(true);

    const confirm = () => {
        $q.dialog({
            title: 'Confirme',
            message: 'Deseja realmente sair?',
            
            cancel: {
                push: true,
                label: 'Não',
                color: 'red',
            },

            ok: {
                push: true,
                label: 'Sim',
                color: 'green',
            },

        }).onOk(() => {
            logout();

        }).onCancel(() => {
            return;

        });;
    }

    const logout = async () => {
        $q.notify({
            color: 'green',
            message: 'Saindo ...',
            position: 'top',
            timeout: 2000

        });

        const res = await api.post('/auth/logout');
        if(res.data) 
        {
            LocalStorage.remove("authToken");
            LocalStorage.remove("siteName");            
            LocalStorage.remove("lastCheck"); 
            LocalStorage.remove("lastURL"); 

            router.replace({ path: '/' });   
        };
    };

    onMounted(() => {
        width >= 1100 ? drawerLeft.value = true : drawerLeft.value = false;

    });
</script>

<style>
    body {
        background-color: #f5f5f5;
    }
</style>