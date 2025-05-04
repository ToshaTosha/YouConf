<template>
  <div>
    <!-- <DateTabs
      :dateKeys="dateKeys"
      :selectedDate="selectedDate"
      @date-selected="selectDate"
    /> -->
    <ScheduleTable :sections="sections" :events="processedEvents" />
  </div>
</template>

<script>
import DateTabs from '@/Components/DateTabs.vue'
import ScheduleTable from '@/Components/ScheduleTable.vue'

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
      this.selectedDate = date
      this.updateProcessedEvents()
    },
    updateProcessedEvents() {
      console.log(this.schedules)
      const events = this.schedules[this.selectedDate] || []
      this.processedEvents = Object.values(events)
    },
  },
  mounted() {
    this.updateProcessedEvents()
  },
}
</script>
