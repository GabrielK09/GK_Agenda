<template>
    <q-layout view="hHr LpR lFf">
        <q-btn @click="drawerLeft = !drawerLeft" flat class="rounded btn-menu" icon="menu" />

        <q-drawer
            v-model="drawerLeft"
            show-if-above
            :width="210"
            class="bg-[#03202e] text-white rounded-r-md dashboard"
        >
            <q-toolbar class="logo">
                <img
                    class="rounded mb-4"  
                    :src="surprise ? '../../public/images/GK_agenda.png' : '../../public/images/logo-branca.png'"
                    @click="surprise = !surprise"
                />

            </q-toolbar>

            <div class="links">
                <Links/>
                
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

    const width = LocalStorage.getItem("width") as number;

    let surprise = ref<boolean>(true);
    let drawerLeft = ref<boolean>(true);

    onMounted(() => {
        width >= 1100 ? drawerLeft.value = true : drawerLeft.value = false;

    });
</script>

<style>

    @media (max-width: 1100px) {
        .dashboard {
            .logo{
                margin: 0;
                width: 0;
            
            }

            .links {
                margin: -2rem 0 0 0;
            }
        }
    }

    @media (min-width: 1100px) {
        .dashboard {
            .logo{
                margin: 2rem 0 0 0;
            
            }

            .links {
                margin: 0;
            }
        }
    }

    body {
        background-color: #f5f5f5;
    }

</style>