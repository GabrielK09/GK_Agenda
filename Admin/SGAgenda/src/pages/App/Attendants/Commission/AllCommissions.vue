<template>
    <q-page padding>
        <section class="text-xl" v-if="!showCommissions">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Comissões</h2>

                </div>

                <div class="">
                    <q-table
                        borded
                        :rows="allCommissions"
                        :columns="columns"
                        row-key="name"
                        class="rounded-xl"
                    >
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
    </q-page>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { LocalStorage, QTableColumn, useQuasar } from 'quasar';
    import { onMounted, ref } from 'vue';
    import camelcaseKeys from 'camelcase-keys';

    interface Services {
        serviceCode: number,
        name: string,
        category: string,
        price: number,
        isHomeService: boolean,
        checkAvailability: boolean
    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;

    const columns: QTableColumn[] = [
        {
            name: 'commissionCode',
            label: 'Cód do registro',
            field: 'commissionCode',
            align: 'center'
        },
        {
            name: 'attendant',
            label: 'Atendente',
            field: 'attendant',
            align: 'center'
        },
        {
            name: 'service',
            label: 'Serviço prestado',
            field: 'service',
            align: 'center'

        },
        {
            name: 'total_commission',
            label: 'Total de comissão',
            field: 'total_commission',
            align: 'center'
        },
    ];

    let allCommissions = ref<Services[]>([]);
    let services = ref<Services[]>([]);

    let showCommissions = ref<boolean>(false);

    function formatVal(val: number | string) 
    {    
        const num = typeof val === 'string' ? parseFloat(val) : val;
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(num);
    };

    const getAllCommissions = async () => {
        try {
            const res = await api.get(`/services/all/${ownerCode}`);
            const data = camelcaseKeys(res.data.data, { deep: true });
            services.value = data;
            allCommissions.value = [...services.value];
            
        } catch (error) {
            console.error('Erro: ', error);
        };
    };


    const showshowCommissions = () => {
        showCommissions.value = !showCommissions.value;
    };

    const attPage = (event: boolean) => {
        showCommissions.value = !event;
        getAllCommissions();
    };

    onMounted(() => {
        getAllCommissions();
    });
</script>