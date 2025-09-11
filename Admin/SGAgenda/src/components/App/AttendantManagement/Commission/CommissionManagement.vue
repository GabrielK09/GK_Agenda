<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">                    
                    <h2 class="text-gray-600 m-2">Comissões do atendente: {{ props.attendantName }}</h2>
                </div>

                <div class="bg-white p-8 text-xs">
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
                        class="q-gutter-md mt-4 rounded-xl"
                    >   
                        <div class="grid grid-cols-3">
                            <div class="">
                                <q-checkbox left-label v-model="commissionByCategory" label="Comissão por Categoria" />
                                <q-avatar size="35px" icon="category" />

                                <q-checkbox left-label v-model="commissionByService" label="Comissão por Serviço" />
                                <q-avatar size="35px" icon="home_repair_service" />

                                <q-checkbox left-label v-model="valueCommissionByPercent" label="Comissão por %" />
                                <q-avatar size="35px" icon="percent" />

                                <q-checkbox left-label v-model="valueCommissionByMoney" label="Comissão por R$" />
                                <q-avatar size="35px" icon="money" />
                                
                            </div>
                        </div>

                        <div class="grid grid-cols-2 mb-2" >
                            <div v-if="commissionByCategory">                                
                                <q-select 
                                    v-model="commission.categoryCode" 
                                    :options="categories" 
                                    label="Categoria"
                                    stack-label
                                    outlined
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
                            <div v-else>
                                <q-select 
                                    v-model="commission.serviceCode" 
                                    :options="services" 
                                    label="Serviço"
                                    stack-label
                                    outlined
                                >
                                    <template v-slot:label>
                                        <div class="mt-2">
                                            <span>Serviço</span>
                                            <q-avatar size="35px" icon="home_repair_service" />
                                            
                                        </div>
                                    </template>
                                </q-select>
                            </div>

                            <div class="ml-12">
                                <div v-if="valueCommissionByMoney">
                                    <q-input 
                                        v-model="commission.fixCommission" 
                                        outlined
                                        type="text" 
                                        label="Valor em R$" 
                                    />
                                </div>
                                <div v-else>
                                    <q-input 
                                        v-model="commission.percCommission" 
                                        outlined
                                        type="text" 
                                        label="Valor em %"
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
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref, watch } from 'vue';

    interface Service {
        serviceCode: number,
        service: string
    };

    interface Category {
        categoryCode: number,
        category: string
    };

    interface Commission {
        ownerCode: number, 
        serviceCode: number|undefined, 
        categoryCode: number|undefined, 
        attendantCode: number, 
        percCommission: number,
        fixCommission: number,
    };
    
    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        attendantName: string,
        attendantCode: number
    }>();

    const ownerCode = LocalStorage.getItem("ownerCode") as number;

    const allServices = ref<Service[]>([]);
    const services = ref<Service[]>([]);

    const allCategories = ref<Category[]>([]);
    const categories = ref<Category[]>([]);
    
    const commission = ref<Commission>({
        attendantCode: 0,
        categoryCode: undefined,
        fixCommission: 0,
        ownerCode: 0,
        percCommission: 0,
        serviceCode: undefined 
    });

    let commissionByService = ref<boolean>(false);
    let commissionByCategory = ref<boolean>(false);

    let valueCommissionByPercent = ref<boolean>(false);
    let valueCommissionByMoney = ref<boolean>(false);

    watch(commissionByCategory, () => {
        if(commissionByCategory.value)
        {
            commissionByService.value = false;  
        };
    });

    watch(commissionByService, () => {
        if(commissionByService.value)
        {
            commissionByCategory.value = false;  
        };
    });

    watch(valueCommissionByPercent, () => {
        if(valueCommissionByPercent.value)
        {
            valueCommissionByMoney.value = false;  
        };
    });

    watch(valueCommissionByMoney, () => {
        if(valueCommissionByMoney.value)
        {
            valueCommissionByPercent.value = false;  
        };
    });

    const getAllServices = async () => {
        try {
            const res = await api.get(`/services/all/${ownerCode}`);
            const data = camelcaseKeys(res.data.data, { deep: true });
            services.value = data;
            allServices.value = [...services.value];
            
        } catch (error) {
            console.error('Erro: ', error);
        };
    };

    const createCommission = async () => {
        //const res = await api.post()
    };  

    onMounted(() => {
        getAllServices();
    }); 
</script>