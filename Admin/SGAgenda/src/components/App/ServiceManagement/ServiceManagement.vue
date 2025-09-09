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
                        <div class="flex mb-auto mt-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>      
                            <span 
                                class="cursor-pointer"
                                @click="emits('close', true)"
                            >
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
                                :rules="[]"
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
                                :options="categorys" 
                                label="Categoria"
                                stack-label
                                outlined
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
    import { LocalStorage } from 'quasar';
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
        duration: string,
        isHomeService: boolean, 
        checkAvailability: boolean, 
    };  

    const emits = defineEmits([
        'close'
    ]);

    const service = ref<ServiceData>({
        ownerCode: LocalStorage.getItem("ownerID") as number,
        categoryCode: '',
        name: '',
        price: 0,
        description: '',
        duration: '',
        isHomeService: false, 
        checkAvailability: false 
    });

    const categorys = ref<Categories[]>([]);
    let show = ref<boolean>(true);

    const getCategories = async () => {

    };

    const createService = async () => {
        const payload = service.value;

        
    };

    onMounted(() => {
        getCategories();
    }); 

</script>