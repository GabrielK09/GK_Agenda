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
                            @click="attendantManagement('create', 0)" 
                            label="Cadastrar novo atendente"
                        
                        />
                    </div>
                </div>

                <div class="">
                    <q-table
                        borded
                        :rows="attendants"
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
                                @update:model-value="filterAttendant"
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
                                        <div 
                                            class="text-center"
                                            :class="{
                                                'text-gray-400': !props.row.active
                                            }"
                                        >
                                            {{ col.value ? 'Atendente' : 'Administrador' }}
                                        </div>
                                    </template>

                                    <template v-else>
                                        <div 
                                            class="text-center"
                                            :class="{
                                                'text-gray-400': !props.row.active
                                            }"
                                        >
                                            {{ col.value }}

                                        </div>
                                    </template>

                                    <template v-if="col.name === 'actions'">
                                        <div class="text-center">
                                            |
                                                <q-btn :disable="!props.row.active" size="10px" no-caps color="black" icon="alarm_add" flat @click="hoursManagement(props.row.name, props.row.attendantCode)"/> <!-- Horários -->
                                                <q-btn :disable="!props.row.active" size="10px" no-caps color="black" icon="money" flat @click="commissionManagement(props.row.name, props.row.attendantCode)"/> <!-- Comissão -->
                                            |
                                                <q-btn :disable="!props.row.active" size="10px" no-caps color="black" icon="edit_square" flat @click="attendantManagement('update', props.row.attendantCode)" />

                                                <q-btn 
                                                    size="10px" 
                                                    no-caps
                                                    :disable="!props.row.isAttendant"
                                                    :color="props.row.active ? 'red' : 'green'" 
                                                    :icon="props.row.active ? 'delete' : 'check'" 
                                                    flat 
                                                    @click.prevent="props.row.active ? showDialogDeleteAttendant(props.row.attendantCode) :  showDialogActiveAttendant(props.row.attendantCode)"
                                                />
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
            :action="selecetedAction"
            :attendantCode="selecetedAttendantCode"

        />

        <CommissionForAttendant
            v-if="showCommissionForAttendat"
            @close="attPage($event)"
            :attendantName="selecetedAttendantName"
            :attendantCode="selecetedAttendantCode"
        />

        <HourManagement
            v-if="showHoursForAttendat"
            @close="attPage($event)"
            :attendantName="selecetedAttendantName"
            :attendantCode="selecetedAttendantCode"
        />

    </q-page>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { LocalStorage, QTableColumn, useQuasar } from 'quasar';
    import { onMounted, ref } from 'vue';
    import camelcaseKeys from 'camelcase-keys';
    import AttendantManagement from 'src/components/App/AttendantManagement/AttendantManagement.vue';    
    import CommissionForAttendant from 'src/components/App/AttendantManagement/Commission/CommissionForAttendant.vue';
    import HourManagement from 'src/components/App/AttendantManagement/Hours/HourManagement.vue';
    
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
    
    let allAttendant = ref<AttendantData[]>([]);
    let attendants = ref<AttendantData[]>([]);
    let searchInput = ref<string>('');

    let showCommissionForAttendat = ref<boolean>(false); 
    let showHoursForAttendat = ref<boolean>(false); 

    let showAttendant = ref<boolean>(false); 
    let selecetedAttendantName = ref<string>(''); 
    let selecetedAction = ref<string>(''); 
    let selecetedAttendantCode = ref<number>(0); 

    const showDialogDeleteAttendant = (attendantCode: number) => {
        $q.dialog({
            title: 'Desativar atendente',
            message: `Deseja realmente desativar esse atendente (${attendantCode})?`,
            cancel: {
                push: true,
                label: 'Não',
                color: 'red',
            },

            ok: {
                push: true,
                label: 'Sim',
                color: 'green',
            },

        }).onOk(() => {
            deleteService(attendantCode);

        }).onCancel(() => {
            return;
        });
    };

    const showDialogActiveAttendant = (attendantCode: number) => {
        $q.dialog({
            title: 'Ativar atendente',
            message: `Deseja realmente ativar esse atendente (${attendantCode})?`,
            cancel: {
                push: true,
                label: 'Não',
                color: 'red',
            },

            ok: {
                push: true,
                label: 'Sim',
                color: 'green',
            },

        }).onOk(() => {
            activeService(attendantCode);

        }).onCancel(() => {
            return;
        });
    };

    const activeService = async (attendantCode: number) => {
        const res = await api.put(`/attendants/active/${ownerCode}/${attendantCode}`);
        const data = res.data;

        if(data.success)
        {
            $q.notify({
                color: 'green',
                message: data.message,
                position: 'top',
                timeout: 1200
            });
        };
        getAllAttendant();
    };

    const deleteService = async (attendantCode: number) => {
        const res = await api.delete(`/attendants/delete/${ownerCode}/${attendantCode}`);
        const data = res.data;

        if(data.success)
        {
            $q.notify({
                color: 'green',
                message: data.message,
                position: 'top',
                timeout: 1200
            });
        };
        getAllAttendant();
    };

    const getAllAttendant = async () => {
        const res = await api.get(`/attendants/all/${ownerCode}`); 
        const data = camelcaseKeys(res.data, { deep: true });

        allAttendant.value = data.data;
        attendants.value = [...allAttendant.value];
    };  

    const attPage = (event: boolean) => {
        showAttendantManagement.value = !event;
        showCommissionForAttendat.value = !event;
        showHoursForAttendat.value = !event;
        showAttendant.value = !event;

        getAllAttendant();
    };

    const attendantManagement = (action: string, attendantCode: number) => {
        selecetedAction.value = action;
        selecetedAttendantCode.value = attendantCode;

        showAttendant.value = true;
        showAttendantManagement.value = !showAttendantManagement.value;
    };

    const commissionManagement = (attendantName: string, attendantCode: number) => {
        selecetedAttendantName.value = attendantName;
        selecetedAttendantCode.value = attendantCode;            

        showAttendant.value = true;
        showCommissionForAttendat.value = !showCommissionForAttendat.value;
    };

    const hoursManagement = (attendantName: string, attendantCode: number) => {
        selecetedAttendantName.value = attendantName;
        selecetedAttendantCode.value = attendantCode;            

        showAttendant.value = true;
        showHoursForAttendat.value = !showHoursForAttendat.value;
    };

    const filterAttendant = () => {    
        console.log(searchInput.value);
        
        attendants.value = allAttendant.value.filter(attendant => attendant.name.toLowerCase().includes(searchInput.value));
        console.log(attendants.value);
        
    };

    onMounted(() => {
        getAllAttendant();
    });
</script>