<template>
    <q-dialog v-model="showDetailScheduling" persistent>
        <q-card class="p-12">
            <q-card-section>
                <h3 class="text-xl">Dados do cliente</h3>
                <div class="">
                    <span class="text-base">Nome: {{  scheduling?.customerName }} - Telefone {{ scheduling?.customerPhone }}</span> 

                </div>
            </q-card-section>
            
            <q-card-section>
                <div class="">
                    <h3 class="text-xl">Dados do seviço</h3>
                    <span class="text-base">Serviço: {{ scheduling?.service  }} - </span> 
                    <span class="text-base">R$: {{ scheduling?.servicePrice.toFixed(2).toString().replace('.', ',')  }}</span> 
                </div>
            </q-card-section>
            
            <q-card-actions align="right">
                <q-btn
                    flat
                    no-caps
                    color="primary"
                    label="Finalizar" 
                    @click="callQRCode"
                />
                
            </q-card-actions>
        </q-card>
    </q-dialog>

    <QRCode
        v-if="showQRCode"
        :total-amount="scheduling?.servicePrice"
        @finaly="finishScheduleMethod"
    />
    
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import QRCode from 'src/components/PixQRCode/QRCode.vue';
    import { ref, onMounted } from 'vue';
    
    interface Scheduling {
        customerName: string,
        customerPhone: string,
        serviceCode: number,
        service: string,
        servicePrice: number,
        attendantCode: number,
        attendant: string
        hour: string,
        day: string,
        month: string,
    };

    const $q = useQuasar();

    const props = defineProps<{
        showDetailScheduling: boolean,
        schedulingCode: number

    }>();

    const emits = defineEmits([
        'close'
    ]);

    const ownerCode = LocalStorage.getItem("ownerCode");
    const scheduling = ref<Scheduling>();

    let showDetailScheduling = ref<boolean>(props.showDetailScheduling);
    let showQRCode = ref<boolean>(false);

    const getSchedulingData = async () => {
        const res = await api.get(`/schedule/get-detail/${ownerCode}/${props.schedulingCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });
        
        scheduling.value = data;
        
    };

    const callQRCode = () => {
        showQRCode.value = true;
        showDetailScheduling.value = false;
    };

    const finishScheduleMethod = async () => {
        /*'ownerCode'
        'scheduleCode'
        'amoutPaid'
        'serviceCode'*/

        const payload = {
            ownerCode: ownerCode,
            attendantCode: scheduling.value?.attendantCode,
            scheduleCode: props.schedulingCode,
            amoutPaid: scheduling.value?.servicePrice,
            serviceCode: scheduling.value?.serviceCode,
        };  

        console.log(payload);

        try {
            const res = await api.put('/schedule/finish/schedule', payload);
            const data = res.data;

        
            if(data.success)
            {
                $q.notify({
                    color: 'green',
                    message: data.message,
                    position: 'top',
                    timeout: 1000

                });

                emits('close', true);
            };
        } catch (error: any) {
            $q.notify({
                color: 'red',
                message: error?.response?.data?.message,
                position: 'top',
                timeout: 1000
            });
        };
    };

    onMounted(() => {
        getSchedulingData();
    }); 
</script>