<template>
  <div class="p-4">
    <PerformancesTable
      :performances="performances"
      :statuses="statuses"
      :role="$page.props.role"
      @status-updated="handleStatusUpdated"
    />
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/inertia-vue3'
import PerformancesTable from '@/components/PerformancesTable.vue'
export default {
  props: {
    performances: Array,
    statuses: Array,
  },
  components: {
    Link,
    PerformancesTable,
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString() // Форматирование даты
    },
    async updateStatus(performanceId, statusId) {
      try {
        await this.$inertia.post(`/performances/${performanceId}/status`, {
          status_id: statusId,
        })
        this.$emit('status-updated')
      } catch (error) {
        console.error('Ошибка при обновлении статуса:', error)
      }
    },
  },
}
</script>
