<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">   
                    <h2 class="text-gray-600 m-2">{{ props.action === 'create' ? 'Cadastrar um(a) novo(a) atendete' : 'Editar dados do(a) atendente'}}</h2>
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
                    </div>

                    <q-form
                        @submit="createAttendant"
                        class="q-gutter-md mt-4 rounded-xl"
                    >
                        <div class="grid grid-cols-2">
                            <q-input 
                                label="Nome do atedendente *" 
                                v-model="attendant.name" 
                                stack-label
                                outlined
                                type="text"
                                class="mr-4" 
                                dense
                            />
                        
                            <q-input 
                                v-model="attendant.email" 
                                type="text" 
                                label="E-mail *" 
                                stack-label
                                outlined
                                dense
                                :disable="props.action === 'update'"
                                :rules="[validateMail]"
                            />
                        </div>

                        <div v-if="props.action !== 'update'">
                            <div class="grid grid-cols-2">
                                <q-input 
                                    label="Senha *" 
                                    v-model="attendant.password" 
                                    stack-label
                                    outlined
                                    type="text"
                                    class="mr-4" 
                                    dense
                                />
                            
                                <q-input 
                                    v-model="attendant.confirmPassword" 
                                    type="text" 
                                    label="Confirme sua senha *" 
                                    stack-label
                                    outlined
                                    dense
                                    :rules="[validatePassword]"
                                />
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

    interface AttendantData {
        ownerCode: number,
        name: string,
        email: string,
        confirmPassword: string,
        password: string,
    };  

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        action: string,
        attendantCode: number|undefined

    }>();

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    
    const attendant = ref<AttendantData>({
        ownerCode: ownerCode,
        name: '',
        email: '',
        confirmPassword: '',
        password: ''
    });

    const getAttendantData = async () => {
        $q.notify({
            color: 'green',
            message: 'Carregando dados do atendente ...',
            position: 'top',
            timeout: 1000

        });

        const res = await api.get(`/attendants/find/${ownerCode}/${props.attendantCode}`);
        console.log(res.data);
        

        const data: AttendantData = camelcaseKeys(res.data.data, { deep: true });

        attendant.value = {
            ownerCode: data.ownerCode,
            name: data.name,
            email: data.email,
            confirmPassword: data.password,
            password: data.password
        };

        console.log(attendant.value);
    };

    const createAttendant = async () => {
        $q.notify({
            color: 'green',
            message: props.action === 'create' ? 'Validando dados ...' : 'Alterando dados ...',
            position: 'top',
            timeout: 2000

        }); 

        const payload: AttendantData = {
            ownerCode: attendant.value.ownerCode,
            name: attendant.value.name,
            email: attendant.value.email,
            password: attendant.value.password,
            confirmPassword: attendant.value.confirmPassword
        };
    
        try {
            console.log('payload:', payload);

            if(props.action === 'create' && props.attendantCode === undefined)
            {
                const res = await api.post('/attendants/create', payload);
                console.log(res);
                const data = res.data;

                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Atendente cadastrado com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };

            if(props.action === 'update' && props.attendantCode !== undefined)
            {
                const res = await api.put(`/attendants/update/${ownerCode}/${props.attendantCode}`, payload);
                const data = res.data;
                console.log(res.data);
                
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Dados do atendente alterado com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };
        } catch (error: any) {
            console.error('Erro:', error);
            $q.notify({
                color: 'red',
                message: error?.reponse?.data?.message,
                position: 'top'
                
            });
        };
    };

    const validatePassword = () => {
        if(props.action !== 'update')
        {
            if(attendant.value.confirmPassword !== attendant.value.password)
            {
                return 'A senhas devem ser iguais!'

            };
        };
        
        return true;
    };  

    const validateMail = () => {
        if(!attendant.value.email.trim().includes('@'))
        {
            return 'Insira um e-mail válido!'
            
        }

        return true;
    };

    onMounted(() => {
        if(props.action === 'update') getAttendantData();
    });     
</script>