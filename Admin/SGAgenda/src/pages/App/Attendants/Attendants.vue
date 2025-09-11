<template>
    <q-page padding>
        <section class="text-xl" v-if="!showAttendant">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Atendentes</h2>

                    <div class="mt-auto mb-auto">
                        <q-btn 
                            no-caps 
                            class="bg-sky-500 text-white" 
                            @click="attendantManagement" 
                            label="Cadastrar novo atendente"
                        
                        />
                    </div>
                </div>

                <div class="">
                    <q-table
                        borded
                        :rows="allAttendant"
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
                                    <span class="text-xs">Buscar por um atendente ...</span>
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
                                    <template v-if="col.name === 'isAttendant'">
                                        <div class="text-center">
                                            {{ col.value ? 'Atendente' : 'Administrador' }}
                                        </div>
                                    </template>

                                    <template v-else>
                                        <div class="text-center">
                                            {{ col.value }}

                                        </div>
                                    </template>

                                    <template v-if="col.name === 'actions'">
                                        <div class="text-center">
                                            |
                                                <q-btn size="10px" no-caps color="black" icon="alarm_add" flat @click=""/> <!-- Horários -->
                                                <q-btn size="10px" no-caps color="black" icon="money" flat @click="commissionManagement(props.row.name, props.row.attendantCode)"/> <!-- Comissão -->
                                                <q-btn size="10px" no-caps color="black" icon="hourglass_disabled" flat @click=""/> <!-- Exceções -->
                                            |
                                                <q-btn size="10px" no-caps color="black" icon="edit_square" flat @click=""/>
                                                <q-btn size="10px" no-caps color="red" icon="delete" flat @click=""/>
                                            |
                                        </div>
                                    </template>
                                </q-td>
                            </q-tr>
                        </template>

                        <template v-slot:no-data>
                            <div class="ml-auto mr-auto">
                                <q-icon name="warning" size="30px"/>
                                <span class="mt-auto mb-auto ml-2 text-xs">Sem atendentes cadastrados</span>

                            </div>
                        </template>

                    </q-table>
                </div>
            </div>
        </section>

        <AttendantManagement 
            v-if="showAttendantManagement"
            @close="attPage($event)"
            :action="'create'"
            :attendant-code="undefined"

        />

        <CommissionManagement
            v-if="showCommissionManagement"
            @close="attPage($event)"
            :attendantName="selecetedAttendantName"
            :attendantCode="selecetedAttendantCode"
        />
        
    </q-page>
</template>

<script setup lang="ts">
    import { LocalStorage, QTableColumn, useQuasar } from 'quasar';
    import { onMounted, ref } from 'vue';
    import AttendantManagement from 'src/components/App/AttendantManagement/AttendantManagement.vue';
    import CommissionManagement from 'src/components/App/AttendantManagement/Commission/CommissionManagement.vue';
    import camelcaseKeys from 'camelcase-keys';
    import { api } from 'src/boot/axios';
    
    interface AttendantData {
        attendantCode: number,
        name: string,
        email: string,

    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;

    const columns: QTableColumn[] = [
        {
            name: 'attendantCode',
            label: 'Cód',
            field: 'attendantCode',
            align: 'center'
        },
        {
            name: 'name',
            label: 'Atendente',
            field: 'name',
            align: 'center'
        },
        {
            name: 'email',
            label: 'E-mail',
            field: 'email',
            align: 'center'
        },
        {
            name: 'isAttendant',
            label: 'Função',
            field: 'isAttendant',
            align: 'center'
        },
        {
            name: 'actions',
            label: 'Ações',
            field: 'actions',
            'align': 'center'
        }
        
    ];

    let showAttendantManagement = ref<boolean>(false);
    let showCommissionManagement = ref<boolean>(false);

    let allAttendant = ref<AttendantData[]>([]);
    let attendants = ref<AttendantData[]>([]);
    let searchInput = ref<string>('');

    let showAttendant = ref<boolean>(false); 
    let selecetedAttendantName = ref<string>(''); 
    let selecetedAttendantCode = ref<number>(0); 

    const getAllAttendant = async () => {
        const res = await api.get(`/attendants/all/${ownerCode}`); 
        const data = camelcaseKeys(res.data, { deep: true });

        allAttendant.value = data.data;
        attendants.value = [...allAttendant.value];
    };  

    const attPage = (event: boolean) => {
        showAttendantManagement.value = !event;
        showCommissionManagement.value = !event;
        showAttendant.value = !event;

        getAllAttendant();
    };

    const attendantManagement = () => {
        showAttendant.value = true;
        showAttendantManagement.value = !showAttendantManagement.value;
    };

    const commissionManagement = (attendantName: string, attendantCode: number) => {
        selecetedAttendantName.value = attendantName;
        selecetedAttendantCode.value = attendantCode;            

        showAttendant.value = true;
        showCommissionManagement.value = !showCommissionManagement.value;
    };

    const exceptionsManagement = () => {

    };

    const hoursManagement = () => {

    };

    onMounted(() => {
        getAllAttendant();
    });
</script>