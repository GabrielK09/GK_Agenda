<template>
    <main class="p-12">
        <div 
            class="text-2xl " 
            :class="{
                'ml-32 mt-20': width >= 1100
            }"
        >
            <article
                :class="{
                    'grid grid-cols-2': width >= 1100
                }"    
            >
                <div
                    :class="{
                        'flex justify-between p-12 border w-max': width >= 1100
                    }"
                    class="max-h-max"
                >
                    <q-form
                        @submit="registerOwner"
                        class="q-gutter-md"
                    >
                        <h2 class="text-center">
                            Faça seu cadastro aqui!
                        </h2>
                        <div class="flex-row">
                            <q-input 
                                v-model="owner.name" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                class="w-[100%] mb-4 border rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div class="border-b">Nome <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-3 ml-4">
                                        <q-icon name="person" />
                                        
                                    </div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="owner.email" 
                                type="email" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                class="w-[100%] mb-4 border rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div class="border-b">Email <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-3 ml-4">
                                        <q-icon name="mail" />
                                        
                                    </div>
                                </template>
                                
                            </q-input>

                            <q-input 
                                v-model="owner.phone" 
                                type="tel" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                class="w-[100%] mb-4 border rounded-md"
                                mask="(##) #####-####"
                            >
                                <template v-slot:label>
                                    <div class="border-b">Telefone</div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-3 ml-4">
                                        <q-icon name="phone" />
                                        
                                    </div>
                                </template>
                                
                            </q-input>
                        </div>        

                        <div
                            :class="{
                                'flex gap-10': width >= 1100
                            }"

                        >
                            <q-input 
                                v-model="owner.password" 
                                :type="show ? 'text' : 'password'" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                class="mb-4 border rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div class="border-b">Senha <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-2 ml-4">
                                        <ShowPassword
                                            @show="show = $event"
                                        />
                                        
                                    </div>
                                </template>
                                
                            </q-input>

                            <q-input 
                                v-model="confirmPassword" 
                                :type="showConfirm ? 'text' : 'password'" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Confirme a sua senha "
                                class=" mb-4 border rounded-md"
                                required
                                @update:model-value="checkPasswords"
                                
                            >
                                <template v-slot:label>
                                    <div class="border-b">Confirme a sua senha <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-2 ml-4">
                                        <ShowPassword
                                            @show="showConfirm = $event"
                                        />

                                    </div>
                                </template>
                                
                            </q-input>
                        </div>                    
                        
                        <div class="w-40 ml-auto mr-auto">
                            <q-btn label="Criar conta" type="submit" class="w-full" color="primary"/>

                            
                        </div>
                        <span 
                                class="text-xs flex mt-2 justify-center cursor-pointer"
                                @click="toLogin"
                            >
                                Já possui uma conta?
                            </span>
                    </q-form>
                </div>

                <div class="relative right-12 top-8">
                    <img 
                        v-if="width > 1300 && width >= 1440"
                        src="public/images/imagem_teste.jpg" 
                    />
                </div>
            </article>
        </div>
    </main>
</template>

<script setup lang="ts">
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import ShowPassword from 'src/components/ShowPassword/ShowPassword.vue';
    
    interface Owner {
        name: string,
        email: string,
        phone: string,
        password: string
    };

    const $q = useQuasar();
    const router = useRouter();

    const owner = ref<Owner>({
        name: '',
        email: '',
        phone: '',
        password: ''
    });

    const width = LocalStorage.getItem("width") as number;

    let confirmPassword = ref<string>('');
    let show = ref<boolean>(false);
    let confirmToEmit = ref<boolean>(false);
    let showConfirm = ref<boolean>(false);

    const registerOwner = async () => {
        const payload: Owner = {
            email: owner.value.email,
            name: owner.value.name,
            password: owner.value.password,
            phone: owner.value.phone
        };
        
        if (confirmToEmit.value) {
            try {
                const res = await api.post('/auth/register-owner', payload, {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                
                if(res.data.success) {
                    $q.notify({
                        color: 'green',
                        message: res.data.message,
                        position: 'top',
                        timeout: 2000

                    });
                    LocalStorage.set("ownerID", res.data.data.id);
                    
                    router.replace({ path: '/complete-register' });
                };

            } catch (error: any) {
                const e: string = error.response?.data?.message;
                const isDuplicateMail = e.trim().includes("SQLSTATE[23000]")
                
                if (isDuplicateMail) {
                    $q.notify({
                        color: 'red',
                        message: 'Esse e-mail já está cadastrado!',
                        position: 'top',
                        timeout: 2000

                    });

                    owner.value.email = '';

                } else {
                    $q.notify({
                        color: 'red',
                        message: e,
                        position: 'top',
                        timeout: 2000

                    });
                };
            };
        };
    };
    
    const toLogin = () => {
        router.replace({ path: '/login' });

    };

    const checkPasswords = () => {
        if(confirmPassword.value != owner.value.password) {
            $q.notify({
                color: 'red',
                message: 'As senhas não são iguais!',
                position: 'top',
                timeout: 2000

            });
            confirmToEmit.value = false;
        } else {
            confirmToEmit.value = true;
        };
    };
</script>