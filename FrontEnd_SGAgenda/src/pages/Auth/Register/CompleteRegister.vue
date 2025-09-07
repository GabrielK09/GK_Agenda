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
                        @submit="completeOwner"
                        class="q-gutter-md"
                    >
                        <h2 class="text-center">
                            Só mais alguns passos!
                        </h2>
                        <div class="flex-row">
                            <q-input 
                                v-model="owner.companyName" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Razão social"
                                class="w-[100%] mb-4 border rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div class="border-b">Razão social <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="ml-2"></div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="owner.tradeName" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome Fantasia"
                                class="w-[100%] mb-4 border rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div class="border-b">Nome Fantasia <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="ml-2"></div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="owner.cnpjCpf" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                class="w-[100%] mb-4 border rounded-md"
                                required
                                maxlength="14"
                                @update:model-value="formatcnpjCpf(owner.cnpjCpf)"
                            >
                                <template v-slot:label>
                                    <div class="border-b">CPF/CNPJ<span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="ml-2"></div>
                                </template>
                            </q-input>
                        </div>        
                        
                        <div class="w-40 ml-auto mr-auto">
                            <q-btn label="Finalizar cadastro" type="submit" class="w-full" color="primary"/>
                            
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
    import { ref } from 'vue';

    interface Owner {
        companyName: string,
        tradeName: string,
        cnpjCpf: string
    };

    const ownerID = LocalStorage.getItem("ownerID");
    const width = LocalStorage.getItem("width") as number;
    const owner = ref<Owner>({
        companyName: '',
        tradeName: '',
        cnpjCpf: '',
    });
    
    const completeOwner = async () => {
        const payload: Owner = {
            companyName: owner.value.companyName,
            tradeName: owner.value.tradeName,
            cnpjCpf: owner.value.cnpjCpf 
        };
        console.log('ownerID: ', ownerID);
        

        const res = await api.put(`/auth/update-owner/${ownerID}`, payload, {
            headers: {
                Accept: 'application/json'
            }
        });

        console.log('Res: ', res.data);
    };  

    const formatcnpjCpf = (val: string) => {
        if(val.length === 14)
        {
            owner.value.cnpjCpf = val.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            
        } else {
            owner.value.cnpjCpf = val.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        };
    };
</script>