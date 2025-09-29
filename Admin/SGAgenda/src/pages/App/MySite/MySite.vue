<template>
    <q-page padding>
        <section class="text-xl">
            <div
                class="m-2"
            >
                <div class="flex justify-between">
                    <h2 class="text-gray-600 m-2">Meu Site</h2>

                </div>

                <div class="ml-2 p-4 bg-white rounded-md">
                    <span class="url">
                        <q-field 
                            label="URL do Site:" 
                            stack-label
                            borderless
                            outlined
                        >
                            <a target="_blank" :href="siteURL">{{ siteURL }}</a>
                            
                            <template v-slot:append>
                                <q-btn 
                                    icon="content_copy"
                                    flat
                                    rounded
                                    :data-clipboard-text="siteURL"
                                    class="btn"
                                    @click="fnClipBoard"
                                />
                            </template>
                        </q-field>
                    </span>

                    <div class="mt-4 flex options">
                        <q-input 
                            v-model="site.contactPhone" 
                            type="text" 
                            class="phone_input"
                            label="Telefone"
                            outlined
                            stack-label
                            mask="(##) #####-####"
                        />

                        <q-input 
                            v-model="site.slogan" 
                            type="text" 
                            class="slogan_input"
                            label="Slogan"
                            outlined
                            stack-label
                        />
                    </div>

                    <q-separator color="grey-13" class="mt-4"/>

                    <div class="persona m-2">
                        <h3 class="mt-2 ml-.5">Personalização</h3>

                        <div class="colors flex">
                            {{ site.themeColor }}
                            <q-select 
                                v-model="site.themeColor" 
                                :options="[
                                    'Tema claro',
                                    'Tema escuro'
                                ]" 

                                display-value-html
                                label="Tema do site" 
                                class="m-4 w-44"
                                stack-label
                            />

                            <div
                                class="rounded-3xl h-12 w-12 picker cursor-pointer"
                                :class="{
                                    'border-black border': site.siteColor === '#ffffff'
                                }"
                                :style="`background-color: ${site.siteColor}`"
                                @click="showPickColor = !showPickColor"
                            >
                            </div>
                            <q-color 
                                v-show="showPickColor"
                                v-model="site.siteColor" 
                                inline 
                                no-header-tabs
                                no-header
                                class="m-4 w-44"
                            />
                        </div>
                    </div>
                    <q-btn color="primary"  label="OK" @click="saveSiteSettings" />
                </div>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
    import { LocalStorage, useQuasar } from 'quasar';
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';
    import camelcaseKeys from 'camelcase-keys';
    import clipBoard from 'src/utils/clipboard';

    interface SiteSettings {
        ownerCode: number,
        slogan: string,
        contactPhone: string,
        themeColor: string,
        siteColor: string,
        
    };

    const $q = useQuasar();
    const ownerCode = LocalStorage.getItem("ownerCode") as number;

    const site = ref<SiteSettings>({
        ownerCode: ownerCode,
        slogan: '',
        contactPhone: '',
        themeColor: '',
        siteColor: '#ffffff',

    });

    let siteURL = ref<string>('');
    let showPickColor = ref<boolean>(false);

    const getURL = async () => {
        const res = await api.get(`/site/get-url/${ownerCode}`);
        const data = camelcaseKeys(res.data.data, { deep: true });

        siteURL.value = data.siteUrl;
        getSiteSettings();
    };  

    const getSiteSettings = async () => {
        const url = siteURL.value.split('/');
        const urlName = url[url.length-1] as string;
        
        const res = await api.get(`/site/get-site-settings/${urlName}`);
        const data: SiteSettings = camelcaseKeys(res.data.data, { deep: true });

        site.value = {
            contactPhone: data.contactPhone ?? site.value.contactPhone,
            ownerCode: data.ownerCode,
            siteColor: data.siteColor ?? site.value.siteColor,
            slogan: data.slogan ?? site.value.slogan,
            themeColor: data.themeColor === '#ffffff' ? 'Tema claro' : 'Tema escuro'
        };
    };  

    const saveSiteSettings = async () => {
        const payload: SiteSettings = {
            ownerCode: ownerCode,
            siteColor: site.value.siteColor,
            slogan: site.value.slogan,
            themeColor: site.value.themeColor === 'Tema claro' ? '#ffffff' : ' #000000',
            contactPhone: site.value.contactPhone,
        };

        console.log('payload: ', payload);
        
        const res = await api.post('/site/save-site-settings', payload);
        const data = res.data;
        
        if(data.success)
        {
            $q.notify({
                color: 'green',
                message: data.message,
                position: 'top'
            });
            
            getSiteSettings();
        };
    };

    const fnClipBoard = async () => {
        if(await clipBoard())
        {
            $q.notify({
                color: 'green',
                message: 'URL copiada com sucesso!',
                position: 'top'
            });
        };
    };

    onMounted(() => {
        getURL();
        
    });
</script>   

<style lang="scss">
    @media (min-width: 1100px){
        .options {
            display: flex;
            justify-content: space-between;
            
            .phone_input {
                margin: 0 2rem 0 0;

            }

            .slogan_input {
                width: 81.4%;
            }
        }
    }    
    
    @media (max-width: 1280px){
        .options {
            display: flex;
            justify-content: space-between;

            .phone_input {
                width: 35%;
                margin: 0 1rem 0 0;

            }

            .slogan_input {
                width: 59%;
            }
        }
    }    
</style>