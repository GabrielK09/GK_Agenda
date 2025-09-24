<template>
    <main class="p-12">
        <router-link to="/">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            
        </router-link>
        <div 
            class="text-2xl " 
            :class="{
                'ml-32 mt-20': width >= 1100
            }"
        >
            <article
                :class="{
                    'grid grid-cols-2': width >= 1100
                }"    
            >
                <div
                    :class="{
                        'flex justify-between p-12 border w-max': width >= 1100
                    }"
                    class="max-h-max"
                >
                    <q-form
                        @submit="makeSiteURL"
                        class="q-gutter-md"
                    >
                        <h2 class="text-center ">
                            Vamos registrar seu site agora!
                        </h2>
                        <div class="flex-row">
                            <q-input 
                                v-model="informedURL" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Razão social"
                                class="w-[100%] border rounded-md"
                                :rules="[
                                    val => !!val || 'Esse campo é necessário!'
                                ]"
                                hide-bottom-space
                                required
                            >
                                <template v-slot:before>
                                    <div class="ml-4"></div>
                                </template>
                            </q-input>
                            <span class="text-xs ml-2">Seu endereço: {{ prefix }}</span>
                        </div>        
                        
                        <div class="w-40 ml-auto mr-auto">
                            <q-btn label="Salva endereço" type="submit" class="w-full" color="primary"/>
                            
                        </div>
                    </q-form>
                </div>

                <div class="relative right-12 top-8">
                    <img 
                        v-if="width > 1300 && width >= 1440"
                        src="public/images/imagem_teste.jpg" 
                    />
                </div>
            </article>
        </div>
    </main>
</template>

<script setup lang="ts">
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { ref, watch } from 'vue';
    import { useRouter } from 'vue-router';

    // O prefixo do site: https://app.gkagenda.com.br/ nome a ser inserido
    // No caso iria ficar http://localhost/ip:9090/app.gkagenda/ nome a ser inserido

    interface SaveURL {
        ownerCode: number,
        urlName: string,
        url: string
    };

    const $q = useQuasar();
    const router = useRouter();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    const width = LocalStorage.getItem("width") as number;

    let informedURL = ref<string>('');
    let prefix = `http://localhost:9090/app.gkagenda/`;

    watch(informedURL, (newValue) => {
        prefix = `http://localhost:9090/app.gkagenda/` + newValue;
    });

    const makeSiteURL = async () => {
        $q.notify({
            color: 'green',
            message: 'Verificando URL ...',
            position: 'top',
            timeout: 1200
        });
        console.log('Endereço a ser salvo: ', prefix);
        console.log('Endereço urlName: ', informedURL.value);

        const payload: SaveURL = {
            ownerCode: ownerCode,
            urlName: informedURL.value,
            url: prefix
        };

        try {
            const res = await api.post('/site/create-url', payload, {
                headers: {
                    Accept: 'application/json'
                }
            });

            const data = res.data;

            if(data.success)
            {
                $q.notify({
                    color: 'green',
                    message: data.message,
                    position: 'top',
                    timeout: 1200
                });

                router.replace({ path: '/auth/login' });
                
            };
            
        } catch (error: any) {
            console.error('Erro ao criar proprietário: ', error);
            const e: string = error.response?.data?.message;
            let isDuplicateMail;
            if(e) isDuplicateMail = e.trim().includes("SQLSTATE[23000]")
            
            if (isDuplicateMail) {
                $q.notify({
                    color: 'red',
                    message: 'Esse endereço já está cadastrado!',
                    position: 'top',
                    timeout: 2000

                });

                informedURL.value = '';

            } else {
                $q.notify({
                    color: 'red',
                    message: e,
                    position: 'top',
                    timeout: 2000

                });
            };
        };      
    };
</script>