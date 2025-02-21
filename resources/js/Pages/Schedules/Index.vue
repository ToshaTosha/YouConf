<template>
  <div class="p-4">
    <div class="flex mb-4">
      <button
        v-for="(schedules, date) in schedules"
        :key="date"
        @click="selectedDate = date"
        :class="[
          'px-4 py-2 mr-2',
          selectedDate === date ? 'bg-blue-500 text-white' : 'bg-gray-200',
        ]"
      >
        {{ date }}
      </button>
    </div>
    <table class="min-w-full bg-white border border-gray-200">
      <thead>
        <tr>
          <th>Time</th>
          <th v-for="section in sections" :key="section.id">
            {{ section.name }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(timeSlot, index) in timeSlots" :key="timeSlot">
          <td>{{ timeSlot }}</td>
          <td
            v-for="section in sections"
            :key="section.id"
            :style="getCellStyle(section.id, timeSlot)"
            :rowspan="getRowSpan(section.id, timeSlot, index)"
          >
            {{ getEvent(section.id, timeSlot) }}
          </td>
        </tr>
      </tbody>
    </table>
    {{ schedules }}
  </div>
</template>

<script>
export default {
  data() {
    return {
      timeSlots: this.generateTimeSlots(),
      selectedDate: Object.keys(this.schedules)[0],
    }
  },
  props: {
    schedules: Object,
    sections: Array,
  },
  methods: {
    generateTimeSlots() {
      const slots = []
      for (let hour = 7; hour < 21; hour++) {
        for (let minute = 0; minute < 60; minute += 15) {
          slots.push(
            `${String(hour).padStart(2, '0')}:${String(minute).padStart(
              2,
              '0',
            )}`,
          )
        }
      }
      return slots
    },
    getCellStyle(sectionId, timeSlot) {
      console.log(this.schedules[this.selectedDate])
      const event = this.schedules[this.selectedDate].find(
        (schedule) =>
          schedule.section_id === sectionId &&
          timeSlot >= schedule.start_time &&
          timeSlot <= schedule.end_time,
      )
      return event ? { backgroundColor: 'rgb(51 255 87 / 5%)' } : {}
    },
    getEvent(sectionId, timeSlot) {
      const event = this.schedules[this.selectedDate].find(
        (schedule) =>
          schedule.section_id === sectionId &&
          timeSlot >= schedule.start_time &&
          timeSlot <= schedule.end_time,
      )
      return event ? event.application_title : ''
    },
    getRowSpan(sectionId, timeSlot, index) {
      const event = this.schedules[this.selectedDate].find(
        (schedule) =>
          schedule.section_id === sectionId &&
          timeSlot >= schedule.start_time &&
          timeSlot <= schedule.end_time,
      )
      if (event) {
        // Проверяем, является ли текущая ячейка началом события
        if (timeSlot === event.start_time) {
          const startIndex = this.timeSlots.indexOf(event.start_time)
          const endIndex = this.timeSlots.indexOf(event.end_time)
          return endIndex - startIndex + 1
        } else {
          // Если это не начало события, возвращаем 0, чтобы ячейка была скрыта
          return 0
        }
      }
      return 1
    },
    selectDate(date) {
      this.selectedDate = date
    },
  },
}
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}
th,
td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: center;
}
td[rowspan='0'] {
  display: none;
}
</style>
