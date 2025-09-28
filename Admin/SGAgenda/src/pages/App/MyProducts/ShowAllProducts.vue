<template>
    <q-page padding>
        <section class="text-xl" v-if="!productManagement">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Produtos</h2>

                    <div class="mt-auto mb-auto">
                        <q-btn 
                            no-caps 
                            class="bg-sky-500 text-white" 
                            @click="showProductManagement('create', undefined)" 
                            label="Cadastrar novo produto"
                        
                        />
                    </div>
                </div>

                <div class="">
                    <q-table
                        borded
                        :rows="products"
                        :columns="columns"
                        row-key="name"
                        class="rounded-xl"
                    >
                        <template v-slot:top-right>
                            <q-input 
                                outlined
                                v-model="searchInput" 
                                type="text" 
                                label="" 
                                @update:model-value="filterProducts"
                            >
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                <template v-slot:label>
                                    <span class="text-xs">Buscar por um produto ...</span>
                                </template>
                            </q-input>
                        </template>

                        <template v-slot:body="props">
                            <q-tr
                                :props="props"
                            >
                                <q-td
                                    v-for="(col, i) in props.cols"
                                >
                                    <template v-if="col.name === 'actions'">
                                        <div 
                                            class="text-center"
                                        >
                                            <q-btn :disabled="props.row.active !== 1" size="10px" no-caps color="black" icon="edit_square" flat @click="showProductManagement('update', props.row.productCode)"/>
                                            <q-btn :disabled="props.row.active !== 1" size="10px" no-caps color="red" icon="delete" flat @click="showDialogDeleteProduct(props.row.productCode)"/>
                                            <q-btn :disabled="props.row.active === 1" size="10px" no-caps color="green" icon="check" flat @click="showDialogActiveProduct(props.row.productCode)"/>
                                            
                                        </div>
                                    </template>
                                    
                                    <template v-else>
                                        <div 
                                            class="text-center"
                                            :title="props.row.active !== 1 ? 'Produto desativado!' : ''"
                                        >
                                            <div 
                                                :class="{
                                                    'text-gray-400': props.row.active !== 1
                                                }"
                                            >
                                                {{ col.value }}

                                            </div>

                                        </div>
                                    </template>
                                </q-td>
                            </q-tr>
                        </template>

                        <template v-slot:no-data>
                            <div class="ml-auto mr-auto">
                                <q-icon name="warning" size="30px"/>
                                <span class="mt-auto mb-auto ml-2 text-xs">Sem produtos cadastrados</span>

                            </div>
                        </template>

                    </q-table>
                </div>
            </div>
        </section>
        
        <ProductManagement
            v-if="productManagement"
            @close="attPage($event)"
            :action="action"
            :product-code="selectedProductCode"

        />
    </q-page>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { LocalStorage, QTableColumn, useQuasar } from 'quasar';
    import { onMounted, ref } from 'vue';    
    import camelcaseKeys from 'camelcase-keys';
    import ProductManagement from 'src/components/App/ProductManagement/ProductManagement.vue';    

    interface Product {
        name: string,        
        price: number,
        amount: number,
    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    
    const columns: QTableColumn[] = [
        {
            name: 'name',
            label: 'Produto',
            field: 'name',
            align: 'center'
        },
        {
            name: 'price',
            label: 'Preço',
            field: 'price',
            align: 'center',
            format(val: number) {
                return `R$ ${val.toFixed(2).toString().replace('.', ',')}`
            }
        },
        {
            name: 'amount',
            label: 'Qtde',
            field: 'amount',
            align: 'center'
        },
        {
            name: 'actions',
            label: '',
            field: 'actions',
            align: 'right'
        }
    ];

    let allProducts = ref<Product[]>([]);
    let products = ref<Product[]>([]);

    let searchInput = ref<string>('');
    let productManagement = ref<boolean>(false);

    let action = ref<string>('');
    let selectedProductCode = ref<number|undefined>(0);

    const search = () => {
        
    };

    const getAllProducts = async () => {
        const res = await api.get(`/products/all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });

        products.value = data;
        allProducts.value = [...products.value];
        
    };

    const showDialogDeleteProduct = (productCode: number) => {
        $q.dialog({
            title: 'Excluir produto',
            message: `Deseja realmente remover esse produto (${productCode})?`,
            cancel: {
                push: true,
                label: 'Não',
                color: 'red',
            },

            ok: {
                push: true,
                label: 'Sim',
                color: 'green',
            },

        }).onOk(() => {
            deleteProduct(productCode);

        }).onCancel(() => {
            return;
        });
    };

    const showDialogActiveProduct = (productCode: number) => {
        $q.dialog({
            title: 'Ativar produto',
            message: `Deseja realmente ativar esse produto (${productCode})?`,
            cancel: {
                push: true,
                label: 'Não',
                color: 'red',
            },

            ok: {
                push: true,
                label: 'Sim',
                color: 'green',
            },

        }).onOk(() => {
            activeProduct(productCode);

        }).onCancel(() => {
            return;
        });
    };

    const deleteProduct = async (productCode: number) => {
        const res = await api.delete(`/products/delete/${ownerCode}/${productCode}`);
        const data = res.data;

        if(data.success)
        {
            $q.notify({
                color: 'green',
                message: data.message,
                position: 'top',
                timeout: 1200
            });
        };
        getAllProducts();
    };

    const activeProduct = async (productCode: number) => {
        const res = await api.put(`/products/active/${ownerCode}/${productCode}`);
        const data = res.data;

        if(data.success)
        {
            $q.notify({
                color: 'green',
                message: data.message,
                position: 'top',
                timeout: 1200
            });
        };
        getAllProducts();
    };

    const filterProducts = () => {    
        console.log(searchInput.value);
        
        products.value = allProducts.value.filter(product => product.name.toLowerCase().includes(searchInput.value));
        console.log(allProducts.value);
        
    };

    const showProductManagement = (management: string, productCode:number|undefined) => {
        productManagement.value = true;
        action.value = management;
        selectedProductCode.value = productCode;
    };

    const attPage = (event: boolean) => {
        productManagement.value = !event;
        getAllProducts();
    };

    onMounted(() => {
        getAllProducts();
    });
</script>