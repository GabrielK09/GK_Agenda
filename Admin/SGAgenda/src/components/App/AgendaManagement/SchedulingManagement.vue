<template>
    <q-dialog v-model="showDetailScheduling" >
        <q-card>
            <q-card-section class="row items-center">
                <q-avatar icon="signal_wifi_off" color="primary" text-color="white" />
                <span class="q-ml-sm">You are currently not connected to any network.</span>

            </q-card-section>
            
            <q-card-actions align="right">
                <q-btn
                    flat
                    no-caps
                    color="primary"
                    label="Finalizar" 
                    @click="emits('close', true)"
                />
                
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { ref, onMounted } from 'vue';

    interface Service {
        serviceCode: number,
        description: string,
        name: string,
    };

    interface SchedulingDetails {
        schedulingCode: number,
        schedulingDuration: string,
        schedulingCustomerPhone: string,
        schedulingCustomer: string,
    };

    interface Scheduling {
        scheduling: SchedulingDetails,
        service: Service

    };

    const props = defineProps<{
        showDetailScheduling: boolean,
        schedulingCode: number

    }>();

    const emits = defineEmits([
        'close'
    ]);

    const scheduling = ref<Scheduling>({
        scheduling: {
            schedulingCode: 0,
            schedulingCustomer: '',
            schedulingCustomerPhone: '',
            schedulingDuration: ''
        },
        service: {
            serviceCode: 0,
            name: '',
            description: ''
        }
    });

    let showDetailScheduling = ref<boolean>(props.showDetailScheduling);

    const getSchedulingData = async () => {
        //const res = await api.get(``);
    };

    onMounted(() => {
        getSchedulingData();
    }); 
</script>