<template>
    <main class="p-12">
        <router-link to="/register">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            
        </router-link>
        <div 
            class="text-2xl " 
            :class="{
                'ml-32 mt-12': width >= 1100
            }"
        >
            <article
                :class="{
                    'grid grid-cols-2': width >= 1100
                }"    
            >
                <div
                    :class="{
                        'flex justify-between mt-8 p-12 w-[100vh]': width >= 1100

                    }"
                    class="max-h-max"
                >
                    <q-form
                        @submit="completeOwner"
                        class="q-gutter-md p-4 ml-28"
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
                                class="max-w-[130%] mb-4 border rounded-md"
                                required
                                @update:model-value="equalName(owner.companyName)"
                            >
                                <template v-slot:label>
                                    <div >Razão social <span class="text-red-500 text-xs relative bottom-1">*</span></div>
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
                                @update:model-value="setUpperCase(owner.tradeName)"
                            >
                                <template v-slot:label>
                                    <div >Nome Fantasia <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="ml-2"></div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="owner.cnpjCpf" 
                                type="text" 
                                stack-label
                                dense
                                label-slot
                                borderless
                                color="grey"
                                class="w-[100%] mb-4 border rounded-md" 
                                required
                                maxlength="17"
                                @update:model-value="formatCnpjCpf(owner.cnpjCpf)"
                                :rules="[cpfInput]"
                                hide-bottom-space
                            >
                                <template v-slot:label>
                                    <div >CPF/CNPJ<span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:before>
                                    <div class="ml-4"></div>
                                </template>
                            </q-input>
                        </div>        
                        
                        <div class="w-max ml-auto mr-auto">
                            <q-btn no-caps label="Cadastrar empresa" type="submit" class="w-full" color="primary"/>
                            
                        </div>
                    </q-form>
                </div>

                <div class="relative right-8 top-20">
                    <img 
                        v-if="width >= 1440"
                        src="public/images/imagem_teste.jpg" 
                    />
                </div>
            </article>
        </div>
    </main>
</template>

<script setup lang="ts">
    import { event, LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import validateCPF from 'src/utils/validateCPF';
    import getCNPJData from 'src/utils/Services/CNPJ/getCNPJData';

    interface AddessData {
        cep: string, // zip
        address: string,
        complement: string,
        neighborhood: string,
        municipality: string,
        uf: string,
        number: string
    };

    interface CNPJData {
        companyName: string,
        tradeName: string,
        cnpj: string,
        address: AddessData
    };  

    interface Owner {
        companyName: string,
        tradeName: string,
        cnpjCpf: string
    };

    const $q = useQuasar();
    const router = useRouter();
    const ownerCode = LocalStorage.getItem("ownerCode");
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
        

        try {
            const res = await api.put(`/auth/update-owner/${ownerCode}`, payload, {
                headers: {
                    Accept: 'application/json'
                }
            });
            
            const data = res.data;

            console.log('Res: ', res.data);
            if(data.success)
            {
                $q.notify({
                    color: 'green',
                    message: 'Dados salvos com sucesso!',
                    position: 'top',
                    timeout: 1200

                });

                router.replace({ path: '/companie-url' });
            };
            
        } catch (error: any) {
            console.error('Erro ao criar proprietário: ', error);
            const e: string = error.response?.data?.message;
            let isDuplicateMail;
            if(e) isDuplicateMail = e.trim().includes("SQLSTATE[23000]")
            
            if (isDuplicateMail) {
                $q.notify({
                    color: 'red',
                    message: 'Esse CNPJ/CPF já está cadastrado!',
                    position: 'top',
                    timeout: 2000

                });

                owner.value.cnpjCpf = '';

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

    const equalName = (val: string) => {
        owner.value.companyName = val.toUpperCase();    
        owner.value.tradeName = owner.value.companyName;
    };

    function formatCnpjCpf(val: string)
    {
        const cnpjCpf = val.replace(/\D/g, '');
        
        if (cnpjCpf.length === 11) 
        {
            console.log('É CPF')
            owner.value.cnpjCpf = cnpjCpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3-\$4");

            return owner.value.cnpjCpf;
        };
        
        if (cnpjCpf.length === 14)
        {
            console.log('É CNPJ');
            owner.value.cnpjCpf = cnpjCpf.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
            getCNPJ(cnpjCpf);

            return owner.value.cnpjCpf;

        };
    }

    const setUpperCase = (val: string) => { owner.value.tradeName = val.toUpperCase(); };

    const cpfInput = () => {
        if(!validateCPF(owner.value.cnpjCpf) && owner.value.cnpjCpf.replace(/\D/g, '').length === 11)
        {
            return 'CPF inválido';
        };

        return true;
    };

    const getCNPJ = async (cnpj: string) => {
        const data = await getCNPJData(cnpj) as CNPJData;
        owner.value.companyName = data.companyName;
        owner.value.tradeName = data.tradeName ? data.tradeName : data.companyName;

    };

    onMounted(() => {
        if(!ownerCode) router.replace({ path: '/register' });

    });
</script>
