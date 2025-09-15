<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">                    
                    <h2 class="text-gray-600 m-2">Cadastrar serviço</h2>
                </div>

                <div class="bg-white p-8 text-xs">
                    <div class="flex justify-between">
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

                        <div>
                            <q-checkbox left-label v-model="service.isHomeService" label="Serviço a domicílio" />
                            <q-checkbox left-label v-model="service.checkAvailability" label="Checar horários" />
                        </div>
                    </div>

                    <q-form
                        @submit="createService"
                        class="q-gutter-md mt-4 rounded-xl"
                    >
                        <div class="">
                            <q-input 
                                label="Nome do serviço" 
                                v-model="service.name" 
                                stack-label
                                outlined
                                type="text" 
                                
                                :rules="[
                                    val => !!val || 'O nome do serviço é necessário!'
                                ]"
                            />
                        </div>
                        <div class="grid grid-cols-3">
                            <q-input 
                                v-model="service.duration" 
                                type="number" 
                                input-class="text-right"
                                label="" 
                                stack-label
                                outlined
                                class="mr-6"
                                lazy-rules
                                :rules="[validateDurationField]"
                            >
                                <template v-slot:label>
                                    <div class="mt-2">
                                        <span>Duração em minutos</span>
                                        <q-avatar size="35px" icon="alarm" />
                                    </div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="service.price" 
                                type="text"
                                input-class="text-right"
                                label="Preço do serviço" 
                                stack-label
                                outlined
                                mask="##,##"
                                reverse-fill-mask
                                class="mr-6"
                                :rules="[]"    
                            >
                                <template v-slot:label>
                                    <div class="mt-2">
                                        <span>Valor do serviço</span>
                                        <q-avatar size="35px" icon="payments" />
                                    </div>
                                </template>
                            </q-input>

                            <q-select 
                                v-model="service.categoryCode" 
                                :options="categories" 
                                label="Categoria"
                                stack-label
                                outlined
                                :option-label="val => `${val.categoryCode} - ${val.name}`"
                            >
                                <template v-slot:label>
                                    <div class="mt-2">
                                        <span>Categoria</span>
                                        <q-avatar size="35px" icon="category" />
                                        
                                    </div>
                                </template>
                            </q-select>
                        </div>

                        <div class="">
                            <q-input 
                                outlined
                                stack-label
                                v-model="service.description" 
                                type="textarea"
                                label=""
                                maxlength="255"
                            />
                            <div class="mt-1.5">
                                <span>* Descrição máxima de 255 caracteres</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <q-btn 
                                no-caps
                                label="Finalizar cadastro" 
                                type="submit" 
                                color="primary"  
                            />
                        </div>
                    </q-form>
                </div>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';

    interface Categories {
        categoryCode: number,
        category: string
    }

    interface ServiceData {
        ownerCode: number,
        categoryCode: number|string,
        name: string,
        price: number,
        description: string,
        durationString: string,
        duration: string,
        isHomeService: boolean, 
        checkAvailability: boolean, 
    };  

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        action: string,
        serviceCode: number|undefined

    }>();

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    
    const service = ref<ServiceData>({
        ownerCode: ownerCode,
        categoryCode: '',
        name: '',
        price: 0,
        description: '',
        durationString: '',
        duration: '',
        isHomeService: false, 
        checkAvailability: false 
    });

    const categories = ref<Categories[]>([]);

    function validateDurationField(val: string)
    {
        if(!val)
        {
            return 'Esse campo é necessário!';
        };

        if(Number(val) > 1440)
        {
            service.value.duration = '';
            return 'O valor deve ser no máximo 1440';
        };

        return true;
    };

    const getServiceData = async () => {
        $q.notify({
            color: 'green',
            message: 'Carregando dados do serviço ...',
            position: 'top',
            timeout: 1000

        });

        const res = await api.get(`/services/find/${ownerCode}/${props.serviceCode}`);
        const data: ServiceData = camelcaseKeys(res.data.data, { deep: true });
        console.log(data);
        service.value = {
            categoryCode: data.categoryCode,
            isHomeService: data.isHomeService ? true : false,
            checkAvailability: data.checkAvailability ? true : false,
            description: data.description,
            durationString: data.durationString,
            duration: data.durationString,
            name: data.name,
            ownerCode: data.ownerCode,
            price: Number(data.price.toString().replace(',', '.'))
        };

        console.log(service.value);
    };

    const getCategories = async () => {
        const res = await api.get(`/categories/all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });
        categories.value = data;
    };

    const createService = async () => {
        $q.notify({
            color: 'green',
            message: props.action === 'create' ? 'Validando dados ...' : 'Alterando dados ...',
            position: 'top',
            timeout: 2000

        }); 

        //const minutes = Math.floor(Number(service.value.duration) / 60);
        const minutes = ~~(Number(service.value.duration) / 60);
        const seconds = Number(service.value.duration) % 60;
        const newDuration = minutes + ':' + (seconds === 0 ? '00' : seconds);

        const payload: ServiceData = {
            categoryCode: service.value.categoryCode,
            checkAvailability: service.value.checkAvailability,
            description: service.value.description,
            durationString: service.value.duration,
            duration: newDuration,
            isHomeService: service.value.isHomeService,
            name: service.value.name,
            ownerCode: service.value.ownerCode,
            price: Number(service.value.price.toString().replace(',', '.'))
        };
    
        try {
            console.log('payload:', payload);

            if(props.action === 'create' && props.serviceCode === undefined)
            {
                const res = await api.post('/services/create', payload);
                const data = res.data;
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Serviço cadastrado com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };

            if(props.action === 'update' && props.serviceCode !== undefined)
            {
                const res = await api.put(`/services/update/${ownerCode}/${props.serviceCode}`, payload);
                const data = res.data;
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Serviço alterado com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };
        } catch (error) {
            console.error('Erro:', error);

        };
    };

    onMounted(() => {
        if(props.action === 'update') getServiceData();
        
        getCategories();
    });     
</script>