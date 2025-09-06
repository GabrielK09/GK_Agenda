<template>
    <q-layout view="hHr LpR lFf">
        <q-header>
            <div 
                class="q-pa-md ml-16 text-xl"
            >     
                <div class="flex" v-if="width as number >= 1100">
                    <q-avatar rounded class="w-40 h-10 cursor-pointer">
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

                    <q-dialog v-model="show" persistent>
                        <div class="bg-white p-4 flex-col text-center">
                            <div class="ml-auto mr-auto hover:bg-blue-500 rounded-md p-1">
                                <router-link to="">Contato</router-link>
                            </div>
                            
                            <div class="hover:bg-blue-500 rounded-md p-1">
                                <router-link to="">Suporte</router-link>
                            </div>

                            <div class="">
                                <q-btn 
                                    :style="`background-color: rgb(59 130 200 / var(--tw-bg-opacity, 1))`"
                                    class="mb-4"
                                    label="Criar conta"
                                    
                                />

                                <q-btn 
                                    :style="`background-color: #fff`"
                                    class="text-blue-600 mb-4 flex"
                                    label="Entrar"
                                    
                                />
                            </div>
                            
                            <q-btn flat label="Cancel" color="primary" v-close-popup />
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
    let show = ref<boolean>(true);

</script>