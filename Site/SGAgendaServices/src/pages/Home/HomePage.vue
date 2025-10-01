<template>
    <div class="flex justify-center">
        <div>
            <h1 
                class="text-center"
                :style="`color: ${textColor}`"
            >
                {{ slogan }}
            </h1>

            <div v-for="category in categories" class="text-xl mt-4 p-8">
                <span :style="`color: ${textColor}`" class="p-1 ml-4">{{ category.name }} - {{ category.categoryCode }}</span>
                <div 
                    v-for="service in servicesHasCategory"
                    class="mt-4 rounded-md"
                    :style="`background-color: ${colors.bgCardColor}`"
                > 
                    <div v-if="service.categoryCode === category.categoryCode" class="card p-12">
                        <div :style="`color: ${textColor}`"> 
                            <div class="">
                                <span>Serviço: {{service.name}}</span>
                                <br>
                                <span>{{ service.description }}</span>

                            </div>
                        
                            <div class="mt-4">
                                <span 
                                    class="rounded-md p-2 mr-4"
                                    :style="`background-color: ${colors.bgBtnColor}`"
                                >
                                    R$ {{ service.price.toFixed(2).toString().replace('.', ',') }}
                                </span> 
                                <span
                                    class="bg-gray-800 rounded-md p-2"
                                    :style="`background-color: ${colors.bgBtnColor}`"
                                >
                                    <q-avatar icon="schedule" />{{ service.duration }}
                                </span>

                                <q-btn
                                    no-caps
                                    outline
                                    icon="arrow_forward"
                                    class="ml-24"
                                    :style="`background-color: ${colors.bgBtnColor}`"
                                    :to="`/app.gkagenda/${whatURLName}/schedule/${service.serviceCode}`"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="servicesNotHasCategory.length > 0" class="mt-4 p-8">
                <span class="p-1 ml-4">Confira os demais serviços!</span>
                <div v-for="service in servicesNotHasCategory" class="mt-4 bg-gray-500 rounded-md  p-8">
                    <div :style="`color: ${textColor}`"> 
                        <div class="">
                            <span>Serviço: {{service.name}}</span>
                            <br>
                            <span>{{ service.description }}</span>
                        </div>
                    
                        <div class="mt-4">
                            <span class="bg-gray-800 rounded-md p-2 mr-4">R$ {{ service.price.toFixed(2).toString().replace('.', ',') }}</span> 
                            <span class="bg-gray-800 rounded-md p-2">
                                <q-avatar icon="schedule" />{{ service.duration }}
                            </span>

                            <q-btn
                                no-caps
                                outline
                                icon="arrow_forward"
                                class="ml-24"
                                :to="`/app.gkagenda/${whatURLName}/schedule/${service.serviceCode}`"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import { onMounted, ref } from 'vue';
    import camelcaseKeys from 'camelcase-keys';
    import { LocalStorage } from 'quasar';

    interface Service {
        categoryCode: number,
        category: string,
        serviceCode: number,
        name: string,
        description: string,
        price: number,
        duration: number
    };

    interface Categories {
        categoryCode: number,
        name: string,
        description: string,
    };

    interface SiteSettings {
        themeColor: string,
        siteColor: string,
        bgCardColor: string,
        bgBtnColor: string,
        contactPhone: string,
        slogan: string,
    };

    interface SiteColors {
        bgCardColor: string,
        bgBtnColor: string
    };

    const colors = ref<SiteColors>({
        bgBtnColor: '',
        bgCardColor: '' 
    });

    const siteSettings = ref<SiteSettings>({
        themeColor: '',
        siteColor: '',
        bgCardColor: '',
        bgBtnColor: '',
        contactPhone: '',
        slogan: '',
    });

    let themeColor = ref<string>('');
    let textColor = ref<string>('');

    let servicesNotHasCategory = ref<Service[]>([]);
    let servicesHasCategory = ref<Service[]>([]);
    let servicesByCategory = ref<Service[]>([]);

    let services = ref<Service[]>([]);
    let categories = ref<Categories[]>([]);
    
    let whatURLName = ref<string>('');
    let slogan = ref<string>(LocalStorage.getItem('slogan') as string);

    const getData = async (urlName: string) => {

        const resService = await api.get(`/site/get-services/${urlName}`);
        const resCategory = await api.get(`/site/get-categories/${urlName}`);

        const resServiceData = camelcaseKeys(resService.data.data, { deep: true });
        const resCategoryData = camelcaseKeys(resCategory.data.data, { deep: true });

        services.value = resServiceData;
        categories.value = resCategoryData;

        servicesNotHasCategory.value = services.value.filter(service => service.categoryCode === null );
        servicesHasCategory.value = services.value.filter(service => service.categoryCode !== null);

        servicesByCategory.value = servicesHasCategory.value.map(service => {
            return service;
        });

        console.log(servicesByCategory.value);
    };

    const getSiteSettings = async (urlName: string) => {
        const res = await api.get(`/site/get-site-settings/${urlName}`);
        const data: SiteSettings = camelcaseKeys(res.data.data, { deep: true });
        console.log('getSiteSettings:', data);
        
        siteSettings.value = data;
        
        LocalStorage.set("themeColor", data.themeColor);
        LocalStorage.set("siteColor", data.siteColor);
        LocalStorage.set("contactPhone", data.contactPhone);
        LocalStorage.set("slogan", data.slogan);
        LocalStorage.set("bgBtnColor", data.bgBtnColor);
        LocalStorage.set("bgCardColor", data.bgCardColor);

        colors.value = {
            bgBtnColor: data.bgBtnColor,
            bgCardColor: data.bgCardColor
        };
    
        themeColor.value = data.themeColor;

        const qApp = document.getElementById('q-app');

        if(qApp !== null)
        {   
            qApp.style.backgroundColor = themeColor.value; 

            if(themeColor.value === '#222831')
            {   
                textColor.value = '#ffffff';
                
            };
        };  
    };

    onMounted(() => {
        const url = document.URL.split('/');
        const urlName = url[url.length-1] as string;
        LocalStorage.setItem("urlName", urlName);

        whatURLName.value = urlName;
        console.log(whatURLName.value);
        getData(whatURLName.value);
        getSiteSettings(whatURLName.value);
        
    }); 
</script>