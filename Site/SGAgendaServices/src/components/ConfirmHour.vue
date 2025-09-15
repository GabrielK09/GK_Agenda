<template>
    <q-dialog v-model="show" persistent>
        <q-card>
            <q-card-section class="row items-center">
                <q-form
                    @submit="confirmHour"
                    class="q-gutter-md"
                >
                    <div class="">
                        <span class="q-ml-sm">Confirmar agendamento para as {{ props.hour }} dia: {{ props.date }} em {{ props.month + 1 }} | {{ props.attendantCode }} | {{ props.serviceCode }}</span>

                        <q-input 
                            v-model="confirmData.customerName" 
                            type="text" 
                            label="Seu nome" 
                            outlined
                            class="ml-4 mt-4"
                            stack-label
                            :rules="[requiredField]"
                        />

                        <q-input 
                            v-model="confirmData.customerPhone" 
                            type="text" 
                            label="Seu telefone" 
                            mask="(##) #####-####"
                            outlined
                            class="ml-4 mt-4"
                            stack-label
                            :rules="[requiredField]"
                        />
                    </div>

                    <div class="flex justify-end">
                        <q-btn 
                            no-caps 
                            label="Confirmar" 
                            type="submit" 
                            color="primary" 
                            
                        />
                        
                    </div>
                </q-form>
            </q-card-section>
            <q-card-actions align="right">
                <q-btn no-caps flat label="Não" color="primary" @click="closePoppup"/>
                
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { ref } from 'vue';

    interface Confirm {
        siteName: string,
        serviceCode: number,
        attendantCode: number,
        customerName: string,
        customerPhone: string,        
        date: string,
        hour: string,
        month: string,
    };

    const confirmData = ref<Confirm>({
        siteName: '',
        customerName: '',
        customerPhone: '',
        serviceCode: 0,
        attendantCode: 0,
        date: '',
        hour: '',
        month: '',

    });

    const emits = defineEmits([
        'close',
        'position'
    ]);

    const props = defineProps<{
        position: number,
        serviceCode: unknown,
        attendantCode: unknown,
        date: string,
        hour: string,
        month: string,
        
    }>();

    const show = ref<boolean>(true);

    const closePoppup = () => {
        emits('close', true);
        emits('position', props.position)
    };

    function requiredField(val: string) {
        if(!val) return 'Esse campo é necessário';
        return true;
    }

    const confirmHour = async () => {
        const newAttendantCode = Number(props.attendantCode);
        const newServiceCode = Number(props.serviceCode);

        const payload: Confirm = {
            attendantCode: newAttendantCode,
            date: props.date,
            hour: props.hour,
            customerName: confirmData.value.customerName,
            customerPhone: confirmData.value.customerPhone,
            serviceCode: newServiceCode,
            siteName: LocalStorage.getItem("urlName") as string,
            month: props.month
        };

        console.log(payload);
    
        const res = await api.post('/site/create/schedule', payload, {
            headers: {
                Accept: 'application/json'
            }
        });

        console.log(res.data);
        
    }; 
</script>