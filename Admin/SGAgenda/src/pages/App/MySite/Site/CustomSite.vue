<template>
    <q-btn  
        color="primary" 
        @click="changeTheme" 
        class="ml-4"
        flat
        :label="isDarkTheme ? 'Tema claro' : 'Tema escuro'"
    />

    <div 
        class="flex justify-center"
        :style="`background-color: ${colors.theme}`"
    >
        <div>
            <h1 
                class="text-center"
                :style="`color: ${colors.generalTextColor}`"
            >
                'Slogan de exemplo'
            </h1>

            <div v-for="category in categories" class="text-xl mt-4 p-8">
                <span :style="`color: ${colors.textColorLabel3}`" class="p-1 ml-4">{{ category.name }} - {{ category.categoryCode }}</span>
                <div 
                    v-for="service in servicesHasCategory"
                    class="mt-4 rounded-md"
                    :style="`background-color: ${colors.bgCardColor}`"
                > 
                    <div v-if="service.categoryCode === category.categoryCode" class="card p-12">
                        <div :style="`color: ${colors.textColorLabel3}`"> 
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
                <span 
                    class="p-1 ml-4"
                    :style="`color: ${colors.generalTextColor}`"
                    
                >   
                    Confira os demais serviços!
                </span>
                
                <div
                    class="absolute z-50 backdrop-blur-sm p-2 rounded-lg"
                    v-if="showChangeColors.showBgCardColor"
                >
                    <div class="mb-2 flex bg-white">
                        <q-btn color="primary" icon="close" @click="showChangeColors.showBgCardColor = !showChangeColors.showBgCardColor" />
                        <span class="mt-auto mb-auto ml-4 mr-4">Cor do fundo em edição</span>
                    </div>
                    <q-color 
                        no-header
                        v-model="colors.bgCardColor" 
                    />

                </div>

                <div
                    v-if="showChangeColors.showBgLabel1"
                    class="absolute z-50 bg-white p-2 rounded-lg right-96 w-52"
                >
                    <div class="mb-2">                        
                        <span class="ml-4 mr-4">Cor do valor em edição</span>
                        
                        <q-btn
                            class="ml-4 mb-2 w-4 h-4" 
                            color="primary" 
                            icon="close"
                            c
                            @click="showChangeColors.showBgLabel1 = !showChangeColors.showBgLabel1" 
                        />

                        <q-btn
                            class="ml-4 mb-2" 
                            color="primary" 
                            no-caps
                            outline
                            label="Cor do texto"
                            
                        />
                    </div>
                    <q-color 
                        no-header
                        v-model="colors.bgLabel1" 
                    />

                </div>

                <div
                    v-if="showChangeColors.showBgLabel2"
                    class="absolute z-50 bg-white p-2 rounded-lg right-96 w-52"
                >
                <div class="mb-2">
                        <span class="ml-4 mr-4">Cor da duração em edição</span>
                        <q-btn 
                            color="primary" 
                            icon="close"
                            outline
                            @click="showChangeColors.showBgLabel2 = !showChangeColors.showBgLabel2" 

                        />

                        <q-btn
                            class="ml-4 mb-2" 
                            color="primary" 
                            no-caps
                            outline
                            label="Cor do texto"
                            
                        />

                    </div>
                    <q-color 
                        no-header
                        v-model="colors.bgLabel2" 
                        
                    />
                    
                </div>

                <div
                    v-for="service in servicesNotHasCategory" 
                    class="mt-4 rounded-md p-8 cursor-pointer"                     
                    :style="`background-color: ${colors.bgCardColor}`"
                >
                    <div
                        @click="showChangeColor('card')" 
                        :style="`color: ${colors.textColorLabel3}`"
                        
                    > 
                        <div class="">
                            <span>Serviço: {{service.name}}</span>
                            <br>
                            <span>{{ service.description }}</span>
                        </div>
                    
                        <div class="mt-4">
                            <span 
                                class="rounded-md p-2 mr-4 cursor-pointer"
                                :style="`background-color: ${colors.bgLabel1}; color: ${colors.textColorLabel1}`"
                                @click.stop="showChangeColor('lb1')"
                            >
                                R$ {{ service.price.toFixed(2).toString().replace('.', ',') }}
                            </span> 

                            <span 
                                class="rounded-md p-2 mr-4 cursor-pointer"
                                :style="`background-color: ${colors.bgLabel2}`"
                                @click.stop="showChangeColor('lb2')"
                            >
                                <q-avatar icon="schedule" />{{ service.duration }}
                            </span>

                            <q-btn
                                no-caps
                                outline
                                icon="arrow_forward"
                                class="ml-24 bg-gra"
                                
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <pre>{{ colors }}</pre>
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

    interface SiteColors {
        theme: string,
        generalTextColor: string,
        bgCardColor: string,
        bgBtnColor1: string,
        bgLabel1: string,
        textColorLabel1: string,
        bgLabel2: string,
        textColorLabel2: string,
        textColorLabel3: string,
    };

    interface ShowEditSiteColors {
        showBgCardColor: boolean,
        showBgBtnColor1: boolean,
        showBgLabel1: boolean,
        showTextColorLabel1: boolean,
        showBgLabel2: boolean,
        showTextColorLabel2: boolean,
        showTextColorLabel3: boolean,
    };

    const colors = ref<SiteColors>({
        theme: '#fff',
        generalTextColor: '#fff',
        textColorLabel3: '#fff',
        bgBtnColor1: '',
        bgLabel1: '#9ca3af',
        textColorLabel1: '#fff',
        bgLabel2: '#9ca3af',
        textColorLabel2: '#fff',
        bgCardColor: '#ccc' 
    });

    const showChangeColors = ref<ShowEditSiteColors>({
        showBgBtnColor1: false,
        showBgLabel1: false,
        showTextColorLabel1: false,
        showBgLabel2: false,
        showTextColorLabel2: false,
        showTextColorLabel3: false,
        showBgCardColor: false
    });

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

    let isDarkTheme = ref<boolean>(false);
    
    const showChangeColor = (label: string) => {
        switch (label) {
            case 'card':
                showChangeColors.value = {
                    showBgCardColor: true,
                    showBgBtnColor1: false,
                    showBgLabel1: false,
                    showTextColorLabel1: false,
                    showBgLabel2: false,
                    showTextColorLabel2: false,
                    showTextColorLabel3: false,
                };

                break;
        
            case 'btn1':
                showChangeColors.value = {
                    showBgCardColor: false,
                    showBgBtnColor1: true,
                    showBgLabel1: false,
                    showTextColorLabel1: false,
                    showBgLabel2: false,
                    showTextColorLabel2: false,
                    showTextColorLabel3: false,
                };

                break

            case 'lb1':
                showChangeColors.value = {
                    showBgCardColor: false,
                    showBgBtnColor1: false,
                    showBgLabel1: true,
                    showTextColorLabel1: false,
                    showBgLabel2: false,
                    showTextColorLabel2: false,
                    showTextColorLabel3: false,
                };

                break

            case 'lb2':
                 showChangeColors.value = {
                    showBgCardColor: false,
                    showBgBtnColor1: false,
                    showBgLabel1: false,
                    showTextColorLabel1: false,
                    showBgLabel2: true,
                    showTextColorLabel2: false,
                    showTextColorLabel3: false,
                };

                break
            default:
                break;
        };
    };

    const changeTheme = () => {
        isDarkTheme.value = !isDarkTheme.value;
        if(!isDarkTheme.value)
        {
            colors.value.theme = '#ffffff';
            colors.value.generalTextColor = '#000';
            
        };
        
        if(isDarkTheme.value)
        {
            colors.value.theme = '#222831';
            colors.value.generalTextColor = '#fff';

        };   
    };
</script>