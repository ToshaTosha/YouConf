<template>
  <div>
    <DateTabs
      :dateKeys="dateKeys"
      :selectedDate="selectedDate"
      @date-selected="selectDate"
    />
    <ScheduleTable :sections="sections" :events="processedEvents" />
  </div>
</template>

<script>
import DateTabs from '@/components/DateTabs.vue'
import ScheduleTable from '@/components/ScheduleTable.vue'

export default {
  components: {
    DateTabs,
    ScheduleTable,
  },
  props: {
    sections: Array,
    schedules: Object,
  },
  data() {
    return {
      selectedDate: Object.keys(this.schedules)[0],
      processedEvents: [],
    }
  },
  computed: {
    dateKeys() {
      return Object.keys(this.schedules) // Получите массив дат
    },
  },
  methods: {
    selectDate(date) {
      console.log(this.selectedDate)
      this.selectedDate = date
      this.updateProcessedEvents()
    },
    updateProcessedEvents() {
      const events = this.schedules[this.selectedDate] || []
      this.processedEvents = Object.values(events)
    },
  },
  mounted() {
    this.updateProcessedEvents()
  },
}
</script>
