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
                        'flex justify-center p-12 border': width >= 1100
                    }"
                    class="max-h-max mt-auto"
                >
                    <q-form
                        @submit="login"
                        class="q-gutter-md w-[60vh] mr-24 mt-4"
                    >
                        <h2 class="text-center">
                            Bem-vindo(a) de volta!
                        </h2>
                        <div class="flex-row m-auto">
                            <q-input 
                                v-model="auth.email" 
                                type="email" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Email"
                                class="w-[120%] mb-4 border rounded-md"
                                required
                            >
                                <template v-slot:label>
                                    <div class="border-b">E-mail <span class="text-red-500 text-xs relative bottom-1">*</span></div>
                                </template>

                                <template v-slot:prepend>
                                    <div class="ml-2"></div>
                                </template>
                            </q-input>

                            <q-input 
                                v-model="auth.password" 
                                :type="show ? 'text' : 'password'" 
                                stack-label
                                label-slot
                                borderless
                                color="grey"
                                label="Nome"
                                class="w-[120%] mb-4 border rounded-md"
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

                        </div>        
                        
                        <div class="w-40 ml-auto mr-auto">
                            <q-btn label="Entrar" type="submit" class="w-full" color="primary"/>
                            
                        </div>
                    </q-form>
                </div>

                <div class="ml-12 relative right-8 top-8">
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

    interface AuthData {
        email: string,
        password: string
    };

    const $q = useQuasar();
    const router = useRouter();

    const width = LocalStorage.getItem("width") as number;
    const auth = ref<AuthData>({
        email: '',
        password: ''
        
    });

    let show = ref<boolean>(false);

    const login = async () => {
        $q.notify({
            color: 'green',
            message: 'Validando dados de login ... ',
            position: 'top',
            timeout: 1200

        });
        const payload: AuthData = {
            email: auth.value.email,
            password: auth.value.password
        };

        try {
            const res = await api.post('/auth/login', payload);
            const data = res.data;
            console.log(data);

            if(data.success) 
            { 
                $q.notify({
                    color: 'green',
                    message: 'Login bem sucedido!',
                    position: 'top',
                    timeout: 1200

                });

                LocalStorage.set("authToken", data.token);
                LocalStorage.set("isAttendant", data.isAttendant);
                LocalStorage.set("siteName", data.siteName);

                LocalStorage.set("lastCheck", 0);

                router.replace({ path: `/admin/${data.siteName}/dashboard` });
                
            };
        } catch (error: any) {
            console.error(error);

            $q.notify({
                color: 'red',
                message: error.response?.data?.message,
                position: 'top',
                timeout: 1200

            });
        };
    };

</script>