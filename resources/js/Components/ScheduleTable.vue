<template>
  <!-- Общая сетка для времени и событий -->
  <div
    className="col-start-1 col-end-5 gap-4 grid grid-rows-[repeat(48,minmax(0,1fr))]"
    :style="{ gridTemplateColumns: `50px repeat(${sections.length}, 1fr)` }"
  >
    <!-- Заголовки категорий -->
    <div className="col-start-1 col-end-2"></div>
    <div
      v-for="(section, sectionIndex) in sections"
      :key="sectionIndex"
      className="text-center font-bold p-2"
    >
      {{ section.name }}
    </div>
    <!-- Время -->
    <div
      v-for="(time, index) in times"
      :key="index"
      className="text-center pr-2"
      :style="{ gridRow: `${index + 2} / span 1` }"
    >
      {{ time }}
    </div>

    <!-- События -->
    <div
      v-for="event in currentEvents"
      :key="event.name"
      :className="`bg-blue-500 p-2 rounded-lg`"
      :style="{
        gridColumn: `${
          sections.findIndex((section) => section.id === event.section_id) + 2
        }`,
        gridRow: `${event.start + 1} / ${event.end + 2}`,
      }"
    >
      {{ event.application_title }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    sections: Array,
    events: Array,
  },
  data() {
    return {
      times: this.generateTimes(),
      categories: ['Work', 'Personal', 'Meetings'], // Категории событий
      //   events: [
      //     {
      //       name: 'Event 1',
      //       startTime: '09:00',
      //       endTime: '09:30',
      //       category: 'Work',
      //     },
      //     {
      //       name: 'Event 1.2',
      //       startTime: '09:30',
      //       endTime: '10:00',
      //       category: 'Work',
      //     },
      //     {
      //       name: 'Event 2',
      //       startTime: '10:00',
      //       endTime: '11:00',
      //       category: 'Personal',
      //     },
      //     {
      //       name: 'Event 3',
      //       startTime: '11:30',
      //       endTime: '12:00',
      //       category: 'Meetings',
      //     },
      //     {
      //       name: 'Event 3',
      //       startTime: '13:30',
      //       endTime: '15:00',
      //       category: 'Meetings',
      //     },
      //   ],
    }
  },
  methods: {
    generateTimes() {
      const times = []
      for (let hour = 8; hour <= 20; hour++) {
        for (let minute = 0; minute < 60; minute += 15) {
          const time = `${hour
            .toString()
            .padStart(2, '0')}:${minute.toString().padStart(2, '0')}`
          times.push(time)
        }
      }
      return times
    },
    timeToRowIndex(time) {
      // Преобразует время в номер строки
      const [hour, minute] = time.split(':').map(Number)
      const totalMinutes = (hour - 8) * 60 + minute // 8:00 — начало отсчета
      return Math.floor(totalMinutes / 15) + 1 // +1, так как строки начинаются с 1
    },
  },
  computed: {
    currentEvents() {
      console.log('currentEvents', this.events[0])
      return this.events.map((event) => ({
        ...event,
        start: this.timeToRowIndex(event.start_time),
        end: this.timeToRowIndex(event.end_time),
      }))
    },
  },
}
</script>
