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
            
            <ColorManagement
                v-if="showColorManagement"
                :label="selecetToEdit"
                @close="showColorManagement = !$event"
                @chagen-color="colorManagement($event)"
            />

            <div v-if="servicesNotHasCategory.length > 0" class="mt-4 p-8 relative">
                <span 
                    class="p-1 ml-4"
                    :style="`color: ${colors.generalTextColor}`"
                    
                >   
                    Confira os demais serviços!
                </span>                

                <div
                    v-for="service in servicesNotHasCategory" 
                    class="mt-4 rounded-md p-8 cursor-pointer"                     
                    :style="`background-color: ${colors.bgCardColor}`"
                >
                    <div
                        @click="showChangeColor('card')"
                        title="Clicando aqui o fundo será editado" 
                        :style="`color: ${colors.textCardColor1}`"
                        
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
                                :style="`background-color: ${colors.bgLabel2}; color: ${colors.textColorLabel2}`"
                                @click.stop="showChangeColor('lb2')"
                            >
                                <q-avatar icon="schedule" />{{ service.duration }}
                            </span>

                            <q-btn
                                no-caps
                                icon="arrow_forward"
                                class="ml-24 bg-gra"
                                :style="`background-color: ${colors.bgBtnColor1}; color: ${colors.bgBtnColor1 === '#000000' ? '#fff' : '#000'}`"
                                @click.stop="showChangeColor('btn1')"
                            />

                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <pre>{{ colors }}</pre>
    <pre>{{ returnEvent }}</pre>
</template>

<script setup lang="ts">
    import { api } from 'src/boot/axios';
    import ColorManagement from 'src/components/Colors/ColorManagement.vue';
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
        textCardColor1: string,
        bgCardColor: string,
        bgBtnColor1: string,
        bgBtnColor2: string,
        bgLabel1: string,
        textColorLabel1: string,
        bgLabel2: string,
        textColorLabel2: string,
        textColorLabel3: string,
    };

    interface ReturnColors {
        label: string,
        textColor: string,
        color: string
    };

    const colors = ref<SiteColors>({
        theme: '#fff',
        generalTextColor: '#fff',
        textColorLabel3: '#fff',
        bgBtnColor1: '',
        bgBtnColor2: '',
        bgLabel1: '#9ca3af',
        textColorLabel1: '#fff',
        bgLabel2: '#9ca3af',
        textColorLabel2: '#fff',
        textCardColor1: '',
        bgCardColor: '#ccc' 
        
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

    let selecetToEdit = ref<string>('');
    let showColorManagement = ref<boolean>(false);
    let isDarkTheme = ref<boolean>(false);

    let returnEvent = ref<ReturnColors>();

    const showChangeColor = (label: string) => {
        selecetToEdit.value = label;
        showColorManagement.value = !showColorManagement.value;
    };

    const colorManagement = (event: ReturnColors) => {
        returnEvent.value = event;
        switch (event.label) {
            case 'card':
                colors.value.bgCardColor = event.color;
                colors.value.textCardColor1 = event.textColor;

                break;
        
            case 'btn1':
                colors.value.bgBtnColor1 = event.color;
                //colors.value.
                break

            case 'btn2':
                colors.value.bgBtnColor1 = event.color;
                break

            case 'lb1':
                colors.value.bgLabel1 = event.color;
                colors.value.textColorLabel1 = event.textColor;

                break

            case 'lb2':
                colors.value.bgLabel2 = event.color;
                colors.value.textColorLabel2 = event.textColor;
                
                break;
        
            default:
                break;
        }
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

    onMounted(() => {
        colors.value.theme === '#fff' ? colors.value.generalTextColor = '#000' : colors.value.generalTextColor = '#fff';
    });
</script>