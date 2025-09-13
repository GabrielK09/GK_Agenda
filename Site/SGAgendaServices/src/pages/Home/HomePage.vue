<template>
    <div class="">
        <div class="">
            <h1>Produtos com categoria</h1>
            <div v-for="category in categories" class="text-xl mt-4 ">
                <span class="border p-1">{{ category.name }}</span>

                <div v-if="servicesByCategory.length > 0">
                    <div v-for="service in servicesByCategory">
                        {{ service }}

                    </div>
                </div>
            </div>
        </div>    

        <div class="">
            <h1>Produtos sem categoria</h1>
            <div v-if="servicesNotHasCategory.length > 0">
                <div class="">
                    <div v-for="service in servicesNotHasCategory">
                        {{ service }}
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

    interface Service {
        categoryCode: number,
        category: string,
        name: string,
        description: string,
        price: number
    };

    interface Categories {
        categoryCode: number,
        name: string,
        description: string,
    };

    let servicesNotHasCategory = ref<Service[]>([]);
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

        let test: any[] = [];
        test = categories.value.map(category => {
            console.log('Aqui 1');
            console.log(category);
            
            servicesByCategory.value.filter(service => service.categoryCode === category.categoryCode );
        });

        console.log(test);
        
    };

    onMounted(() => {
        const url = document.URL.split('/');
        const urlName = url[url.length-1] as string;
        
        whatURLName.value = urlName;
        getData(urlName);
         
    }); 
</script>