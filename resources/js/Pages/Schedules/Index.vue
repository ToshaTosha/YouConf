<template>
  <div class="p-4">
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      timeSlots: this.generateTimeSlots(),
      schedules: [
        {
          id: 1,
          application: {
            id: 1,
            title: 'Opening Ceremony',
            section_id: 1,
            section: {
              id: 1,
              name: 'Main Stage',
              color: '#FF5733',
            },
          },
          date: '2025-02-16',
          start_time: '10:00',
          duration: 60,
          end_time: '11:00',
          location: 'Main Hall',
        },
        {
          id: 2,
          application: {
            id: 2,
            title: 'Tech Talk: AI Innovations',
            section_id: 2,
            section: {
              id: 2,
              name: 'Tech Stage',
              color: '#33FF57',
            },
          },
          date: '2025-02-16',
          start_time: '11:00',
          duration: 45,
          end_time: '11:45',
          location: 'Tech Room',
        },
        {
          id: 3,
          application: {
            id: 3,
            title: 'Tech Talk: AI Innovations 2',
            section_id: 2,
            section: {
              id: 2,
              name: 'Tech Stage',
              color: '#33FF57',
            },
          },
          date: '2025-02-16',
          start_time: '12:00',
          duration: 30,
          end_time: '12:30',
          location: 'Tech Room',
        },
      ],
      sections: [
        {
          id: 1,
          name: 'Main Stage',
          color: '#FF5733',
        },
        {
          id: 2,
          name: 'Tech Stage',
          color: '#33FF57',
        },
        {
          id: 3,
          name: 'Workshop Room',
          color: '#3357FF',
        },
      ],
    }
  },
  methods: {
    generateTimeSlots() {
      const slots = []
      for (let hour = 8; hour < 20; hour++) {
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
      const event = this.schedules.find(
        (schedule) =>
          schedule.application.section_id === sectionId &&
          timeSlot >= schedule.start_time &&
          timeSlot < schedule.end_time,
      )
      return event ? { backgroundColor: event.application.section.color } : {}
    },
    getEvent(sectionId, timeSlot) {
      const event = this.schedules.find(
        (schedule) =>
          schedule.application.section_id === sectionId &&
          timeSlot >= schedule.start_time &&
          timeSlot < schedule.end_time,
      )
      return event ? event.application.title : ''
    },
    getRowSpan(sectionId, timeSlot, index) {
      const event = this.schedules.find(
        (schedule) =>
          schedule.application.section_id === sectionId &&
          timeSlot >= schedule.start_time &&
          timeSlot < schedule.end_time,
      )
      if (event) {
        // Проверяем, является ли текущая ячейка началом события
        if (timeSlot === event.start_time) {
          const startIndex = this.timeSlots.indexOf(event.start_time)
          const endIndex = this.timeSlots.indexOf(event.end_time)
          return endIndex - startIndex
        } else {
          // Если это не начало события, возвращаем 0, чтобы ячейка была скрыта
          return 0
        }
      }
      return 1
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
