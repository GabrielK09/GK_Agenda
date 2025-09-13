<template>
    <div class="q-pa-md">
        <q-date
            v-model="dateForCalender"
            landscape
            minimal
            :locale="myLocale"
            :events="props.activeScheduling.map(date => dayjs(date).format('YYYY/MM/DD'))"
            event-color="blue"
        />
    </div>
</template>

<script setup lang="ts">
    import { computed, ref, watch } from 'vue';
    import dayjs, { Dayjs } from 'dayjs';
    import isBetween from 'dayjs/plugin/isBetween';
    
    const props = defineProps<{
        activeScheduling: string[]
    }>();

    const emits = defineEmits([
        'changeDate'
    ]);
    
    const myLocale = {
        days: 'Domingo_Segunda_Terça_Quarta_Quinta_Sexta_Sábado'.split('_'),
        daysShort: 'Dom_Seg_Ter_Qua_Qui_Sex_Sáb'.split('_'),
        months: 'Janeiro_Fevereiro_Março_Abril_Maio_Junho_Julho_Agosto_Setembro_Outubro_Novembro_Dezembro'.split('_'),
        monthsShort: 'Jan_Fev_Mar_Abr_Mai_Jun_Jul_Ago_Set_Out_Nov_Dez'.split('_'),
        firstDayOfWeek: 1,
        format24h: true,
        pluralDay: 'dias'
    };
    
    let dateForCalender = ref<string|Dayjs>(dayjs().format('YYYY/MM/DD'));

    const formatDate = computed(() => {
        return dayjs(dateForCalender.value).format('DD/MM/YYYY');
    }); 

    watch(formatDate, () => {
        emits('changeDate', formatDate.value);
    });
</script>
