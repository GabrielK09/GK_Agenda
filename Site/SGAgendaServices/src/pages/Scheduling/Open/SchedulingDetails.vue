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
                    <div v-if="daysByMarked.length <= 0">
                        <span>Sem horários para este atendete</span>
                    </div>
                </div>
            </div>    

            <div class="">
                <div v-if="releaseMarkHours">
                    <div v-if="releaseMarkHours" class="mt-6">
                        <!--Ia-->
                        <div class="grid grid-cols-4 gap-2">
                            <q-btn
                                v-for="(s, i) in slots"
                                :key="s.time"
                                class="px-3 py-2 rounded-md border text-sm hover:-translate-y-1 transition-transform"
                                :class="[
                                    s.selected ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700',
                                    s.disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                ]"
                                    :disabled="s.disabled"
                                    @click="showConfirmHour(i)"
                                    title="Selecionar horário"
                                >
                                    {{ s.time }}
                            </q-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ConfirmHour
        v-if="shoowConfirmHour"
        @close="closeAndUncheck($event)"
        @position="removePosition($event)"
        :position="positionMarked"
        :hour="selectedHour"
        :service-code="codeParam.scheduleCode"
        :attendant-code="LocalStorage.getItem('attendantCode')"
        :date="selectedDate"
        :month="selectedMonth"

    />
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import ConfirmHour from 'src/components/ConfirmHour.vue';
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
        month: number,
        attendantCode: number,
        dayWeek: string
    };

    type AttendantHour = {
        day: "Seg"|"Ter"|"Qua"|"Qui"|"Sex"|"Sáb"|"Dom";
        markedDay: number;
        
    };

    type Slot = {
        time: string;
        selected?: boolean,
        disabled?: boolean
    };

    type Availability = {
        day: string,
        hour: string,
        month: string
    };

    const slots = ref<Slot[]>([]);

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

    let shoowConfirmHour = ref<boolean>(false);
    let positionMarked = ref<number>(0);
    let selectedMonth = ref<number>(0);
    let selectedHour = ref<string>('');
    let selectedDate = ref<string>('');

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
        LocalStorage.set("attendantCode", attendantCode);
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

            days.push({ day, attendantCode, dayWeek, month});
        };
        //

        daysByMarked.value = filterHours(data, attendantCode, year, month + 1);
        console.log(filterHours(data, attendantCode, year, month + 1));
    };

    const filterHours = (attendantHours: AttendantHour[], attendantCode: number, year: number, month: number): { day: number, attendantCode: number, dayWeek: string, month: number }[] => {
        const marked = attendantHours
            .filter(h => h.markedDay === 1)
            .map(h => dowIndex[h.day]);

        const lastDay = new Date(year, month, 0).getDate();
        const today = new Date();
        const isCurrentMonth = year === today.getFullYear() && (month - 1) === today.getMonth();
        const startDay = isCurrentMonth ? today.getDate() : 1;

        const result: { day: number; attendantCode: number; dayWeek: string, month: number }[] = [];

        for (let d = startDay; d <= lastDay; d++) {
            const date = new Date(year, month - 1, d);
            const dow = date.getDay();
   
            if(marked.includes(dow)) {
                selectedMonth.value = date.getMonth() + 1;
                result.push({
                    day: d,
                    month: date.getMonth() + 1,
                    attendantCode,
                    dayWeek: date.toLocaleDateString("pt-BR", { weekday: "short" }),
                    
                });
            };
        };

        return result;
    };

    const selectedDay = async (day: string, dayWeek: string, attendantCode: number) => {
        selectedDate.value = day;
        console.log('Chamaou o selectedDay, day: ', day, ' dia da semana: ', dayWeek);
        
        releaseMarkHours.value = true;
        const res = await api.get(`/site/get-attendants/hours/${urlName}/${attendantCode}`)
        const data = camelcaseKeys(res.data.data, { deep: true });
        
        allHoursAttendants.value = data.filter((d: any) => d.markedDay && d.day.toLowerCase() === dayWeek.replace('.', ''));
        
        const startStr = allHoursAttendants.value.map((d: any) => d.start );
        const endStr = allHoursAttendants.value.map((d: any )=> d.end );
        const intervalBetweenServicesStr = allHoursAttendants.value.map((d: any )=> d.intervalBetweenServices );

        const interval = setIntervalByStr(intervalBetweenServicesStr[0])

        slots.value = buildSlots(startStr[0], endStr[0], interval);
    };

    const setIntervalByStr = (intervalStr: string): number => {
        let interval = 0;
        switch (intervalStr) {
            case '5 minutos':
                interval = 5;
                break;

            case '10 minutos':
                interval = 10;
                break;

            case '15 minutos':
                interval = 15;
                break;
            case '20 minutos':
                interval = 20;
                break;
            case '25 minutos':
                interval = 25;
                break;

            case '30 minutos':
                interval = 30;
                break;
        
            default:
                break;
        }
        return interval;

    };

    //const checkAvailability = async (out: Slot[]): Promise<Slot[]> => {
    const checkAvailability = async (out: Slot[]): Promise<Slot[]> => {
        /*
            let selectedMonth = ref<number>(0);
            let selectedDate = ref<string>(''); 
        */

        let newOut: Slot[] = [];
        const payload = {
            dateByFilter: `${selectedDate.value}/${selectedMonth.value.toString().length === 1 ? `0${selectedMonth.value}` : selectedMonth.value}/${new Date().getFullYear()}`
        }
        
        const res = await api.post(`/site/get-all/schedule/${urlName}`, payload);

        const data: Availability[] = camelcaseKeys(res.data.data, { deep: true });

        for (let i = 0; i < out.length; i++) {
            const slot = out[i] as Slot;

            for (let j = 0; j < data.length; j++) {
                const day = data[j];
        
                // time = hour
                if(slot?.time === day?.hour)
                {
                    continue  
                } else {
                    newOut.push(slot);
                };  
            };
        };

        return newOut;
    };

    // ia
    function buildSlots(startStr: string, endStr: string, stepMin: number): Slot[] {
        const start = toMinutes(startStr);
        const end   = toMinutes(endStr);

        if (!stepMin || stepMin <= 0 || end <= start) return [];

        const out: Slot[] = [];
        
        for (let t = start; t < end; t += stepMin) {
            out.push({ time: fromMinutes(t), selected: false, disabled: false });

        }

        checkAvailability(out);

        return out;
    };

    function toMinutes(hhmm: string): number {
        const [h, m] = (hhmm ?? '00:00').split(':').map(v => parseInt(v, 10) || 0);
        return Number(h) * 60 + Number(m);

    };

    function fromMinutes(min: number): string {
        const h = Math.floor(min / 60);
        const m = min % 60;
        const hh = h.toString().padStart(2, '0');
        const mm = m.toString().padStart(2, '0');
        return `${hh}:${mm}`;
    };
    //

    const showConfirmHour = (position: number) => {
        const slot = slots.value[position];

        if(!slot) return;
        if(slot) {
            slot.selected = true;
            shoowConfirmHour.value = true;
            positionMarked.value = position;
            selectedHour.value = slot.time;

        };
    };

    const closeAndUncheck = (show: boolean) => {
        shoowConfirmHour.value = !show;
    };  

    const removePosition = (position: number) => {
        const slot = slots.value[position];

        if(!slot) return;
        if(slot) slot.selected = false;
    };  

    onMounted(() => {
        getAttendantData();
        getServiceData();
    });
</script>