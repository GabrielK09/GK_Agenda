<template>
    <q-page padding>
        <div class="flex flex-col items-center text-2xl">
            <div class="cards-1 flex gap-16 max-w-[100%]">
                <div class="card-1">
                    <div class="bg-blue-400 text-white rounded-md p-8 w-96">
                        <span>Totais de atendimentos:</span>
                        
                        <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">{{ countSchedulings }}</span></span>
                    </div>
                </div>

                <div class="card-2">
                    <div class="bg-blue-500 text-white rounded-md p-8 w-96">
                        <span>Faturamento total:</span>
                            
                        <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">R$ 0,00</span></span>
                    </div>
                </div>

                <div class="card-3">
                    <div class="bg-blue-600 text-white rounded-md p-8 w-96">
                        <span>Totais de atendimentos:</span>
                        
                        <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">{{ countSchedulings }}</span></span>
                    </div>
                </div>
            </div>

            <div class="cards-2 mt-4 flex gap-16 max-h-96">
                <div class="card-1">
                    <div class="bg-blue-400 text-white rounded-md p-8 w-96 h-96">
                        
                    </div>

                </div>

                <div class="card-2">
                    <div class="bg-blue-500 text-white rounded-md p-8 w-96 h-96">

                    </div>

                </div>

                <div class="card-3">
                    <div class="bg-blue-600 text-white rounded-md p-8 w-96 h-96">

                    </div>

                </div>
            </div>
        </div>
    </q-page>
</template>

<script setup lang="ts">
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';

    const ownerCode = LocalStorage.getItem("ownerCode");
    const width = LocalStorage.getItem("width") as number;

    let countSchedulings = ref<number>(0);

    const getAllSchedulings = async () => {
        const res = await api.get(`/schedule/get-all/${ownerCode}`);
        const data: unknown[] = camelcaseKeys(res.data.data, { deep: true });
        countSchedulings.value = data.length;

    };

    onMounted(() => {
        getAllSchedulings();

    });
    
</script>