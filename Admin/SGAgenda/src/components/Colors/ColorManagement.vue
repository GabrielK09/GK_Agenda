<template>
    <div
        v-if="!showChangeTextColor"
        class="border absolute z-50 bg-white p-2 rounded-lg w-80"
        :class="{
            'right-44': props.label === 'lb1',
            'right-96': props.label === 'lb2' || props.label === 'card',
        }"
    >
        <div
            class="mb-2 flex justify-center"
        >                        
            <span class="ml-4 mr-4">{{ descLabel }}</span>
            
            <div>
                <q-btn
                    class="ml-4 mb-2 w-4 h-4" 
                    color="primary" 
                    icon="close"
                    @click="emits('close', true)" 
                />

                <q-btn
                    class="ml-4 mb-2" 
                    color="primary" 
                    no-caps
                    outline
                    label="Cor do texto"
                    @click="changeTextColor()"
                    
                />
            </div>
        </div>

        <q-color 
            no-header
            v-model="colors.color" 

        />
    </div>
    
    <div
        v-if="showChangeTextColor"
        class="border absolute z-50 bg-white p-2 rounded-lg w-56"
        :class="{
            'right-96': props.label === 'lb1',
            'left-96': props.label === 'lb2' || props.label === 'card',

        }"
    >
        <div class="mb-2 flex justify-center">
            <span class="ml-4 mr-4">Cor do texto</span>
            
            <div>
                <q-btn
                    class="ml-4 mb-2 w-4 h-4" 
                    color="primary" 
                    icon="close"
                    @click="emits('close', true)" 
                />

                <q-btn
                    class="ml-4 mb-2" 
                    color="primary" 
                    no-caps
                    outline
                    label="Cor do elemento"
                    @click="showChangeTextColor = !showChangeTextColor"
                    
                />
            </div>
        </div>

        <q-color 
            no-header
            v-model="colors.textColor" 

        />
    </div>
</template>

<script setup lang="ts">
    import { defineEmits, defineProps, onMounted, ref, watch } from 'vue';
    
    interface ReturnColors {
        label: string,
        textColor: string,
        color: string
    };

    type SiteColors = {
        color: string,
        textColor: string
    };

    const props = defineProps<{
        label: string
        
    }>();

    const emits = defineEmits([
        'chagenColor',
        'close'
        
    ]);

    let descLabel = ref<string>('');
    let showChangeTextColor = ref<boolean>(false);

    const colors = ref<SiteColors>({
        color: '#000',
        textColor: '#000',
        
    });

    watch(colors.value, (newColor) => {
        console.log(newColor);
        
        commit();
    });

    const showChangeColor = () => {
        switch (props.label) {
            case 'card':
                descLabel.value = 'Cor do fundo em edição';
                break;
        
            case 'btn1':
                descLabel.value = 'Cor do botão de seleção do serviço em edição';
                break

            case 'lb1':
                descLabel.value = 'Cor do valor em edição';
                break

            case 'lb2':
                descLabel.value = 'Cor da duração em edição';

                break
            default:
                break;
        };
    };

    const commit = () => {
        const payload: ReturnColors = {
            color: colors.value.color,
            textColor: colors.value.textColor,
            label: props.label
        };

        emits('chagenColor', payload);
    };

    const changeTextColor = () => {
        showChangeTextColor.value = !showChangeTextColor.value;
        
    };

    onMounted(() => {
        showChangeColor();

    });
</script>