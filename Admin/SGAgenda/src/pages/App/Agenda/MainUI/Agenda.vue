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
                                :active-scheduling="activeSchedulings"
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
                                row-key="schedulingDate"
                            >
                                <template v-slot:body="props">
                                    <q-tr
                                        :props="props"
                                    >
                                        <q-td
                                            v-for="col in props.cols"
                                            @click="showDetailScheduling = !showDetailScheduling"
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

        <q-dialog v-model="showDetailScheduling" persistent>
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar icon="signal_wifi_off" color="primary" text-color="white" />
                    <span class="q-ml-sm">You are currently not connected to any network.</span>
                </q-card-section>
                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn flat label="Turn on Wifi" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>

<script setup lang="ts">
    import AgendaDay from '../Agendas/Day/AgendaDay.vue';
    import { onMounted, ref } from 'vue';
    import { QTableColumn } from 'quasar';
    import dayjs from 'dayjs';

    interface ShowAgenda {
        isInDay: boolean,
        isInWeek: boolean,
        isInMonth: boolean,
    };

    interface Service {
        serviceCode: number,
        name: string
    };

    interface Attendant {
        attendantCode: number,
        name: string
    };

    interface SchedulingData {
        schedulingCode: number,
        schedulingDuration: string,
        schedulingCustomerPhone: string,
        schedulingCustomer: string,
    };

    interface Scheduling {
        scheduling: SchedulingData,
        service: Service,
        attendant: Attendant,   
        schedulingHour: string,
        schedulingDate: string,
    };

    const showAgenda = ref<ShowAgenda>({
        isInDay: true,
        isInWeek: false,
        isInMonth: false
    }); 

    const activeSchedulings = ref<string[]>([]);

    const columns = ref<QTableColumn[]>([
        {
            field: 'schedulingDate',
            label: 'Data do agendamento',
            name: 'schedulingDate',
            align: 'center'
        },
        {
            field: 'schedulingHour',
            label: 'Horário',
            name: 'schedulingHour',
            align: 'center'
        },
        {
            field: 'scheduling',
            label: 'Cliente',
            name: 'scheduling',
            align: 'center',
            sortable: true,
            format(val) {
                const data: SchedulingData = val;
                return data.schedulingCustomer;
            }
        },
        {
            field: 'service',
            label: 'Serviço',
            name: 'service',
            align: 'center',
            sortable: true,
            format(val) {
                const data: Service = val;
                return data.name;
            }
        }
    ]);

    const allSchedulings = ref<Scheduling[]>([]);

    const schedulings = ref<Scheduling[]>([
        {
            attendant: {
                attendantCode: 1,
                name: 'Gabriel'
            },
            service: {
                serviceCode: 1,
                name: 'Corte de cabelo'
            },
            scheduling: {
                schedulingCode: 1,
                schedulingCustomer: 'Carlos',
                schedulingCustomerPhone: '(49) 99948-2859',
                schedulingDuration: '12'
            },
            schedulingHour: '12:00',
            schedulingDate: dayjs().add(1, 'day').format('DD/MM/YYYY')
        },
        {
            attendant: {
                attendantCode: 1,
                name: 'Gabriel'
            },
            service: {
                serviceCode: 1,
                name: 'Corte de cabelo'
            },
            scheduling: {
                schedulingCode: 1,
                schedulingCustomer: 'André',
                schedulingCustomerPhone: '(49) 99948-2859',
                schedulingDuration: '11'
            },
            schedulingHour: '12:00',
            schedulingDate: dayjs().format('DD/MM/YYYY')
        },
    ]);

    let formatedDate = ref<string>(dayjs().format('DD/MM/YYYY')); 
    let showDetailScheduling = ref<boolean>(false);

    const changeDateTable = (date: string) => {
        formatedDate.value = date;
        filter(date);
    };

    const filter = (date: string) => {
        schedulings.value = allSchedulings.value.filter(scheduling => scheduling.schedulingDate === date);
    };

    const getAllSchedulings = () => {

    };

    const convertStringDate = (stringDate: string): string => {
        let newString = '';
        const splitString: string[] = stringDate.split('/');
        const day = splitString[0];
        const month = splitString[1];
        const year = splitString[2];
        newString = `${year}/${month}/${day}`;

        return newString;
    };

    onMounted(() => {
        allSchedulings.value = [...schedulings.value];
        
        console.log(schedulings.value.map(scheduling => convertStringDate(scheduling.schedulingDate)));
        activeSchedulings.value = schedulings.value.map(scheduling => convertStringDate(scheduling.schedulingDate));

        filter(dayjs().format('DD/MM/YYYY'));
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
                margin: 5rem 0 0 0;
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