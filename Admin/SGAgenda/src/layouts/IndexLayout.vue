<template>
    <q-layout view="hHr LpR lFf">
        <q-header>
            <div 
                class="top-header q-pa-md ml-16 text-xl"
            >     
                <div class="flex">
                    <q-avatar rounded class="w-40 h-10 cursor-pointer mr-10">
                        <img 
                            :src="surprise ? 'public/images/GK_agenda.png' : 'public/images/logo-branca.png'"
                            @click="surprise = !surprise"
                        >
                    </q-avatar>

                    <div class="ml-auto mr-14 inline-flex">
                        <q-btn 
                            id="menu-btn"
                            class="menu-btn"
                            color="white" 
                            icon="menu"  
                            flat
                            @click="show = !show"
                        />

                        <div class="links inline-flex">
                            <q-btn  
                                no-caps
                                :style="`background-color: rgb(59 130 200 / var(--tw-bg-opacity, 1))`"
                                class="mr-4"
                                label="Criar conta"
                                :to="'/auth/register'"
                            />

                            <q-btn 
                                no-caps
                                :style="`background-color: #fff`"
                                class="text-blue-600"
                                label="Entrar"
                                @click="toLogin"
                            />

                        </div>
                    </div>
                </div>
            </div>
        </q-header>

        <q-dialog v-model="show" persistent>
            <div class="bg-white p-4 flex-col text-center">
                <div class="">
                    <q-btn flat icon="close" color="primary" v-close-popup />
                </div>

                <q-btn 
                    no-caps
                    :style="`background-color: rgb(59 130 200 / var(--tw-bg-opacity, 1))`"
                    class="mb-2 mt-2 flex justify-center text-white"
                    label="Criar conta"
                    :to="'/auth/register'"
                />  

                <q-btn 
                    no-caps
                    :style="`background-color: #fff`"
                    class="text-blue-600 mt-2 mb-4"
                    label="Entrar"
                    @click="toLogin"
                />
                <div class="ml-auto mr-auto hover:bg-blue-500 hover:text-white rounded-md p-1">
                    <router-link to="">Contato</router-link>
                    
                </div>
                
                <div class="hover:bg-blue-500 hover:text-white rounded-md p-1">
                    <router-link to="">Suporte</router-link>
                </div>
            </div>
        </q-dialog>

        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import { LocalStorage } from 'quasar';
    import { useRouter } from 'vue-router';

    const router = useRouter();

    let surprise = ref<boolean>(true);
    let show = ref<boolean>(false);
    
    const toLogin = () => { 
        const token = LocalStorage.getItem("authToken");
        const siteName = LocalStorage.getItem("siteName");
    
        if(token && siteName)
        {
            router.replace({ path: `/admin/${siteName}/dashboard` });

        } else {
            router.replace({ path: '/auth/login' }); 

        };
    };
</script>

<style lang="scss">
    @media (max-width: 1100px) {
        .top-header {
            display: flex;
            justify-content: center;

            .links {
                display: none;
            }

        }
    }

    @media (min-width: 1100px) {
        .top-header {
            .menu-btn {
                display: none;
            }
        }
    }
    
</style>