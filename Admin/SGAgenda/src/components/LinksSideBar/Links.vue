<template>
    <q-list 
        v-for="(link, i) in links"
        class="mt-2 p-2"        
    >
        <div v-if="link.name !== 'logout'">
            <router-link :to="link.url" class="bg-red-600">
                <q-item 
                    clickable 
                    class="rounded-md"
                    :class="{
                        'bg-blue-500': link.marked
                    }"

                    @click="changeMakred(i)"

                >
                    <q-item-section avat>
                        <div class="flex">
                            <q-icon size="25px" :name="link.icon" />
                            <span class="ml-3">{{ link.title }}</span>
                        </div>
                    </q-item-section>            
                </q-item>
            </router-link>
        </div>
        <div v-else>
            <q-item 
                clickable 
                class="rounded-md"
                :class="{
                    'bg-blue-500': link.marked
                }"

                @click="confirm"

            >
                <q-item-section avat>
                    <div class="flex">
                        <q-icon size="25px" :name="link.icon" />
                        <span class="ml-3">{{ link.title }}</span>
                    </div>
                </q-item-section>            
            </q-item>
        </div>
    </q-list>
</template>

<script setup lang="ts">
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';
    
    const $q = useQuasar();

    interface ILinks {
        icon: string,
        title: string,
        position: number,
        marked: boolean,
        url: string,
        name?: string
    };

    const router = useRouter();

    let links = ref<ILinks[]>([
        {icon: 'dashboard', title: 'Dashboard', position: 0, marked: true, url: `dashboard` },
        {icon: 'event', title: 'Agenda', position: 1, marked: false, url: `agenda` },
        {icon: 'attach_money', title: 'Comissões', position: 2, marked: false, url: `commission` },
        {icon: 'perm_identity', title: 'Atendentes', position: 3, marked: false, url: `attendants` },
        {icon: 'checklist', title: 'Serviços', position: 4, marked: false, url: `services` },
        {icon: 'category', title: 'Categorias', position: 5, marked: false, url: `categories` },
        {icon: 'list_alt', title: 'Produtos', position: 6, marked: false, url: `products` },
        {icon: 'link', title: 'Meu Site', position: 7, marked: false, url: `site` },
        {icon: 'settings', title: 'Configurações', position: 8, marked: false, url: `site` },
        {icon: 'logout', title: 'Sair', position: 99, marked: false, url: '', name: 'logout' },
    ]);

    const confirm = () => {
        $q.dialog({
            title: 'Confirme',
            message: 'Deseja realmente sair?',
            
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
            logout();

        }).onCancel(() => {
            return;

        });
    };
    
    const logout = async () => {
        $q.notify({
            color: 'green',
            message: 'Saindo ...',
            position: 'top',
            timeout: 2000

        });

        const res = await api.post('/auth/logout');
        if(res.data) 
        {
            LocalStorage.remove("authToken");
            LocalStorage.remove("siteName");            
            LocalStorage.remove("lastCheck"); 
            LocalStorage.remove("lastURL"); 

            router.replace({ path: '/' });   

            $q.notify({
                color: 'green',
                message: 'Volte sempre!',
                position: 'top'

            });
        };
    };

    const changeMakred = (i: number) => {
        LocalStorage.set("lastCheck", i);

        links.value.forEach(link => {
            if(link.position === i) link.marked = true, LocalStorage.set("lastURL", link.url);
            if(link.position !== i) link.marked = false;
        });
    };

    onMounted(() => {;
        router.replace({ path: LocalStorage.getItem("lastURL") as string })
        links.value.forEach(link => {
            if(link.position === LocalStorage.getItem("lastCheck")) link.marked = true;
            if(link.position !== LocalStorage.getItem("lastCheck")) link.marked = false;
        });
    }); 
    
</script>