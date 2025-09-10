<template>
    <q-page padding>
        <section class="text-xl" v-if="!attendantManagement">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Atendentes</h2>

                    <div class="mt-auto mb-auto">
                        <q-btn 
                            no-caps 
                            class="bg-sky-500 text-white" 
                            @click="" 
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
                                    <template v-if="col.name === 'actions'">
                                        <div class="text-center">
                                            <q-btn size="10px" no-caps color="black" icon="edit_square" flat @click=""/>
                                            <q-btn size="10px" no-caps color="red" icon="delete" flat @click="(props.row.serviceCode)"/>

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
                                <span class="mt-auto mb-auto ml-2 text-xs">Sem atendentes cadastrados</span>

                            </div>
                        </template>

                    </q-table>
                </div>
            </div>
        </section>
        
        
    </q-page>
</template>

<script setup lang="ts">
    import { LocalStorage, QTableColumn, useQuasar } from 'quasar';
    import { onMounted, ref } from 'vue';
    import camelcaseKeys from 'camelcase-keys';
    
    interface AttendantData {
        attendantCode: number,
        name: string,
        email: string,

    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    const width = LocalStorage.getItem("width") as number;

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
            name: 'actions',
            label: '',
            field: 'actions',
            align: 'right'
        }
        
    ];

    let attendantManagement = ref<boolean>(false);

    let allAttendant = ref<AttendantData[]>([]);
    let attendants = ref<AttendantData[]>([]);
    let searchInput = ref<string>('');

</script>