<template>
    <q-page padding>
        <section class="text-xl" v-if="!categoryManagement">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Categorias</h2>

                    <div class="mt-auto mb-auto">
                        <q-btn 
                            no-caps 
                            class="bg-sky-500 text-white" 
                            @click="showCategoryManagement('create', undefined)" 
                            label="Cadastrar nova categoria"
                        
                        />
                    </div>
                </div>

                <div class="">
                    <q-table
                        borded
                        :rows="categories"
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
                                @update:model-value="filterCategories"
                            >
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                <template v-slot:label>
                                    <span class="text-xs">Buscar por uma categoria ...</span>
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
                                        <div class="text-center">
                                            <q-btn :disabled="props.row.active !== 1" size="10px" no-caps color="black" icon="edit_square" flat @click="showCategoryManagement('update', props.row.categoryCode)"/>
                                            <q-btn :disabled="props.row.active !== 1" size="10px" no-caps color="red" icon="delete" flat @click="showDialogCategory(props.row.categoryCode, 'delete')"/>
                                            <q-btn :disabled="props.row.active === 1" size="10px" no-caps color="green" icon="check" flat @click="showDialogCategory(props.row.categoryCode, 'active')"/>

                                        </div>
                                    </template>
                                    
                                    <template v-else>
                                        <div class="text-center">
                                            {{ col.value }}

                                        </div>
                                    </template>
                                </q-td>
                            </q-tr>
                        </template>

                        <template v-slot:no-data>
                            <div class="ml-auto mr-auto">
                                <q-icon name="warning" size="30px"/>
                                <span class="mt-auto mb-auto ml-2 text-xs">Sem categorias cadastrados</span>

                            </div>
                        </template>

                    </q-table>
                </div>
            </div>
        </section>
        
        <CategoryManagement
            v-if="categoryManagement"
            @close="attPage($event)"
            :action="action"
            :category-code="selectedCategoryCode"

        />
    </q-page>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { LocalStorage, QTableColumn, useQuasar } from 'quasar';
    import { onMounted, ref } from 'vue';    
    import CategoryManagement from 'src/components/App/CategoryManagement/CategoryManagement.vue';
    import camelcaseKeys from 'camelcase-keys';

    interface Categories {
        categoryCode: number,
        name: string,
        parentCategory: string,
        category: string,
        description: string
        
    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;
    
    const columns: QTableColumn[] = [
        {
            name: 'categoryCode',
            label: 'Cód',
            field: 'categoryCode',
            align: 'center'
        },
        {
            name: 'parentCategory',
            label: 'Categoria pai',
            field: 'parentCategory',
            align: 'center'
        },
        {
            name: 'name',
            label: 'Categoria',
            field: 'name',
            align: 'center'
        },
        {
            name: 'description',
            label: 'Descrição',
            field: 'description',
            align: 'center'
        },
        {
            name: 'actions',
            label: '',
            field: 'actions',
            align: 'right'
        }
    ];

    let allCategories = ref<Categories[]>([]);
    let categories = ref<Categories[]>([]);

    let searchInput = ref<string>('');
    let categoryManagement = ref<boolean>(false);

    let action = ref<string>('');
    let selectedCategoryCode = ref<number|undefined>(0);

    const search = () => {
        
    };

    const getAllCategories = async () => {
        const res = await api.get(`/categories/all/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });
        categories.value = data;
        allCategories.value = [...categories.value];
        
    };

    const showDialogCategory = (categoryCode: number, action: string) => {
        $q.dialog({
            title: action === 'delete' ? 'Desativar categoria' : 'Ativar categoria',
            message: `Deseja realmente ${action === 'delete' ? `desativar` : 'ativar'} essa categoria (${categoryCode})?`,
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
            executeCategory(categoryCode, action);

        }).onCancel(() => {
            return;
        });
    };

    const executeCategory = async (categoryCode: number, action: string) => {
        let success;
        try {
            if(action === 'delete') {
                const res = await api.delete(`/categories/delete/${ownerCode}/${categoryCode}`);
                const data = res.data;
                success = data;
                
            } else {
                const res = await api.put(`/categories/active/${ownerCode}/${categoryCode}`);
                const data = res.data;
                success = data;
            };

            if(success.success)
            {
                $q.notify({
                    color: 'green',
                    message: success.message,
                    position: 'top'
                });

                getAllCategories();
            };
            
        } catch (error: any) {
            $q.notify({
                color: 'red',
                message: error.response?.data?.message,
                position: 'top'
            });

        };
    };

    const filterCategories = () => {    
        console.log(searchInput.value);
        
        categories.value = allCategories.value.filter(category => category.name.toLowerCase().includes(searchInput.value));
        console.log(categories.value);
        
    };

    const showCategoryManagement = (management: string, categoryCode:number|undefined) => {
        categoryManagement.value = true;
        action.value = management;
        selectedCategoryCode.value = categoryCode;
    };

    const attPage = (event: boolean) => {
        categoryManagement.value = !event;
        getAllCategories();
    };

    onMounted(() => {
        getAllCategories();
    });
</script>