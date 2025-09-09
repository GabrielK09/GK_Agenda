<template>
    <main class="p-12">
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
    // No caso iria ficar http://localhost/ip:9000/app.gkagenda/ nome a ser inserido

    interface SaveURL {
        ownerCode: number,
        urlName: string,
        url: string
    };

    const $q = useQuasar();
    const router = useRouter();
    const ownerID = LocalStorage.getItem("ownerID") as number;
    const width = LocalStorage.getItem("width") as number;

    let informedURL = ref<string>('');
    let prefix = `http://localhost:9000/app.gkagenda/`;

    watch(informedURL, (newValue) => {
        prefix = `http://localhost:9000/app.gkagenda/` + newValue;
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
            ownerCode: ownerID,
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

            console.log(data);

            if(data.success)
            {
                $q.notify({
                    color: 'green',
                    message: data.message,
                    position: 'top',
                    timeout: 1200
                });

                router.replace({ path: '/login' });
                
            };
            
        } catch (error: any) {
            $q.notify({
                color: 'green',
                message: error.reponse?.data.message || error.reponse?.message,
                position: 'top',
                timeout: 1200
            });
            console.error('Erro: ', error);
        };      
    };
</script>