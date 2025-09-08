<template>
    <q-list class="mt-2 p-2">
        <q-item 
            v-for="(link, i) in links"
            clickable 
            class="rounded-md mb-4"
            :class="{
                'bg-blue-700': link.marked
            }"
            @click="changeMakred(i)"
        >
            <q-item-section avatar >
                <q-icon size="25px" :name="link.icon" />

            </q-item-section>
            
            <q-item-section>
                <router-link :to="`/${link.url}`">{{ link.title }}</router-link>
                
            </q-item-section>
            
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
    import { LocalStorage } from 'quasar';
    import { onMounted, reactive, ref } from 'vue';

    interface ILinks {
        icon: string,
        title: string,
        position: number,
        marked: boolean,
        url: string
    };

    const siteName = ref(LocalStorage.getItem("siteName") as string);

    let links: ILinks[] = reactive([
        {icon: 'dashboard', title: 'Dashboard', position: 0, marked: true, url: `admin/${siteName.value}/dashboard` },
        {icon: 'event', title: 'Agenda', position: 1, marked: false, url: `admin/${siteName.value}/agenda` },
        {icon: 'attach_money', title: 'Comissões', position: 2, marked: false, url: `admin/${siteName.value}/agenda` },
        {icon: 'perm_identity', title: 'Atendetes', position: 3, marked: false, url: `admin/${siteName.value}/agenda` },
        {icon: 'checklist', title: 'Serviços', position: 4, marked: false, url: `admin/${siteName.value}/services` },
        {icon: 'list_alt', title: 'Produtos', position: 5, marked: false, url: `admin/${siteName.value}/agenda` },
        {icon: 'wallet', title: 'Caixa', position: 6, marked: false, url: `admin/${siteName.value}/agenda` },
        
    ]);

    const changeMakred = (i: number) => {
        console.log('Vai mudar a cor do fundo: ', i);
        LocalStorage.set("lastCheck", i);

        links.map(link => {
            if(link.position === i) link.marked = true;
            if(link.position !== i) link.marked = false;
        });
    };

    onMounted(() => {
        links.map(link => {
            if(link.position === LocalStorage.getItem("lastCheck")) link.marked = true;
            if(link.position !== LocalStorage.getItem("lastCheck")) link.marked = false;
        });
    }); 
    
</script>