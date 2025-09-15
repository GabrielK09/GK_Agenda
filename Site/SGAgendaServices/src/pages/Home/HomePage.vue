<template>
    <div class="flex justify-center mt-24">
        <div class="">
            <div v-for="category in categories" class="text-xl mt-4 p-8">
                <span class="p-1 ml-4">{{ category.name }} - {{ category.categoryCode }}</span>
                <div v-for="service in servicesHasCategory"class="mt-4 bg-gray-500 rounded-md"> 
                    <div v-if="service.categoryCode === category.categoryCode" class="card p-12">
                        <div class="text-white"> 
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
                                    :to="`/schedule/${service.serviceCode}`"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="servicesNotHasCategory.length > 0" class="mt-4 p-8">
                <span class="p-1 ml-4">Confira os demais serviços!</span>
                <div v-for="service in servicesNotHasCategory" class="mt-4 bg-gray-500 rounded-md  p-8">
                    <div class="text-white"> 
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
                                :to="`/schedule/${service.serviceCode}`"
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

    let servicesNotHasCategory = ref<Service[]>([]);
    let servicesHasCategory = ref<Service[]>([]);
    let servicesByCategory = ref<Service[]>([]);

    let services = ref<Service[]>([]);
    let categories = ref<Categories[]>([]);
    
    let whatURLName = ref<string>('');

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

    onMounted(() => {
        const url = document.URL.split('/');
        const urlName = url[url.length-1] as string;
        
        LocalStorage.set("urlName", urlName);
        whatURLName.value = urlName;

        getData(urlName);
         
    }); 
</script>

<style>
    body {
        background-color: #f5f5f5;
    }
</style>