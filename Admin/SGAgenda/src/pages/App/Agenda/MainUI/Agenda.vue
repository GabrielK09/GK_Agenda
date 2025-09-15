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
                                            @click="openSchedulingDetails"
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
    import dayjs from 'dayjs';
    import { api } from 'src/boot/axios';
    import camelcaseKeys from 'camelcase-keys';

   interface Scheduling {
        customerName: string,
        customerPhone: string,
        serviceCode: number,
        name: string
        attendantCode: number,
        name2: string
        hour: string,
        day: string,
        month: string,
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
            sortable: true,
            format(val) {
                return val.customerName;
            }
        },
        {
            field: 'service',
            label: 'Serviço',
            name: 'service',
            align: 'center',
            sortable: true,
            format(val) {
                return val.name;
            }
        }
    ]);

    const allSchedulings = ref<Scheduling[]>([]);

    const schedulings = ref<Scheduling[]>([]);
    const ownerCode = LocalStorage.getItem("ownerCode");

    let formatedDate = ref<string>(dayjs().format('DD/MM/YYYY')); 
    let showDetailScheduling = ref<boolean>(false);
    let selectedSchedulingCode = ref<number>(0);

    const changeDateTable = (date: string) => {
        formatedDate.value = date;
        filter(date);
    };

    const filter = (date: string) => {
        schedulings.value = allSchedulings.value.filter(scheduling => scheduling.day === date);
    };

    const getAllSchedulings = async () => {
        const res = await api.get(`/schedule/get-all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });
        schedulings.value = data;

        console.log(schedulings.value);

        const date = schedulings.value.map(scheduling => scheduling.day);
        const month: unknown[] = schedulings.value.map(scheduling => scheduling.month);
        const newMonth = Number(month[0]) + 1;
        console.log('Data: ', date[0]);
        console.log('newMonth: ', newMonth);
        
        const newDate = convertStringDate(date[0] as string, newMonth);
        /*'scheduling_code',
        'owner_code',
        'attendant_code',
        'attendant',
        'service_code',
        'service',
        'customer_name',
        'customer_phone',
        'day',
        'hour', */
        console.log(newDate);
        activeSchedulings.value.push(newDate);
    };

    const convertStringDate = (stringDate: string, monthStr: number): string => {
        let newString = '';
        console.log(monthStr);
        
        const splitString: string[] = stringDate.split('/');
        const day = splitString[0].length === 1 ? '0' + splitString[0] : splitString[0];
        const month = monthStr.toString().length === 1 ? '0' + monthStr : monthStr;
        const year = new Date().getFullYear();

        newString = `${year}/${month}/${day}`;

        return newString;
    };

    const openSchedulingDetails = () => {
        console.log('openSchedulingDetails');
        
        showDetailScheduling.value = !showDetailScheduling.value;

    };

    onMounted(() => {
        // allSchedulings.value = [...schedulings.value];
        
        // console.log(schedulings.value.map(scheduling => convertStringDate(scheduling.schedulingDate)));
        // activeSchedulings.value = schedulings.value.map(scheduling => convertStringDate(scheduling.schedulingDate));

        // filter(dayjs().format('DD/MM/YYYY'));
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