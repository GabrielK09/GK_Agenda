<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="bg-white p-8 text-xs rounded-md">
                    <div class="flex justify-between">
                        <div 
                            @click="emits('close', true)"
                            class="flex mb-auto mt-auto cursor-pointer"
                        
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>      
                            <span>
                                Voltar para listagem
                            </span>
                        </div>
                    </div>
                    
                    <q-form
                        @submit="createCommission"
                        class="q-gutter-md mt-4 rounded-xl commission-form"
                    >   
                        <div class="links">
                            <div>
                                <div>
                                    <q-checkbox left-label v-model="commissionByCategory" label="Comissão por Categoria" />
                                    <q-avatar size="35px" icon="category" />
                                    
                                    <q-checkbox left-label v-model="commissionByService" label="Comissão por Serviço" />
                                    <q-avatar size="35px" icon="home_repair_service" />

                                </div>
                                
                                <q-checkbox left-label v-model="valueCommissionByPercent" label="Comissão por %" :disable="!releaseValueTypeCommission" />
                                <q-avatar size="35px" icon="percent" />

                                <q-checkbox left-label v-model="valueCommissionByMoney" label="Comissão por R$" :disable="!releaseValueTypeCommission"/>
                                <q-avatar size="35px" icon="money" />
                                
                            </div>
                        </div>

                        <div class="grid grid-cols-2">
                            <div class="w-[90%]">
                                <div v-if="commissionByCategory">
                                    <q-select 
                                        v-model="commission.categoryCode" 
                                        :options="categories" 
                                        label=""
                                        stack-label
                                        outlined
                                        @filter="fillterServices"
                                        use-input
                                        input-debounce="0"
                                        :option-label="option => `${option.categoryCode} - ${option.name}`"
                                        option-value="categoryCode"
                                        :rules="[
                                            val => !!val || 'Um valor precisa ser definido!'  
                                        ]"
                                    >
                                        <template v-slot:label>
                                            <div class="mt-2">
                                                <span>Categoria</span>
                                                <q-avatar size="35px" icon="category" />
                                                
                                            </div>
                                        </template>
                                    </q-select>
                                    <span class="ml-1">'*Valor de comissão definido por categoria será atribuido a <span class="text-red-400">todos</span> os serviços dentro desta categoria*'</span>
                                </div>

                                <div v-if="commissionByService">
                                    <q-select 
                                        v-model="commission.serviceCode" 
                                        :options="services" 
                                        label=""
                                        stack-label
                                        outlined
                                        @filter="fillterServices"
                                        use-input
                                        input-debounce="0"
                                        :option-label="option => `${option.serviceCode} - ${option.name}`"
                                        option-value="serviceCode"
                                        :rules="[
                                            val => !!val || 'Um valor precisa ser definido!'  
                                        ]"
                                        
                                    >
                                        <template v-slot:label>
                                            <div class="mt-2">
                                                <span>Serviço</span>
                                                <q-avatar size="35px" icon="home_repair_service" />
                                                
                                            </div>
                                        </template>
                                    </q-select>
                                </div>
                            </div>

                            <div class="mr-4">
                                <div v-if="valueCommissionByMoney">
                                    <q-input 
                                        v-model="commission.fixCommission" 
                                        outlined
                                        type="text" 
                                        mask="##,##"
                                        reverse-fill-mask
                                        label="Valor em R$" 
                                        :rules="[validateValueCommission]"
                                    />
                                </div>

                                <div v-if="valueCommissionByPercent">
                                    <q-input 
                                        v-model="commission.percCommission" 
                                        outlined
                                        type="text" 
                                        mask="##,##"
                                        reverse-fill-mask
                                        label="Valor em %"
                                        :rules="[validateValueCommission]"
                                    />
                                </div>
                            </div>
                        </div>
                    
                        <div class="flex justify-end">
                            <q-btn 
                                no-caps
                                label="Finalizar cadastro" 
                                type="submit" 
                                color="primary"
                                
                            />
                            
                        </div>
                    </q-form>
                </div>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref, watch } from 'vue';

    interface Service {
        serviceCode: number,
        name: string
    };

    interface Category {
        categoryCode: number,
        name: string
    };

    interface Commission {
        ownerCode: number, 
        serviceCode:  any, 
        categoryCode: any, 
        attendantCode: number, 
        percCommission: number,
        fixCommission: number,
    };

    interface ReleaseForEmit {
        hasByOne: boolean,
        hasValueByOne: boolean
    };
    
    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        attendantName: string,
        attendantCode: number
    }>();

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;

    const allServices = ref<Service[]>([]);
    const services = ref<Service[]>([]);

    const allCategories = ref<Category[]>([]);
    const categories = ref<Category[]>([]);
    
    const commission = ref<Commission>({
        attendantCode: 0,
        categoryCode: undefined,
        fixCommission: 0,
        ownerCode: ownerCode,
        percCommission: 0,
        serviceCode: undefined 
    });

    const releaseForEmit = ref<ReleaseForEmit>({
        hasByOne: false,
        hasValueByOne: false
    });

    let commissionByService = ref<boolean>(false);
    let commissionByCategory = ref<boolean>(false);

    let valueCommissionByPercent = ref<boolean>(false);
    let valueCommissionByMoney = ref<boolean>(false);

    let releaseValueTypeCommission = ref<boolean>(false);

    watch(commissionByCategory, () => {
        if(commissionByCategory.value)
        {
            releaseValueTypeCommission.value = true;  
            commissionByService.value = false;  
            releaseForEmit.value.hasByOne = true;

        } else {
            releaseForEmit.value.hasByOne = false;
        };

        if(!commissionByCategory.value && !commissionByService.value)
        {
            releaseValueTypeCommission.value = false;
            valueCommissionByMoney.value = false;
            commission.value.fixCommission = 0;

            valueCommissionByPercent.value = false;
            commission.value.percCommission = 0;
        };
    });

    watch(commissionByService, () => {
        if(commissionByService.value)
        {
            releaseValueTypeCommission.value = true;
            commissionByCategory.value = false;  
            releaseForEmit.value.hasByOne = true;

        } else {
            releaseForEmit.value.hasByOne = false;
        };

        if(!commissionByService.value && !commissionByCategory.value) {
            releaseValueTypeCommission.value = false;
            valueCommissionByMoney.value = false;
            commission.value.fixCommission = 0;

            valueCommissionByPercent.value = false;
            commission.value.percCommission = 0;
        }; 
    });

    watch(valueCommissionByPercent, () => {
        if (valueCommissionByPercent.value)
        {
            valueCommissionByMoney.value = false;  
            releaseForEmit.value.hasValueByOne = true;
        } else {
            releaseForEmit.value.hasValueByOne = false;
        };
    });

    watch(valueCommissionByMoney, () => {
        if(valueCommissionByMoney.value)
        {        
            valueCommissionByPercent.value = false;  
            releaseForEmit.value.hasValueByOne = true;
        } else {
            releaseForEmit.value.hasValueByOne = false;
        };
    });

    function validateValueCommission(val: string) {
        if(Number(val) <= 0)
        {
            return 'O valor de comissão precisa ser maior que zero!';
        };

        return true;
    }

    const getAllServices = async () => {
        try {
            const res = await api.get(`/services/all/not-commission/${ownerCode}`);
            const data = camelcaseKeys(res.data.data, { deep: true });

            services.value = data;

            allServices.value = [...services.value];
            
        } catch (error) {
            console.error('Erro: ', error);
        };
    };

    const fillterServices = (val: string, doneFn: any) => {
        if(val === '')
        {
            doneFn(() => {
                services.value = allServices.value;
            });
        };
        
        doneFn(() => {
            services.value = allServices.value.filter(service => service.name.toLowerCase().trim().includes(val.toLowerCase()));

        });

        console.log(services.value);
    };

    const getAllCategories = async () => {
        const res = await api.get(`/categories/all/not-commission/${ownerCode}`)
        const data = camelcaseKeys(res.data.data, { deep: true });

        categories.value = data;
        allCategories.value = [...categories.value];
    };

    const createCommission = async () => {
        if(!releaseForEmit.value.hasByOne || !releaseForEmit.value.hasValueByOne)
        {
            $q.notify({
                color: 'red',
                message: !releaseForEmit.value.hasByOne ? 'A comissão deve ser feito por um meio! ( categoria ou serviço )' : 'Um valor de comissão precisa ser definidio! ( percentual ou fixo )',
                position: 'top-right'
            });

        } else {
            const isService: Service = commission.value.serviceCode;
            const isCategory: Category = commission.value.categoryCode;

            const payload: Commission = {
                attendantCode: props.attendantCode,
                categoryCode: isCategory ? isCategory.categoryCode : null,
                serviceCode: isService ? isService.serviceCode : null,
                fixCommission: Number(commission.value.fixCommission.toString().replace(',', '.')),
                percCommission: Number(commission.value.percCommission.toString().replace(',', '.')),
                ownerCode: commission.value.ownerCode

            };

            try {
                const res = await api.post('/commission/create', payload, {
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
                        position: 'top'
                    });
                };
                
            } catch (error: any) {
                $q.notify({
                    color: 'green',
                    message: error?.response?.data?.message,
                    position: 'top'

                });
            };
        };
    };  

    onMounted(() => {
        getAllCategories();
        getAllServices();
    }); 
</script>
