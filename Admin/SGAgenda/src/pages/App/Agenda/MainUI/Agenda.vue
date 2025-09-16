<template>
    <q-page padding>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Agendamentos</h2>
                </div>
                
                <div class="rounded-md bg-white shadow-md">
                    <div class="grid-calender grid grid-cols-2">
                        <div class="calender">
                            <AgendaDay
                                :active-scheduling="events"
                                @change-date="changeDateTable($event)"
                            />
                        </div>

                        <div class="list-calender">
                            <q-table
                                flat 
                                bordered
                                hide-bottom
                                :rows="schedulings"
                                :columns="columns"
                                row-key="schedulingCode"
                            >
                                <template v-slot:body="props">
                                    <q-tr
                                        :props="props"
                                        @click="openSchedulingDetails(props.row.schedulingCode)"
                                    >
                                        <q-td
                                            v-for="col in props.cols"
                                            class="cursor-pointer"
                                        >  
                                            <div 
                                                class="text-center"                
                                            >
                                                {{ col.value }} 

                                            </div>
                                        </q-td>
                                    </q-tr>
                                </template>
                            </q-table>
                        </div>
                    </div>
                </div>        
            </div>
        </section>

        <SchedulingManagement
            v-if="showDetailScheduling"
            :show-detail-scheduling="showDetailScheduling"
            :scheduling-code="selectedSchedulingCode"
            @close="showDetailScheduling = !$event"

        />
            
    </q-page>
</template>

<script setup lang="ts">
    import AgendaDay from '../Calender/AgendaDay.vue';
    import SchedulingManagement from 'src/components/App/AgendaManagement/SchedulingManagement.vue';
    import { onMounted, ref } from 'vue';
    import { LocalStorage, QTableColumn } from 'quasar';
    import { api } from 'src/boot/axios';
    import camelcaseKeys from 'camelcase-keys';

    interface Scheduling {
        schedulingCode: number,
        customerName: string,
        customerPhone: string,
        serviceCode: number,
        name: string
        attendantCode: number,
        attendant: string
        hour: string,
        day: string,
        month: string,
        completed: number
    };

    const activeSchedulings = ref<string[]>([]);

    const columns = ref<QTableColumn[]>([
        {
            field: 'day',
            label: 'Data do agendamento',
            name: 'day',
            align: 'center'
        },
        {
            field: 'hour',
            label: 'Horário',
            name: 'hour',
            align: 'center'
        },
        {
            field: 'customerName',
            label: 'Cliente',
            name: 'customerName',
            align: 'center',
            
        },
        {
            field: 'service',
            label: 'Serviço',
            name: 'service',
            align: 'center'
        }
    ]);

    const allSchedulings = ref<Scheduling[]>([]);

    const schedulings = ref<Scheduling[]>([]);
    let events = ref<string[]>([]);
    const ownerCode = LocalStorage.getItem("ownerCode");

    let showDetailScheduling = ref<boolean>(false);
    let selectedSchedulingCode = ref<number>(0);

    const changeDateTable = (date: string) => {
        filter(date);
    };

    const changeCompleted = (val: number): boolean => {
        console.log(val === 1 ? true : false);
        
        return val === 1 ? true : false;
    };  

    const filter = (informedDate: string) => {
        console.log('Data selecionada: ', informedDate);
        console.log('allSchedulings.value: ', allSchedulings.value);
        schedulings.value = allSchedulings.value.filter(date => date.day === informedDate && date.completed === 0);
        
    };

    const getAllSchedulings = async () => {
        const res = await api.get(`/schedule/get-all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });

        schedulings.value = data;
        allSchedulings.value = [...schedulings.value];

        console.log(schedulings.value);

        const dates: string[] = schedulings.value.filter(d => d.completed === 0).map(scheduling => scheduling.day)
        
        for (let i = 0; i < dates.length; i++) {
            const date = dates[i] as string;
            activeSchedulings.value.push(date);
            
        };

        formatDateForFilter();
        console.log('activeSchedulings: ', activeSchedulings.value);
        
    };

    const formatDateForFilter = () => {
        events.value = activeSchedulings.value.map(date => {
            // 01/01/1000 - DD/MM/YYYY
            const splitDate = date.split('/');
            const day = splitDate[0];
            const month = splitDate[1];
            const year = splitDate[2];
            return `${year}/${month}/${day}`;
        });
    };  

    const openSchedulingDetails = (schedulingCode: number) => {
        console.log('openSchedulingDetails');
        console.log('schedulingCode: ', schedulingCode);
        if(!schedulingCode) return;

        showDetailScheduling.value = !showDetailScheduling.value;
        selectedSchedulingCode.value = schedulingCode;
    };

    onMounted(() => {
        getAllSchedulings();
    }); 

</script>

<style lang="scss">
    @media (min-width: 1000px) {
        .grid-calender {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin: 0 2rem 0 auto;    

            .calender {
                display: flex;
                justify-content: center;
            }

            .list-calender {
                margin: 2rem 0 0 0;
            }
        }   
    }

    @media (max-width: 1000px) {
        .grid-calender {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            padding: 2rem;
            overflow-x: hidden ;

            .calender {
                display: flex;
                justify-content: center;
            }
        }   
    }
</style>