<template>
    <q-dialog class="w-[40rem] text-center z-50" v-model="showQR" v-if="qrCode" persistent>
        <div class="bg-white">
            <div v-if="!hasPixKey" class="mt-2 mb-1">
                <q-select 
                    v-model="pixKeyOption" 
                    :options="pixKeys" 
                    label="Chave PIX" 
                    stack-label
                    outlined
                    class="w-44 ml-auto mr-auto"
                />
                <q-btn color="primary" no-caps label="Salvar chave PIX" @click="updatePix" />
            </div>

            <div v-else>
                <img :src=qrCode alt="qrCode - PIX" class="ml-auto mr-auto mt-4">

            </div>

            <div class="m-8">
                <div v-for="(product, i) in products">
                    <span>Produto: {{ product.name }}</span>
                    <span>R$: {{ product.price.toFixed(2).toString().replace('.', ',') }}</span>
                    <span @click="addProduct(product.productCode, i)" class="p-4 hover:bg-slate-300">+</span>
                </div>
            </div>

            <span>R$: {{ total.toFixed(2).toString().replace('.', ',') }}</span>
        
            <q-card-section class="flex justify-center">
                <q-btn 
                    color="primary"
                    label="Finalizar venda" 
                    @click="finaly" 
                    no-caps
                    
                />
            </q-card-section>
        </div>
    </q-dialog>   
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import generatePIX from 'src/utils/generatePIX';
    import { onMounted, ref } from 'vue'

    interface Product {
        productCode: number,
        name: string,
        price: number,
    };
    
    const props = defineProps<{
        totalAmount: number|undefined,
    }>();

    const emits = defineEmits<{
        (e: 'close', value: boolean): void,

    }>();

    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    const products = ref<Product[]>([]);
    const pixKeys = ref<string[]>([
        'CNPJ/CPF',
        'Tel',
        'E-mail'
    ]);

    let pixKeyOption = ref<string>('');

    let qrCode = ref<string>('');
    let pixKey = ref<string>('');
    let showQR = ref<boolean>(true);
    let hasPixKey = ref<boolean>(false);
    let total = ref<number>(0);
   
    const updatePix = async() => {
        const res = await api.put(`/owner/update/pix-key/${ownerCode}`, {
            pix: pixKeyOption.value
        });
        const data = res.data;

        if(data.success)
        {
            hasPixKey.value = true;
        };
    };

    const getProducts = async () => {
        const res = await api.get(`/products/all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });

        products.value = data;
    };

    const getKey = async () => {
        const res = await api.get(`/owner/data/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });

        if(data.pixKey !== '') {
            hasPixKey.value = true;

            switch (data.pixKey) {
                case 'CNPJ/CPF':
                    pixKey.value = data.cnpjCpf;
                    break;
            
                case 'Tel':
                    pixKey.value = data.phone;
                    break;
                    
                case 'E-mail':
                    pixKey.value = data.email;
                    break;

                default:
                    break;
            };

            getQRCode(total.value)
        } else {
            hasPixKey.value = false;
        };
    };
    
    const getQRCode = async (totalAmount: number) => {
        const res = await generatePIX(totalAmount, String(pixKey.value));

        qrCode.value = res.base64;
        
    };
    
    //const addProduct(product.productCode, i) = () => {
    const addProduct = (productCode: number, position: number) => {
        const product = products.value[position];
        if(!product) return;

        total.value += product.price
        getKey();
    };  

    const finaly = () => 
    {
        emits('close', true);
    };
    
    onMounted(() => {
        total.value = Number(props.totalAmount);
        getKey();
        getProducts();
    });

</script>       