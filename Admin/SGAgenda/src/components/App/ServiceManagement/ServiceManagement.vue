<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Cadastrar serviço</h2>
                </div>

                <div class="bg-white p-8">
                    <div class="flex text-xs">
                        <span>Voltar para os serviços</span>

            
                        <div class="text-base">
                            <q-checkbox 
                                left-label 
                                label="Teste"
                            />
                        </div>
                    </div>
                    <q-form
                        @submit=""
                        class="q-gutter-md mt-4"
                    >
                        <q-input 
                            v-model="text" type="text" label="Label" />
                        <div>
                            <q-btn label="Submit" type="submit" color="primary"/>
                        </div>
                    </q-form>
                </div>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import { ref } from 'vue';

    let show = ref<boolean>(true);
</script>