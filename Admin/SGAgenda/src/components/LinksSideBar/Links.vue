<template>
    <q-list 
        v-for="(link, i) in links"
        class="mt-2 p-2"        
    >
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
    </q-list>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { onMounted, reactive, ref } from 'vue';
    import { useRouter } from 'vue-router';

    interface ILinks {
        icon: string,
        title: string,
        position: number,
        marked: boolean,
        url: string
    };

    const router = useRouter();
    const siteName = ref(LocalStorage.getItem("siteName") as string);

    let links: ILinks[] = reactive([
        {icon: 'dashboard', title: 'Dashboard', position: 0, marked: true, url: `dashboard` },
        {icon: 'event', title: 'Agenda', position: 1, marked: false, url: `agenda` },
        {icon: 'attach_money', title: 'Comissões', position: 2, marked: false, url: `agenda` },
        {icon: 'perm_identity', title: 'Atendentes', position: 3, marked: false, url: `agenda` },
        {icon: 'checklist', title: 'Serviços', position: 4, marked: false, url: `services` },
        {icon: 'list_alt', title: 'Produtos', position: 5, marked: false, url: `agenda` },
        {icon: 'wallet', title: 'Caixa', position: 6, marked: false, url: `agenda` },
        
    ]);

    const changeMakred = (i: number) => {
        console.log('Vai mudar a cor do fundo: ', i);
        LocalStorage.set("lastCheck", i);

        links.map(link => {
            if(link.position === i) link.marked = true, LocalStorage.set("lastURL", link.url);
            if(link.position !== i) link.marked = false;
        });
    };

    onMounted(() => {;
        router.replace({ path: LocalStorage.getItem("lastURL") as string })
        links.map(link => {
            if(link.position === LocalStorage.getItem("lastCheck")) link.marked = true;
            if(link.position !== LocalStorage.getItem("lastCheck")) link.marked = false;
        });
    }); 
    
</script>