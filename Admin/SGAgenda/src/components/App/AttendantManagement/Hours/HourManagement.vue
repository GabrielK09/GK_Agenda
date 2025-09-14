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
                                Voltar para listagem
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 bg-white p-12 grid grid-cols-3 w-max">
                    <div v-for="hourLabel in hoursLabel">
                        <div class="border p-4 flex m-4">
                            <q-toggle v-model="hourLabel.markedDay" /><span class="mt-auto mb-auto mr-4">{{ hourLabel.label }}</span>
                            <q-input 
                                hide-bottom-space
                                outlined
                                v-model="hourLabel.time" 
                                mask="time" 
                                :rules="['time']" 
                            />
                        </div>    
                    </div>
                </div>
                
                <div class="mt-2 bg-white w-max rounded-md">
                    <q-btn  
                        no-caps
                        label="Gravar horários"
                        flat 
                        @click="saveHours" 
                    />

                </div>

                <pre>{{ hoursLabel }}</pre>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
import { api } from 'src/boot/axios';
    import { reactive } from 'vue';

    const hoursLabel = reactive([
        {
            day: 'Dom',
            markedDay: false,
            label: 'Domingo',
            time: ''
        },
        {
            day: 'Seg',
            markedDay: false,
            label: 'Segunda',
            time: ''
        },
        {
            day: 'Ter',
            markedDay: false,
            label: 'Terça',
            time: ''
        },
        {
            day: 'Qua',
            markedDay: false,
            label: 'Quarta',
            time: ''
        },
        {
            day: 'Qui',
            markedDay: false,
            label: 'Quinta',
            time: ''
        },
        {
            day: 'Sex',
            markedDay: false,
            label: 'Sexta',
            time: ''
        },
        {
            day: 'Sab',
            markedDay: false,
            label: 'Sábado',
            time: ''
        },
    ]);

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        attendantName: string,
        attendantCode: number
        
    }>();

    const saveHours = async () => {
        const payload = {
            ownerCode: LocalStorage.getItem("ownerCode"),
            attendatCode: props.attendantCode,
            hours: hoursLabel

        };

        const res = await api.post('/attendants/create/hours', payload, {
            headers: {
                Accept: 'application/json'
            }
        });
        
        const data = res.data;

        console.log(data);
    };
</script>
