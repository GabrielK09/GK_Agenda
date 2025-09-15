<template>
    <main class="p-12">
        <router-link to="/">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            
        </router-link>
        
        <div 
            class="text-2xl " 
            :class="{
                'ml-32 mt-12': width >= 1100
            }"
        >
            <article
                :class="{
                    'grid grid-cols-2': width >= 1100
                }"    
            >
                <div
                    :class="{
                        'flex justify-between mt-8 p-12': width >= 1100

                    }"
                    class="max-h-max"
                >
                    <q-form
                        @submit="registerOwner"
                        class="q-gutter-md p-4 ml-12"
                    >
                        <h2 class="text-center">
                            Faça seu cadastro aqui!
                        </h2>
                        <div class="flex-row ">
                            <q-input 
                                v-model="owner.name" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                outlined
                                class="max-w-[140%] mb-4 rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div>Nome <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-3 ml-2">
                                        <q-icon name="person" />
                                        
                                    </div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="owner.email" 
                                type="text" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                outlined
                                class="w-[100%] mb-4 rounded-md"
                                :rules="[checkMail]"
                                hide-bottom-space
                                required
                            >
                                <template v-slot:label>
                                    <div>Email <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-3 ml-2">
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
                                outlined
                                class="w-[100%] mb-4 rounded-md"
                                mask="(##) #####-####"
                            >
                                <template v-slot:label>
                                    <div>Telefone</div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="flex mt-3 ml-2">
                                        <q-icon name="phone" />
                                        
                                    </div>
                                </template>
                                
                            </q-input>
                        </div>        

                        <div
                            class="min-w-60"
                            :class="{
                                'flex gap-10': width >= 1100
                            }"

                        >
                            <q-input 
                                v-model="owner.password" 
                                :type="show ? 'text' : 'password'" 
                                label=""
                                stack-label
                                outlined
                                color="grey"
                                class="rounded-md w-60"
                                :rules="[validatePassword]"
                                hide-bottom-space
                                required
                            >
                                <template v-slot:prepend>
                                    <div class="flex ml-2">
                                        <ShowPassword
                                            @show="show = $event"
                                        />
                                        
                                    </div>
                                </template>

                                <template #label>
                                    <div class="">Senha</div>
                                </template>
                            </q-input>
                            
                            <q-input 
                                v-model="confirmPassword" 
                                :type="showConfirm ? 'text' : 'password'" 
                                label=""
                                stack-label
                                outlined
                                color="grey"
                                class="rounded-md w-60"
                                :rules="[validatePassword, checkPasswords]"
                                hide-bottom-space
                                required        
                            >   
                                <template v-slot:prepend>
                                    <div class="flex ml-4 mt-1">
                                        <ShowPassword
                                            @show="showConfirm = $event"
                                        />
                                        
                                    </div>
                                </template>

                                <template #label>
                                    <div class="">Confirme sua senha</div>
                                </template>
                                
                            </q-input>

                        </div>                    
                        
                        <div class="w-40 ml-auto mr-auto">
                            <q-btn label="Criar conta" type="submit" class="w-full mt-1" color="primary"/>
                            
                        </div>
                            <span 
                                class="text-xs flex mt-2 justify-center"
                                
                            >
                                <router-link to="/login">Já possui uma conta?</router-link>
                            </span>
                    </q-form>
                </div>

                <div class="relative right-8 top-20">
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
    import camelcaseKeys from 'camelcase-keys';

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
        $q.notify({
            color: 'green',
            message: 'Realizando cadastro ...',
            position: 'top',
            timeout: 2000

        });

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

                console.log('Res:', res);
                const data = camelcaseKeys(res.data, { deep: true });
                
                if(data.success) {
                    $q.notify({
                        color: 'green',
                        message: data.message,
                        position: 'top',
                        timeout: 2000

                    });
                    console.log(data.data.ownerCode);

                    LocalStorage.set("ownerCode", data.data.ownerCode);
                    
                    router.replace({ path: '/complete-register' });
                };

            } catch (error: any) {
                console.error('Erro ao criar proprietário: ', error);
                const e: string = error.response?.data?.message;
                let isDuplicateMail;
                if(e) isDuplicateMail = e.trim().includes("SQLSTATE[23000]")
                
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

    const checkMail = () => {
        if(!owner.value.email.trim().includes('@')) return 'E-mail inválido!';
        
        return true;
    };  

    const checkPasswords = () => {
        if(confirmPassword.value != owner.value.password) {
            confirmToEmit.value = false;
            return 'As senhas não são iguais!';
        } else {
            confirmToEmit.value = true;
            return true;
        };
    };

    const validatePassword = (val: string) => {
        if(val.length < 8)
        {
            return 'A senha deve conter pelo menos 8 caracteres'; 
        }

        return true;
    };
</script>