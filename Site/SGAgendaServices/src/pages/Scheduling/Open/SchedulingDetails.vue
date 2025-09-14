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
                    <div v-for="day in daysByMarked">
                        <div 
                            class="p-4 text-center border cursor-pointer"
                            @click="selectedDay(day.day.toString(), day.dayWeek, day.attendantCode)"
                        >    
                            {{ formatDay(day.day.toString()) + `/0${month + 1}` }} - {{ replaceDayLabel(day.dayWeek.replace('.', '')) }}
                        </div>
                    </div>
                </div>
            </div>    

            <div class="">
                <div v-if="releaseMarkHours">
                    <div v-for="hour in hoursAttendants">
                        {{ hour }}
                    </div>

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

    interface DayWeek {
        day: number,
        attendantCode: number,
        dayWeek: string
    };

    type AttendantHour = {
        day: "Seg"|"Ter"|"Qua"|"Qui"|"Sex"|"Sáb"|"Dom";
        markedDay: number;
        
    };

    const daysByMarked = ref<DayWeek[]>([]);
    const month = new Date().getMonth();

    const dowIndex: Record<AttendantHour['day'], number> = {
        Dom: 0, Seg: 1, Ter: 2, Qua: 3, Qui: 4, Sex: 5, Sáb: 6
    };

    const route = useRoute();
    const codeParam = route.params; // :scheduleCode
    const urlName = LocalStorage.getItem("urlName");

    const attendants = ref<Attendant[]>([]);
    const hoursAttendants = ref([]);
    const allHoursAttendants = ref([]);
    
    const service = ref<Service>({
        serviceCode: 0,
        name: '',
        description: '',
        price: 0,
        duration: ''

    });

    let releaseHours = ref<boolean>(false);
    let releaseMarkHours = ref<boolean>(false);
    let selectedAttendantCode = ref<number>(0);
    
    function replaceDayLabel(day: string): string
    {
        let newDay = '';
        switch (day.toLowerCase()) {
            case 'dom':
                newDay = 'Domingo';
                break;
        
            case 'seg':
                newDay = 'Segunda';
                break;

            case 'ter':
                newDay = 'Terça';
                break;

            case 'qua':
                newDay = 'Quarta';
                break;

            case 'qui':
                newDay = 'Quinta';
                break;

            case 'sex':
                newDay = 'Sexta';
                break;

            case 'sáb':
                newDay = 'Sábado';
                break;

            default:
                break;
        }

        return newDay;        
    };

    function formatDay(val: string): string
    {   
        let newVal: string = '';

        if(Number(val) === 0)
        {
            newVal = '';
        } else if(val.length === 1)
        {   
            newVal = `0${val}`;
        } else {
            return val;
        };

        return newVal;        
    };

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

        getHours(attendantCode);
    };

    const getHours = async (attendantCode: number) => {
        const res = await api.get(`/site/get-attendants/hours/${urlName}/${attendantCode}`)
        const data = camelcaseKeys(res.data.data, { deep: true });
        
        hoursAttendants.value = data;
        const year = new Date().getFullYear();
        //ia
        const days: DayWeek[] = [];

        for (let day = 1; day <= 30; day++) {
            const date = new Date(year, month - 1, day)

            const dayWeek = date.toLocaleDateString("pt-BR", { weekday: "short" });

            days.push({ day, attendantCode, dayWeek});
        };
        //

        daysByMarked.value = filterHours(data, attendantCode, year, month + 1);
        console.log(filterHours(data, attendantCode, year, month + 1));
    };

    const filterHours = (attendantHours: AttendantHour[], attendantCode: number, year: number, month: number): { day: number, attendantCode: number, dayWeek: string }[] => {
        const marked = attendantHours
            .filter(h => h.markedDay === 1)
            .map(h => dowIndex[h.day]);

        const result: {  day: number, attendantCode: number, dayWeek: string }[] = [];
        const lastDay = new Date(year, month, 0).getDate();

        for (let d = 1; d <= lastDay; d++) {
            const date = new Date(year, month - 1, d);
            const dow = date.getDay();

            if(marked.includes(dow)) {
                result.push({
                    day: d,
                    attendantCode,
                    dayWeek: date.toLocaleDateString("pt-BR", { weekday: "short" })
                })

            };
        };

        return result;
    };

    const selectedDay = async (day: string, dayWeek: string, attendantCode: number) => {
        const res = await api.get(`/site/get-attendants/hours/${urlName}/${attendantCode}`)
        const data = res.data.data;
        
        allHoursAttendants.value = [...hoursAttendants.value];        
        
        releaseMarkHours.value = true;
        console.log('Dia selecionado: ', day, ' no: ', dayWeek);
        console.log('hoursAttendants.value: ', hoursAttendants.value);

        hoursAttendants.value = allHoursAttendants.value.filter((h: any) => {
            return h.markedDay === 1 && h.day.toLowerCase() === dayWeek.toLowerCase().replace('.', '')
        });

        console.log('hoursAttendants.value: ', hoursAttendants.value);
    };

    onMounted(() => {
        getAttendantData();
        getServiceData();
    });
</script>