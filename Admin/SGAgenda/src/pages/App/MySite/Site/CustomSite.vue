<template>
    <q-btn  
        color="primary" 
        @click="changeTheme" 
        class="ml-4"
        flat
        :label="isDarkTheme ? 'Tema claro' : 'Tema escuro'"
    />

    <div class="flex justify-center">
        <div>
            <h1 
                class="text-center"
                :style="`color: ${textColor}`"
            >
                'Slogan de exemplo'
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
                                    :style="`background-color: ${colors.bgBtnColor1}`"
                                >
                                    R$ {{ service.price.toFixed(2).toString().replace('.', ',') }}
                                </span> 
                                <span
                                    class="bg-gray-800 rounded-md p-2"
                                    :style="`background-color: ${colors.bgBtnColor1}`"
                                >
                                    <q-avatar icon="schedule" />{{ service.duration }}
                                </span>

                                <q-btn
                                    no-caps
                                    outline
                                    icon="arrow_forward"
                                    class="ml-24"
                                    :style="`background-color: ${colors.bgBtnColor1}`"
                                    
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="servicesNotHasCategory.length > 0" class="mt-4 p-8 relative">
                <span class="p-1 ml-4">Confira os demais serviços!</span>
                
                <div
                    class="absolute z-50 backdrop-blur-sm p-2 rounded-lg"
                    v-if="changeColorCard"
                >
                    <div class="mb-2 flex bg-white">
                        <q-btn color="primary" icon="close" @click="changeColorCard = !changeColorCard" />
                        <span class="mt-auto mb-auto ml-4 mr-4">Cor do fundo em edição</span>
                    </div>
                    <q-color 
                        no-header
                        v-model="colors.bgCardColor" 
                    />

                </div>

                <div
                    v-for="service in servicesNotHasCategory" 
                    class="mt-4 bg-gray-500 rounded-md p-8 cursor-pointer" 
                    @click="showChangeColor('card')"
                    :style="`background-color: ${colors.bgCardColor}`"
                >

                    <div :style="`color: ${textColor}`"> 
                        <div class="">
                            <span>Serviço: {{service.name}}</span>
                            <br>
                            <span>{{ service.description }}</span>
                        </div>
                    
                        <div class="mt-4">
                            <div
                                class="absolute z-50 backdrop-blur-sm p-2 rounded-lg"
                                v-if="changeColorLabel1"
                            >
                                <div class="mb-2 flex bg-white">
                                    <q-btn color="primary" icon="close" @click="changeColorLabel1 = !changeColorLabel1" />
                                    <span class="mt-auto mb-auto ml-4 mr-4">Cor da label 1 em edição</span>
                                </div>
                                <q-color 
                                    no-header
                                    v-model="colors.bgCardColor" 
                                />

                            </div>
                            <span 
                                class="bg-orange-400 rounded-md p-2 mr-4 cursor-pointer"
                                @click="showChangeColor('lb1')"
                            >
                                R$ {{ service.price.toFixed(2).toString().replace('.', ',') }}
                            </span> 

                            <span class="bg-gray-800 rounded-md p-2">
                                <q-avatar icon="schedule" />{{ service.duration }}
                            </span>

                            <q-btn
                                no-caps
                                outline
                                icon="arrow_forward"
                                class="ml-24"
                                
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
        bgBtnColor1: string,
        bgBtnLabel1: string,
        bgBtnLabel2: string
    };

    const colors = ref<SiteColors>({
        bgBtnColor1: '',
        bgBtnLabel1: '',
        bgBtnLabel2: '',
        bgCardColor: '' 
    });

    let textColor = ref<string>('');
    let isDarkTheme = ref<boolean>(false);

    let servicesHasCategory = ref<Service[]>([]);

    let servicesNotHasCategory = ref<Service[]>([
        {
            category: '',
            categoryCode: 0,
            description: 'Serviço de exemplo',
            duration: 30,
            name: 'Serviço de exemplo',
            price: 10.00,
            serviceCode: 1
        }
    ]);

    let categories = ref<Categories[]>([]);

    let changeColorCard = ref<boolean>(false);
    let changeColorBtn1 = ref<boolean>(false);
    let changeColorLabel1 = ref<boolean>(false);
    let changeColorLabel2 = ref<boolean>(false);
    
    const showChangeColor = (local: string) => {
        switch (local) {
            case 'card':
                changeColorCard.value = true;
                changeColorBtn1.value = false;
                changeColorLabel1.value = false;
                changeColorLabel2.value = false;

                break;
        
            case 'btn1':
                changeColorBtn1.value = true;
                changeColorCard.value = false;
                changeColorLabel1.value = false;
                changeColorLabel2.value = false;

                break

            case 'lb1':
                changeColorLabel1.value = true;
                changeColorCard.value = false;
                changeColorBtn1.value = false;
                changeColorLabel2.value = false;

                break

            case 'lb2':
                changeColorLabel2.value = true;
                changeColorCard.value = false;
                changeColorBtn1.value = false;
                changeColorLabel1.value = false;

                break
            default:
                break;
        };
    };

    const changeTheme = () => {
        isDarkTheme.value = !isDarkTheme.value;
        const qApp = document.getElementById('q-app');

        if(qApp !== null)
        {   
            console.log();
            
            if(!isDarkTheme.value)
            {
                console.log('Tema claro');
                qApp.style.backgroundColor = '#ffffff';
                
            };
            
            if(isDarkTheme.value)
            {
                console.log('Tema escuro');
                qApp.style.backgroundColor = '#222831';

            };   
        };  
    };
</script>