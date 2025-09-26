<template>
    <q-page padding>
        <div class="cards items-center text-2xl">
            <div class="cards-1 flex gap-14 max-w-[100%]">
                <div class="card-1">
                    <div class="bg-blue-400 text-white rounded-md p-8 w-96">
                        <span>Totais de atendimentos:</span>
                        
                        <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">{{ countTotalSchedulings }}</span></span>
                    </div>
                </div>

                <div class="card-2">
                    <div class="bg-blue-500 text-white rounded-md p-8 w-96">
                        <span>Faturamento total:</span>
                            
                        <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">{{ calculateTotalInvoicing }}</span></span>
                    </div>
                </div>

                <div class="card-3">
                    <div class="bg-blue-600 text-white rounded-md p-8 w-96">
                        <span>Média:</span>
                        
                        <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">{{ calculateAvgInvoicing }}</span></span>
                    </div>
                </div>
            </div>

            <div class="cards-2 mt-4 flex gap-14 max-h-96">
                <div class="card-1">
                    <div class="text-black shadow-lg rounded-md p-8 w-96 h-96">
                        
                    </div>

                </div>

                <div class="card-2">
                    <div class="text-black shadow-lg rounded-md p-8 w-96 h-96">
                        <div class="registers h-80 overflow-y-auto">
                            <div v-for="schedule in schedules">
                                <div class="flex">
                                    <div class="bg-green-500 w-1 h-6 mt-4 mr-3"></div>
                                    <div class="inline-flex flex-col mb-3">
                                        <span class="mt-3">Cliente: {{ schedule.customerName }}</span>
                                        <span class="mt-3">Telefone: {{ schedule.customerPhone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-3">
                    <div class="text-black shadow-lg rounded-md p-8 w-96 h-96">

                    </div>

                </div>
            </div>
        </div>
    </q-page>
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { computed, onMounted, ref } from 'vue';

    interface ScheduleDetails {
        customerName: string,
        customerPhone: string,
        day: string,
        service: string,
        servicePrice: number,
        completed?: number,
        canceled?: number,

    };

    const ownerCode = LocalStorage.getItem("ownerCode");
    const schedules = ref<ScheduleDetails[]>([]);
    let countSchedules = ref<number>(0);

    const countTotalSchedulings = computed(() => {
        return schedules.value.length; 

    });

    const calculateTotalInvoicing = computed(() => {
        let total = 0;

        schedules.value.forEach(schedule => {
            if(schedule.canceled !== 1 || schedule.completed !== 0) total += schedule.servicePrice
            
        });

        return `R$ ${total.toFixed(2).toString().replace('.', ',')}`;
    });

    const calculateAvgInvoicing = computed(() => {
        let subtotal = 0;
        
        schedules.value.forEach(schedule => {
            if(schedule.canceled !== 1 || schedule.completed !== 0) subtotal += schedule.servicePrice
            
        });

        return `R$ ${(subtotal / schedules.value.length > 0 ? schedules.value.length : 0).toFixed(2).toString().replace('.', ',')}`;
    });

    const getAllSchedulings = async () => {
        const res = await api.get(`/schedule/get-all/${ownerCode}`);
        const data: ScheduleDetails[] = camelcaseKeys(res.data.data, { deep: true });
        countSchedules.value = data.length;
        schedules.value = data;

    };

    const groupByService = async () => {
        const res = await api.get(`/dashboard/dashboard/get-group-services/${ownerCode}`); 
        console.log(res.data);
        
    };

    onMounted(() => {
        getAllSchedulings();
        groupByService();
    });
    
</script>


<style lang="scss">
    .cards { 
        margin: 0 auto 0 auto;
    }

    @media (max-width: 1540px) {
        .cards {
            width: 65%;

            .cards-1, .cards-2 {
                display: flex;
                justify-content: center;

            }
        }
    }
    
</style>