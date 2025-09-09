<template>
    <q-page padding>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div
                    :class="{
                        'grid grid-cols-3 ml-12 mr-12': width > 1000,
                    }"
                >
                    <div class="card-1 m-2 flex ">
                        <div class="p-6 bg-blue-500 text-white rounded-lg w-[45vh]">
                            <span>Totais de atendimentos:</span>
                            
                            <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">{{ 0 }}</span></span>
                        </div>

                        <div class="">

                        </div>
                    </div>

                    <div class="card-2 m-2">
                        <div class="p-6 bg-sky-400 text-white rounded-lg w-[45vh] ">
                            <span>Faturamento total:</span>
                            
                            <span class="flex"><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">R$ 0,00</span></span>

                        </div>

                        <div class="">

                        </div>
                    </div>    
                    
                    <div class="card-3 m-2">
                        <div class="p-6 bg-sky-500 text-white rounded-lg w-[45vh]">
                            <span>Média:</span>

                            <span class="flex "><div class="bg-white w-1 h-4 mt-4 mr-3"></div><span class="mt-3">R$ 0,00</span></span>
                        </div>

                        <div class="">

                        </div>
                    </div>  

                    <div class="card-4 m-2">
                        <div class="border w-[45vh] max-h-48 h-48 rounded-md p-2 shadow-xl">
                            <span class="border-b">Serviços mais agendados</span>
                            <div v-if="false">
                                
                            </div>
                        </div>                    
                    </div>

                    <div class="card-5 m-2">
                        <div class="border w-[45vh] max-h-48 h-48 rounded-md p-2 shadow-xl">

                        </div>
                    
                    </div>

                    <div class="card-6 m-2">
                        <div class="border w-[45vh] max-h-48 h-48 rounded-md p-2 shadow-xl">

                        </div>

                    </div>

                    <div class="card-7 m-2">
                        <!--Horários mais atendidos-->
                        <div class="border w-[45vh] max-h-48 h-48 rounded-md p-2 shadow-xl">

                            
                        </div>
                    </div>

                    <div class="card-8 m-2">
                        <!--Mapa de calor-->
                        <div class="border w-[45vh] max-h-48 h-48 rounded-md p-2 shadow-xl">

                        </div>

                    </div>

                    <div class="card-8 m-2">
                        <!--Categoria mais selecionada-->
                        <div class="border w-[45vh] max-h-48 h-48 rounded-md p-2 shadow-xl">

                        </div>

                    </div>

                    <div class="meta m-2 mt-12">
                        <div class="border w-[45vh] rounded-md p-2 shadow-xl">
                            <span>Meta: </span>


                        </div>
                    </div>
                </div>
            <!--fim-->
            </div>  
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';

    const width = LocalStorage.getItem("width") as number;
</script>