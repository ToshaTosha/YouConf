<template>
  <!-- Общая сетка для времени и событий -->
  <div
    className="col-start-1 col-end-5 grid grid-rows-[repeat(48,minmax(0,1fr))]"
  >
    <!-- Заголовки категорий -->
    <div className="col-start-1 col-end-2"></div>
    <div
      v-for="(category, catIndex) in categories"
      :key="catIndex"
      className="text-center font-bold p-2"
    >
      {{ category }}
    </div>
    <!-- Время -->
    <div
      v-for="(time, index) in times"
      :key="index"
      className="text-right pr-2"
      :style="{ gridRow: `${index + 2} / span 1` }"
    >
      {{ time }}
    </div>

    <!-- События -->
    <div
      v-for="event in processedEvents"
      :key="event.name"
      :className="`bg-blue-500 p-2 rounded-lg`"
      :style="{
        gridColumn: `${categories.indexOf(event.category) + 2}`,
        gridRow: `${event.start + 1} / ${event.end + 2}`,
      }"
    >
      {{ event.name }}
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      times: this.generateTimes(),
      categories: ['Work', 'Personal', 'Meetings'], // Категории событий
      events: [
        {
          name: 'Event 1',
          startTime: '09:00',
          endTime: '09:30',
          category: 'Work',
        },
        {
          name: 'Event 2',
          startTime: '10:00',
          endTime: '11:00',
          category: 'Personal',
        },
        {
          name: 'Event 3',
          startTime: '11:30',
          endTime: '12:00',
          category: 'Meetings',
        },
        {
          name: 'Event 3',
          startTime: '13:30',
          endTime: '15:00',
          category: 'Meetings',
        },
      ],
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
    processedEvents() {
      // Преобразуем события, добавляя start и end на основе времени
      return this.events.map((event) => ({
        ...event,
        start: this.timeToRowIndex(event.startTime),
        end: this.timeToRowIndex(event.endTime),
      }))
    },
  },
}
</script>
