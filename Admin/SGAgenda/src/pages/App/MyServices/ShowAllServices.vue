<template>
    <q-page padding>
        <section class="text-xl" v-if="!serviceManagement">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Serviços</h2>

                    <div class="mt-auto mb-auto">
                        <q-btn 
                            no-caps 
                            class="bg-sky-500 text-white" 
                            @click="showServiceManagement('create', undefined)" 
                            label="Cadastrar novo serviço"
                        
                        />
                    </div>
                </div>

                <div class="">
                    <q-table
                        borded
                        :rows="allServices"
                        :columns="columns"
                        row-key="name"
                        class="rounded-xl"
                    >
                        <template v-slot:top-right>
                            <q-input 
                                outlined
                                v-model="searchInput" 
                                type="text" 
                                label="" 
                            >
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                <template v-slot:label>
                                    <span class="text-xs">Buscar por um serviço ...</span>
                                </template>
                            </q-input>
                        </template>

                        <template v-slot:body="props">
                            <q-tr
                                :props="props"
                            >
                                <q-td
                                    v-for="(col, i) in props.cols"
                                >
                                    <template v-if="col.name === 'actions'">
                                        <div class="text-center">
                                            <q-btn size="10px" no-caps color="black" icon="edit_square" flat @click="showServiceManagement('update', props.row.serviceCode)"/>
                                            <q-btn size="10px" no-caps color="red" icon="delete" flat @click="deleteService(props.row.serviceCode)"/>

                                        </div>
                                    </template>

                                    <template v-else-if="col.name === 'checkAvailability'">
                                        <div class="text-center">
                                            <q-icon :name="col.value ? 'check' : 'close'" :color="col.value ? 'green' : 'red'" size="25px"/>
                                        </div>
                                    </template>

                                    <template v-else-if="col.name === 'isHomeService'">
                                        <div class="text-center">
                                            <q-icon :name="col.value ? 'check' : 'close'" :color="col.value ? 'green' : 'red'" size="25px"/>
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
                                <span class="mt-auto mb-auto ml-2 text-xs">Sem serviços cadastrados</span>

                            </div>
                        </template>

                    </q-table>
                </div>
            </div>
        </section>
        <ServiceManagement
            v-if="serviceManagement"
            @close="serviceManagement = !$event"
            :action="action"
            :service-code="selectedServiceCode"

        />
    </q-page>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { LocalStorage, QTableColumn } from 'quasar';
    import { onMounted, ref } from 'vue';
    import ServiceManagement from 'src/components/App/ServiceManagement/ServiceManagement.vue';
    import camelcaseKeys from 'camelcase-keys';

    interface Services {
        serviceCode: number,
        name: string,
        category: string,
        price: number,
        isHomeService: boolean,
        checkAvailability: boolean
    };

    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    const width = LocalStorage.getItem("width") as number;

    const columns: QTableColumn[] = [
        {
            name: 'serviceCode',
            label: 'Cód',
            field: 'serviceCode',
            align: 'center'
        },
        {
            name: 'name',
            label: 'Nome',
            field: 'name',
            align: 'center'
        },
        {
            name: 'category',
            label: 'Categoria',
            field: 'category',
            align: 'center'
        },
        {
            name: 'price',
            label: 'Preço',
            field: 'price',
            align: 'center',
            format: formatVal
        },
        {
            name: 'isHomeService',
            label: 'A domícilio',
            field: 'isHomeService',
            align: 'center'
        },
        {
            name: 'checkAvailability',
            label: 'Checar a disponibilidade',
            field: 'checkAvailability',
            align: 'center'
        },
        {
            name: 'actions',
            label: '',
            field: 'actions',
            align: 'right'
        }
    ];

    let allServices = ref<Services[]>([]);
    let services = ref<Services[]>([]);

    let searchInput = ref<string>('');
    let serviceManagement = ref<boolean>(false);

    let action = ref<string>('');
    let selectedServiceCode = ref<number|undefined>(0);

    function formatVal(val: number | string) 
    {    
        const num = typeof val === 'string' ? parseFloat(val) : val;
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(num);
    };

    const search = () => {
        
    };

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

    const editService = (id: number) => {
        console.log(id);

    };

    const deleteService = (id: number) => {
        console.log(id);

    };

    const showServiceManagement = (management: string, serviceCode:number|undefined) => {
        action.value = management;
        selectedServiceCode.value = serviceCode;
        serviceManagement.value = !serviceManagement.value;
    };

    onMounted(() => {
        getAllServices();
    });
</script>