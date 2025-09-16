<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">                    
                    <h2 class="text-gray-600 m-2">Cadastrar categoria</h2>
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
                        @submit="createCategory"
                        class="q-gutter-md mt-4 rounded-xl"
                    >
                        <div class="grid grid-cols-2">
                            <q-input 
                                label="Nome da categoria" 
                                v-model="category.name" 
                                stack-label
                                outlined
                                type="text" 
                                class="mr-8"
                                :rules="[
                                    val => !!val || 'O nome da categoria é necessário!'
                                ]"
                            />
                                              
                            <q-select 
                                v-model="category.parentCategory" 
                                :options="categories" 
                                label="Categoria"
                                stack-label
                                outlined
                                :option-label="val => `${val.categoryCode} - ${val.name}`"
                            >
                                <template v-slot:label>
                                    <div class="mt-2">
                                        <span>Categoria pai</span>
                                        <q-avatar size="35px" icon="category" />
                                        
                                    </div>
                                </template>
                            </q-select>
                        </div>

                        <div class="">
                            <q-input 
                                outlined
                                stack-label
                                v-model="category.description" 
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

    interface CategoriesData {
        ownerCode: number,
        parentCategory: number|string,
        name: string,
        description: string,
    };  

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        action: string,
        categoryCode: number|undefined

    }>();

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    
    const category = ref<CategoriesData>({
        ownerCode: ownerCode,
        parentCategory: '',
        name: '',
        description: ''
    });

    const categories = ref<CategoriesData[]>([]);
    
    const getCategoriesData = async () => {
        $q.notify({
            color: 'green',
            message: 'Carregando dados da categoria ...',
            position: 'top',
            timeout: 1000

        });

        const res = await api.get(`/categorys/find/${ownerCode}/${props.categoryCode}`);
        const data: CategoriesData = camelcaseKeys(res.data.data, { deep: true });
        
        category.value = {
            parentCategory: data.parentCategory,
            description: data.description,
            name: data.name,
            ownerCode: data.ownerCode,
        };

        console.log(category.value);
    };

    const getCategories = async () => {
        const res = await api.get(`/categories/all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });
        categories.value = data;
    };

    const createCategory = async () => {
        $q.notify({
            color: 'green',
            message: props.action === 'create' ? 'Validando dados ...' : 'Alterando dados ...',
            position: 'top',
            timeout: 2000

        }); 

        const payload: CategoriesData = {
            parentCategory: category.value.parentCategory,
            description: category.value.description,
            name: category.value.name,
            ownerCode: category.value.ownerCode,
        };
    
        try {
            console.log('payload:', payload);
            
            if(props.action === 'create' && props.categoryCode === undefined)
            {
                const res = await api.post('/categories/create', payload);
                const data = res.data;
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Categoria cadastrada com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };
            
            if(props.action === 'update' && props.categoryCode !== undefined)
            {
                const res = await api.put(`/categories/update/${ownerCode}/${props.categoryCode}`, payload);
                const data = res.data;
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Categoria alterada com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };
            
        } catch (error: any) {
            $q.notify({
                color: 'red',
                message: error.response?.data?.message,
                position: 'top',
                timeout: 2000

            }); 

            console.error('Erro:', error);

        };
    };

    onMounted(() => {
        if(props.action === 'update') getCategoriesData();
        
        getCategories();
    });     
</script>