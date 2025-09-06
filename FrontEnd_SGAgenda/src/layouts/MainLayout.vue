<template>
    <q-layout view="hHr LpR lFf">
        <q-header>
            <div 
                class="q-pa-md ml-16 text-xl"
            >     
                <div class="flex" v-if="width as number >= 1100">
                    <q-avatar rounded class="w-40 h-10 cursor-pointer mr-10">
                        <img 
                            :src="surprise ? 'public/images/GK_agenda.png' : 'public/images/logo-branca.png'"
                            @click="surprise = !surprise"
                        >
                    </q-avatar>

                    <div class="ml-4 mt-auto mb-auto hover:bg-blue-500 rounded-md p-1">
                        <router-link to="">Contato</router-link>
                    </div>
                    
                    <div class="ml-4 mt-auto mb-auto hover:bg-blue-500 rounded-md p-1">
                        <router-link to="">Suporte</router-link>
                    </div>

                    <div class="ml-auto mr-14">
                        <q-btn 
                            :style="`background-color: rgb(59 130 200 / var(--tw-bg-opacity, 1))`"
                            class="mr-4"
                            label="Criar conta"
                            
                        />

                        <q-btn 
                            :style="`background-color: #fff`"
                            class="text-blue-600"
                            label="Entrar"
                            
                        />
                    </div>
                </div>

                <div v-else>
                    <div class="flex justify-center">
                        <q-avatar rounded class="w-40 h-10 cursor-pointer">
                            <img 
                                :src="surprise ? 'public/images/GK_agenda.png' : 'public/images/logo-branca.png'"
                                @click="surprise = !surprise"
                            >
                        </q-avatar>
                        <q-btn 
                            flat
                            round
                            icon="menu"
                            class="ml-14"
                            @click="show = !show"
                        />
                    </div>

                    <q-dialog v-model="show" persistent>
                        <div class="bg-white p-4 flex-col text-center">
                            <div class="">
                                <q-btn flat icon="close" color="primary" class="" v-close-popup />
                            </div>

                            <q-btn 
                                :style="`background-color: rgb(59 130 200 / var(--tw-bg-opacity, 1))`"
                                class="mb-2 mt-2 flex justify-center text-white"
                                label="Criar conta"
                                
                            />

                            <q-btn 
                                :style="`background-color: #fff`"
                                class="text-blue-600 mt-2 mb-4"
                                label="Entrar"
                                
                            />
                            <div class="ml-auto mr-auto hover:bg-blue-500 hover:text-white rounded-md p-1">
                                <router-link to="">Contato</router-link>
                                
                            </div>
                            
                            <div class="hover:bg-blue-500 hover:text-white rounded-md p-1">
                                <router-link to="">Suporte</router-link>
                            </div>
                        </div>
                    </q-dialog>
                </div>
            </div>
        </q-header>

        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { ref } from 'vue';

    const width = LocalStorage.getItem("width");
    let surprise = ref<boolean>(true);
    let show = ref<boolean>(false);

</script>