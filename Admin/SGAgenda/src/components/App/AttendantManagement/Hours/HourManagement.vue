<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="bg-white rounded-lg">
                    <h2 class="text-gray-600 ml-4">Horários do(a) atendente: {{ props.attendantName }}</h2>
                    <div class=" p-4 text-xs">
                        <div 
                            @click="emits('close', true)"
                            class="flex mb-auto mt-auto cursor-pointer"
                        
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>      
                            <span>
                                Voltar para listage
                            </span>
                        </div>
                    </div>
                </div>

                <q-form
                    @submit="saveHours"
                >   
                    <div class="mt-4 bg-white p-8 grid grid-cols-3 w-max">                 
                        <div v-for="hourLabel in hoursLabel">
                            <div class="border p-4 m-4">
                                <div class="flex mb-2 border-t border-b p-2">
                                    <q-toggle v-model="hourLabel.markedDay" /><span class="mt-auto mb-auto mr-4 text-sm">{{ hourLabel.label }}</span>
                                    <q-input 
                                        hide-bottom-space
                                        outlined
                                        v-model="hourLabel.start" 
                                        mask="time" 
                                        class="w-20 mr-2"
                                        :rules="hourLabel.markedDay ? ['time', requiredField] : []" 
                                        stack-label
                                        label=""
                                    >
                                        <template #label>
                                            <span>Início</span>
                                        </template>
                                    </q-input>

                                    <q-input 
                                        hide-bottom-space
                                        outlined
                                        v-model="hourLabel.end" 
                                        mask="time" 
                                        :rules="hourLabel.markedDay ? ['time', requiredField] : []" 
                                        stack-label
                                        class="w-20"
                                        label=""
                                    >
                                        <template #label>
                                            <span>Fim</span>
                                        </template>
                                    </q-input>
                                </div>
                                
                                <div class="flex mb-2 border-t border-b p-2">
                                    <span class="mt-auto mb-auto mr-5 text-sm">Intervalos</span>
                                    <q-input 
                                        hide-bottom-space
                                        outlined
                                        v-model="hourLabel.interval" 
                                        mask="time" 
                                        class="ml-auto"
                                        :rules="hourLabel.markedDay ? ['time', requiredField] : []" 
                                        label=""
                                    >
                                        <template #label>
                                            <span>Intervalo</span>
                                        </template>
                                    </q-input>
                                </div>
                                <div class="flex mb-2 border-t border-b p-2 text-sm">
                                    <span class="mt-auto mb-auto">Intervalos entre atendimento</span>
                                    
                                    <q-select 
                                        v-model="hourLabel.intervalBetweenServices" 
                                        :options="intervalBetweenServicesOptions" 
                                        :rules="hourLabel.markedDay ? [requiredField] : []"
                                        label=""
                                        :disable="hourLabel.markedDay ? false : true"
                                        class="w-28 ml-auto"
                                        outlined
                                        
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 bg-white w-max rounded-md">
                        <q-btn  
                            no-caps
                            label="Gravar horários"
                            flat
                            type="submit"
                        />

                    </div>
                </q-form>
                
                <pre>{{ hoursLabel }}</pre>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, reactive, ref } from 'vue';

    interface HoursLabel {
        day: string,
        markedDay: boolean,
        label: string,
        start: string,
        end: string,
        interval: string,
        intervalBetweenServices: string
    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;

    const intervalBetweenServicesOptions = ref<string[]>([
        '5 minutos',
        '10 minutos',
        '15 minutos',
        '20 minutos',
        '25 minutos',
        '30 minutos',
    ]);

    let hoursLabel = reactive<HoursLabel[]>([
        {
            day: 'Dom',
            markedDay: false,
            label: 'Domingo',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
        {
            day: 'Seg',
            markedDay: false,
            label: 'Segunda',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
        {
            day: 'Ter',
            markedDay: false,
            label: 'Terça',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
        {
            day: 'Qua',
            markedDay: false,
            label: 'Quarta',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
        {
            day: 'Qui',
            markedDay: false,
            label: 'Quinta',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
        {
            day: 'Sex',
            markedDay: false,
            label: 'Sexta',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
        {
            day: 'Sáb',
            markedDay: false,
            label: 'Sábado',
            start: '',
            end: '',
            interval: '',
            intervalBetweenServices: ''
        },
    ]);

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        attendantName: string,
        attendantCode: number
        
    }>();

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

            case 'sab':
                newDay = 'Sabádo';
                break;

            default:
                break;
        }

        return newDay;        
    };

    function requiredField(val: string): boolean|string {
        if(val === '') return 'Esse campo é necessário!';
        return true;
    }

    const saveHours = async () => {
        const payload = {
            ownerCode: ownerCode,
            attendantCode: props.attendantCode,
            hours: hoursLabel

        };

        const res = await api.post('/attendants/create/hours', payload, {
            headers: {
                Accept: 'application/json'
            }
        });
        
        const data = res.data;
        if(data.success)
        {
            $q.notify({
                color: 'green',
                message: 'Horários alterados com sucesso!',
                position: 'top'
            });
            emits('close', true);
        };
    };

    const getHoursData = async () => {
        //ia
        const res = await api.get(`/attendants/get/hours/${ownerCode}/${props.attendantCode}`);
        const data: any[] = camelcaseKeys(res.data.data, { deep: true });
        
        const byDay = new Map<string, any>(data.map(r => [r.day, r]));
        
        hoursLabel.forEach((h) => {
            const src = byDay.get(h.day);

            if(src) {
                Object.assign(h, {
                    day: src.day,
                    interval: src.interval ?? '',
                    intervalBetweenServices: src.intervalBetweenServices ?? '',
                    label: replaceDayLabel(src.day),
                    markedDay: !!Number(src.markedDay),
                    start: src.start ?? '',
                    end: src.end ?? '',
                })
            } else {
                Object.assign(h, {
                    interval: '',
                    intervalBetweenServices: '',
                    markedDay: false,
                    start: '',
                    end: ''
                });
            };
        }); 
    };      

    onMounted(() => {
        getHoursData();
    });
</script>
