<template>
    <q-page>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">                    
                    <h2 class="text-gray-600 m-2">Cadastrar um atendete</h2>
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
                                :rules="[validateMail]"
                            />
                        </div>

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