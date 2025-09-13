<template>
    <q-page padding>
        <section class="text-xl">
            <div
                class="m-4"  
            >
                <div class="bg-white">
                    <div class="w-max border p-2">
                        <div class="grid grid-cols-3">
                            <q-btn no-caps color="primary" label="Dia" @click="changeShowAgenda('day')" class="mr-4"/>
                            <q-btn no-caps color="primary" label="Semana" @click="changeShowAgenda('week')" class="mr-4"/>
                            <q-btn no-caps color="primary" label="Mês" @click="changeShowAgenda('month')" class="mr-4"/>

                        </div>            
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="p-5">
                            <div v-if="showAgenda.isInDay">
                                <AgendaDay/>

                            </div>
                            <div v-else-if="showAgenda.isInWeek">
                                <AgendaWeek/>

                            </div>
                            <div v-else-if="showAgenda.isInMonth">
                                <AgendaMonth/>

                            </div>
                        </div>
                        
                        <div class="">
                             <div style="display: flex; justify-content: center">
                                <div
                                    style="
                                    max-width: 280px;
                                    width: 100%;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    border: 1px solid #ccc;
                                    border-radius: 4px;
                                    padding: 10px;
                                    "
                                >
                                    <div style="width: 100%; display: flex; justify-content: space-evenly">
                                    <div style="width: 50%; display: flex; justify-content: space-between">
                                        <span class="q-button" style="cursor: pointer; user-select: none" @click="onPrev"
                                        >&lt;</span
                                        >
                                        {{ formattedMonth }}
                                        <span class="q-button" style="cursor: pointer; user-select: none" @click="onNext"
                                        >&gt;</span
                                        >
                                    </div>
                                    <div style="width: 30%; display: flex; justify-content: space-between">
                                        <span class="q-button" style="cursor: pointer; user-select: none" @click="addToYear(-1)"
                                        >&lt;</span
                                        >
                                        {{ selectedYear }}
                                        <span class="q-button" style="cursor: pointer; user-select: none" @click="addToYear(1)"
                                        >&gt;</span
                                        >
                                    </div>
                                    </div>

                                    <div style="display: flex; justify-content: center; align-items: center; flex-wrap: nowrap">
                                    <div style="display: flex; max-width: 280px; width: 100%">
                                        <q-calendar-month
                                        ref="calendar"
                                        v-model="selectedDate"
                                        mini-mode
                                        use-navigation
                                        no-active-date
                                        hoverable
                                        focusable
                                        :focus-type="['date', 'weekday']"
                                        :min-weeks="6"
                                        animated
                                        @change="onChange"
                                        @moved="onMoved"
                                        @click-date="onClickDate"
                                        @click-day="onClickDay"
                                        @click-workweek="onClickWorkweek"
                                        @click-head-workweek="onClickHeadWorkweek"
                                        @click-head-day="onClickHeadDay"
                                        />
                                    </div>
                                    </div>
                                </div>
    </div>
                        </div>
                    </div>
                </div>

                <!--div class="flex">
                    <div class="grid grid-cols-3">
                        <q-btn no-caps color="primary" label="Dia" @click="changeShowAgenda('day')" class="mr-4"/>
                        <q-btn no-caps color="primary" label="Semana" @click="changeShowAgenda('week')" class="mr-4"/>
                        <q-btn no-caps color="primary" label="Mês" @click="changeShowAgenda('month')" class="mr-4"/>

                    </div>            
                </div>    

                <div v-if="showAgenda.isInDay">
                    <AgendaDay/>

                </div>
                <div v-else-if="showAgenda.isInWeek">
                    <AgendaWeek/>

                </div>
                <div v-else-if="showAgenda.isInMonth">
                    <AgendaMonth/>

                </div-->        
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
import {
  QCalendarMonth,
  addToDate,
  parseTimestamp,
  today,
  Timestamp,
} from '@quasar/quasar-ui-qcalendar'
import '@quasar/quasar-ui-qcalendar/index.css'
    import AgendaDay from '../Agendas/Day/AgendaDay.vue';
    import AgendaWeek from '../Agendas/Week/AgendaWeek.vue';
    import AgendaMonth from '../Agendas/Month/AgendaMonth.vue';
    import { ref, computed  } from 'vue';

    interface ShowAgenda {
        isInDay: boolean,
        isInWeek: boolean,
        isInMonth: boolean,
    };

    const showAgenda = ref<ShowAgenda>({
        isInDay: true,
        isInWeek: false,
        isInMonth: false
    }); 


    const changeShowAgenda = (agendaType: string) => {
        switch (agendaType) {
            case 'day':
                showAgenda.value = {
                    isInDay: true,
                    isInMonth: false,
                    isInWeek: false,
                };  
                break;

            case 'week':
                showAgenda.value = {
                    isInDay: false,
                    isInWeek: true,
                    isInMonth: false,
                };  
                break;

            case 'month':
                showAgenda.value = {
                    isInDay: false,
                    isInWeek: false,
                    isInMonth: true,
                };  
                break;
        
            default:
                showAgenda.value = {
                    isInDay: true,
                    isInMonth: false,
                    isInWeek: false,
                };  
                break;
        };
    };

    const calendar = ref<QCalendarMonth>(),
  selectedDate = ref(today()),
  selectedYear = ref(new Date().getFullYear()),
  locale = ref('en-US')

const formattedMonth = computed(() => {
  const date = new Date(selectedDate.value)
  const formatter = monthFormatter()
  return formatter ? formatter.format(date) : ''
})

function monthFormatter() {
  try {
    return new Intl.DateTimeFormat(locale.value || undefined, {
      month: 'long',
      timeZone: 'UTC',
    })
  } catch {
    //
  }
}

function addToYear(amount: number) {
  // parse current date to timestamp
  let ts = parseTimestamp(selectedDate.value)
  if (ts) {
    // add specified amount of days
    ts = addToDate(ts, { year: amount })
    // re-assign values
    selectedDate.value = ts.date
    selectedYear.value = ts.year
  }
}

function onToday() {
  if (calendar.value) {
    calendar.value.moveToToday()
  }
}
function onPrev() {
  if (calendar.value) {
    calendar.value.prev()
  }
}
function onNext() {
  if (calendar.value) {
    calendar.value.next()
  }
}
function onMoved(data: Timestamp) {
  console.info('onMoved', data)
}
function onChange(data: { start: Timestamp; end: Timestamp; days: Timestamp[] }) {
  console.info('onChange', data)
}
function onClickDate(data: Timestamp) {
  console.info('onClickDate', data)
}
function onClickDay(data: Timestamp) {
  console.info('onClickDay', data)
}
function onClickWorkweek(data: Timestamp) {
  console.info('onClickWorkweek', data)
}
function onClickHeadDay(data: Timestamp) {
  console.info('onClickHeadDay', data)
}
function onClickHeadWorkweek(data: Timestamp) {
  console.info('onClickHeadWorkweek', data)
}
</script>