<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">                    
                    <h2 class="text-gray-600 m-2">Cadastrar produto</h2>
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
                        @submit="createProduct"
                        class="q-gutter-md mt-4 rounded-xl"
                    >
                        <div class="grid grid-cols-2">
                            <q-input 
                                label="Nome do produto" 
                                v-model="product.name" 
                                stack-label
                                outlined
                                type="text" 
                                class="mr-6"
                                :rules="[
                                    val => !!val || 'O nome do produto é necessário!'
                                ]"
                            />
                                              
                            <q-input 
                                v-model="product.price" 
                                type="text"
                                input-class="text-right"
                                label="Preço do produto" 
                                stack-label
                                outlined
                                mask="##,##"
                                reverse-fill-mask
                                class="mr-10"   
                            >
                                <template v-slot:label>
                                    <div class="mt-2">
                                        <span>Preço do produto</span>
                                    </div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="product.amount" 
                                type="text"
                                input-class="text-right"
                                label="Quantidade" 
                                stack-label
                                outlined
                                class="mr-6"
                            >
                                <template v-slot:label>
                                    <div class="mt-2">
                                        <span>Quantidade</span>
                                    </div>
                                </template>
                            </q-input>
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

    interface ProductData {
        ownerCode: number,
        name: string,
        price: number,
        amount: number,
    };  

    const emits = defineEmits([
        'close'
    ]);

    const props = defineProps<{
        action: string,
        productCode: number|undefined

    }>();

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    
    const product = ref<ProductData>({
        ownerCode: ownerCode,
        name: '',
        price: 0,
        amount: 0

    });

    const getProductsData = async () => {
        $q.notify({
            color: 'green',
            message: 'Carregando dados do produto ...',
            position: 'top',
            timeout: 1000

        });

        const res = await api.get(`/products/find/${ownerCode}/${props.productCode}`);
        const data: ProductData = camelcaseKeys(res.data.data, { deep: true });
        console.log(data);

        product.value = {
            ownerCode: data.ownerCode,
            name: data.name,
            amount: data.amount,
            price: data.price

        };

        console.log(product.value);
    };

    const createProduct = async () => {
        $q.notify({
            color: 'green',
            message: props.action === 'create' ? 'Validando dados ...' : 'Alterando dados ...',
            position: 'top',
            timeout: 2000

        }); 

        const payload: ProductData = {
            ownerCode: product.value.ownerCode,
            name: product.value.name,
            amount: product.value.amount,
            price: Number(product.value.price.toString().replace(',', '.'))
        };
    
        try {
            console.log('payload:', payload);

            if(props.action === 'create' && props.productCode === undefined)
            {
                const res = await api.post('/products/create', payload);
                const data = res.data;
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Produto cadastrado com sucesso!',
                        position: 'top',
                        timeout: 1200

                    });

                    emits('close', true);
                };
            };

            if(props.action === 'update' && props.productCode !== undefined)
            {
                const res = await api.put(`/products/update/${ownerCode}/${props.productCode}`, payload);
                const data = res.data;
                if(data.success)
                {
                    $q.notify({
                        color: 'green',
                        message: 'Produto alterada com sucesso!',
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
        if(props.action === 'update') getProductsData();        
    });     
</script>