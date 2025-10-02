<template>
    <div
        v-if="!showChangeTextColor"
        id="draggableWindow"
        class="border absolute z-50 bg-white p-2 rounded-lg w-80"
        :class="{
            'right-44': props.label === 'lb1',
            'right-96': props.label === 'lb2' || props.label === 'card',

        }"
    >
        <div
            id="windowHeader"
            class="mb-2"
        >                        
            <span class="ml-4 mr-4">{{ descLabel }}</span>
            
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
                @click="changeTextColor('txtlb1')"
                
            />
        </div>

        <q-color 
            no-header
            v-model="colors.color" 

        />
    </div>
    
    <div
        v-if="showChangeTextColor"
        class="absolute z-50 bg-white p-2 rounded-lg w-52"
        :class="{
            'right-96': props.label === 'lb1',
            'left-96': props.label === 'lb2' || props.label === 'card',

        }"
    >
        <div class="mb-2">                        
            <span class="ml-4 mr-4">Cor do texto</span>
            
            <q-btn
                class="ml-4 mb-2" 
                color="primary" 
                no-caps
                outline
                label="Cor do elemento"
                @click="showChangeTextColor = !showChangeTextColor"
                
            />

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

    const changeTextColor = (label: string) => {
        showChangeTextColor.value = !showChangeTextColor.value;
        switch (label) {
            case 'txtlb1':
                break;
                
            default:
                break;
        };
    };

    onMounted(() => {
        showChangeColor();

    });

    /*
    const draggableWindow = document.getElementById('draggableWindow');
    const windowHeader = document.getElementById('windowHeader');

    let isDragging = false;
    let offSetX, offSetY;

    if(draggableWindow)
    {
        windowHeader?.addEventListener('mousedown', (e) => {
            isDragging = true;
            offSetX = e.clientX - draggableWindow?.getBoundingClientRect().left;
            offSetY = e.clientY - draggableWindow?.getBoundingClientRect().top;
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            
            draggableWindow.style.left = `${e.clientX - offSetX}px`;
            draggableWindow.style.top = `${e.clientX - offSetY}px`;
        });

        document.addEventListener('mouseup', (e) => {
            isDragging = false;
            draggableWindow.style.cursor = 'grab';
        });
    };  */
</script>