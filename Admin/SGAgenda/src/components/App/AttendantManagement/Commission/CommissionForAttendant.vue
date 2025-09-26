<template>
    <q-page padding>
        <section class="text-xl" v-if="!showCommissionManagement">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <div class="flex justify-between">                    
                        <h2 class="text-gray-600 m-2">Comissões do(a) atendente: {{ props.attendantName }}</h2>
                    </div>

                    <div class="mt-auto mb-auto">
                        <q-btn 
                            no-caps 
                            class="bg-sky-500 text-white" 
                            @click="showCommissionManagement = !showCommissionManagement" 
                            label="Cadastrar uma nova comissão"
                        
                        />
                    </div>
                </div>
        
                <div>
                    <q-table
                        borded
                        :rows="allCommissions"
                        :columns="columns"
                        row-key="name"
                        class="rounded-xl"
                    >
                        <template v-slot:top-left>
                            <div 
                                @click="emits('close', true)"
                                class="flex mb-auto mt-auto cursor-pointer text-xs"
                            
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>      
                                <span>
                                    Voltar para listagem
                                </span>
                            </div>

                        </template>
                        <template v-slot:body="props">
                            <q-tr
                                :props="props"
                            >
                                <q-td
                                    v-for="col in props.cols"
                                >   
                                    <template v-if="col.name === 'serviceCode'">
                                        <div class="text-center">
                                            {{ col.value ? col.value : '-' }}
                                        </div>
                                    </template>

                                    <template v-else-if="col.name === 'serviceName'">
                                        <div class="text-center">
                                            {{ col.value ? col.value : '-' }}
                                        </div>
                                    </template>

                                    <template v-else-if="col.name === 'categoryCode'">
                                        <div class="text-center">
                                            {{ col.value ? col.value : '-' }}
                                        </div>
                                    </template>

                                    <template v-else-if="col.name === 'categoryName'">
                                        <div class="text-center">
                                            {{ col.value ? col.value : '-' }}
                                        </div>
                                    </template>

                                    <template v-else-if="col.name === 'percCommission'">
                                        <div class="text-center">
                                            {{ col.value ? col.value : '-' }}
                                        </div>
                                    </template>

                                    
                                    <template v-else-if="col.name === 'fixedCommission'">
                                        <div class="text-center">
                                            {{ col.value ? col.value : '-' }}
                                        </div>
                                    </template>

                                    <template v-else>
                                        <div class="text-center">
                                            {{ col.value }}

                                        </div>
                                    </template>
                                </q-td>
                            </q-tr>
                        </template>

                        <template v-slot:no-data>
                            <div class="ml-auto mr-auto">
                                <q-icon name="warning" size="30px"/>
                                <span class="mt-auto mb-auto ml-2 text-xs">Sem comissões cadastrados</span>

                            </div>
                        </template>

                    </q-table>
                </div>
            </div>
        </section>
        
        <CommissionManagement
            v-if="showCommissionManagement"
            @close="attPage($event)"
            :attendantName="selecetedAttendantName"
            :attendantCode="selecetedAttendantCode"
        />

    </q-page>
</template>

<script setup lang="ts">
    import { onMounted, ref } from 'vue';
    import CommissionManagement from 'src/components/App/AttendantManagement/Commission/CommissionManagement.vue';
    import { LocalStorage, QTableColumn } from 'quasar';
    import { api } from 'src/boot/axios';
    import camelcaseKeys from 'camelcase-keys';

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        attendantName: string,
        attendantCode: number
    }>();

    const ownerCode = LocalStorage.getItem("ownerCode");

    let showCommissionManagement = ref<boolean>(false);
    let selecetedAttendantCode = props.attendantCode; 
    let selecetedAttendantName = props.attendantName; 

    const allCommissions = ref<string[]>([]);

    const columns = ref<QTableColumn[]>([
        {
            field: 'commissionAttendantsCode',
            label: 'Cód',
            name: 'commissionAttendantsCode',
            align: 'center'
        },
        {
            field: 'attendantCode',
            label: 'Cód. Atendente',
            name: 'attendantCode',
            align: 'center'
        },
        {
            field: 'attendantName',
            label: 'Atendente',
            name: 'attendantName',
            align: 'center'
        },
        {
            field: 'serviceCode',
            label: 'Cód. Serviço',
            name: 'serviceCode',
            align: 'center'
        },
        {
            field: 'serviceName',
            label: 'Serviço',
            name: 'serviceName',
            align: 'center'
        },
        {
            field: 'categoryCode',
            label: 'Cód. Categoria',
            name: 'categoryCode',
            align: 'center'
        },
        {
            field: 'categoryName',
            label: 'Categoria',
            name: 'categoryName',
            align: 'center'
        },
        {
            field: 'percCommission',
            label: '%',
            name: 'percCommission',
            align: 'center'
        },
        {
            field: 'fixedCommission',
            label: 'R$',
            name: 'fixedCommission',
            align: 'center',
            format(val: number){
                return `R$ ${val.toFixed(2).toString().replace('.', ',')}`
            } 
        },
    ]);

    const attPage = (event: boolean) => {
        showCommissionManagement.value = !event;

    };

    const getAllCommissions = async () => {
        const res = await api.get(`/commission/all/${ownerCode}/${props.attendantCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });
        allCommissions.value = data;

    };

    onMounted(() => {
        getAllCommissions();
    });
</script>