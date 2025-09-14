<template>
    <div class="flex justify-center mt-24">
        <div class="w-[70vh]">
            <div class="border">
                <q-btn
                    no-caps
                    outline
                    icon="arrow_back"
                    :to="`/app.gkagenda/${urlName}`"
                    class="m-4"
                />
            </div>
        
            <div class="mt-4 bg-gray-500 rounded-md p-6">
                <div class="text-white"> 
                    <div class="">
                        <span>Serviço: {{ service.name }}</span>
                        <br>
                        <span>{{ service.description }}</span>
                    </div>
                
                    <div class="mt-4">
                        <span class="bg-gray-800 rounded-md p-2 mr-4">R$ {{ service.price.toFixed(2).toString().replace('.', ',') }}</span> 
                        <span class="bg-gray-800 rounded-md p-2">
                            <q-avatar icon="schedule" />{{ service.duration }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-xl flex justify-center">
                <div v-for="attendant in attendants">
                    <div class="text-center">
                        <div 
                            class="bg-gray-600 text-white m-4 shadow-md rounded-md w-max p-2 cursor-pointer transition-transform hover:-translate-y-2"
                            @click="markAttendant(attendant.attendantCode)"
                        >
                            <q-avatar size="125px" icon="person" />
                            <div class="card">
                                {{ attendant.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div v-if="releaseHours">
                    <span>Ta liberado as hora</span>
                    {{ selectedAttendantCode }}
                </div>
            </div>    
        </div>
    </div>
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';
    import { useRoute } from 'vue-router';

    interface Service {
        serviceCode: number,
        name: string,
        description: string,
        price: number,
        duration: string
    };

    interface Attendant {
        attendantCode: number,
        name: string,
    };

    const route = useRoute();
    const codeParam = route.params; // :scheduleCode
    const urlName = LocalStorage.getItem("urlName");

    const attendants = ref<Attendant[]>([]);

    const service = ref<Service>({
        serviceCode: 0,
        name: '',
        description: '',
        price: 0,
        duration: ''

    });

    let releaseHours = ref<boolean>(false);
    let selectedAttendantCode = ref<number>(0);

    const getServiceData = async () => {
        const res = await api.get(`/site/find/${urlName}/${codeParam.scheduleCode}`);
        const data: Service = camelcaseKeys(res.data.data, { deep: true });

        service.value = {
            serviceCode: data.serviceCode,
            name: data.name,
            description: data.description,
            price: data.price,
            duration: data.duration

        };
    };

    const getAttendantData = async () => {
        const res = await api.get(`/site/get-attendants/${urlName}`);
        const data = camelcaseKeys(res.data.data, { deep: true });

        attendants.value = data;
    };

    const markAttendant = (attendantCode: number) => {
        const attendant: Attendant[] = attendants.value.filter(attendant => { 
            if(attendant.attendantCode === attendantCode)
            {
                selectedAttendantCode.value = attendant.attendantCode;
                return attendant;  
            };
        }); 

        if(attendant) 
        {
            releaseHours.value = true;
        };

        if(!attendant)
        {
            releaseHours.value = false;
            selectedAttendantCode.value = 0;
            return;
        };
    };

    const getHours = async (attendantCode: number) => {

    };

    onMounted(() => {
        getAttendantData();
        getServiceData();
    });
</script>