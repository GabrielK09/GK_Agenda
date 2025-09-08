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
                                @update:model-value="equalName(owner.companyName)"
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
                                @update:model-value="setUpperCase(owner.tradeName)"
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
                                dense
                                label-slot
                                borderless
                                color="grey"
                                class="w-[100%] mb-4 border rounded-md" 
                                required
                                maxlength="17"
                                @update:model-value="formatCnpjCpf(owner.cnpjCpf)"
                                :rules="[cpfInput]"
                            >
                                <template v-slot:label>
                                    <div class="border-b">CPF/CNPJ<span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:before>
                                    <div class="ml-4"></div>
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
    import { event, LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { ref } from 'vue';
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

</script>
