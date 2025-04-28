<template>
  <div class="p-4">
    <div class="flex justify-between mb-4">
      <div>
        <label for="statusFilter" class="mr-2">Фильтр по статусу:</label>
        <select
          v-model="selectedStatus"
          @change="filterPerformances"
          id="statusFilter"
          class="border rounded p-1"
        >
          <option value="">Все статусы</option>
          <option
            v-for="status in statuses"
            :key="status.id"
            :value="status.id"
          >
            {{ status.name }}
          </option>
        </select>
      </div>
    </div>

    <PerformancesTable
      :performances="filteredPerformances"
      :statuses="statuses"
      :role="$page.props.role"
      @status-updated="handleStatusUpdated"
    />
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import PerformancesTable from '@/Components/PerformancesTable.vue'

export default {
  props: {
    performances: Array,
    statuses: Array,
  },
  components: {
    Link,
    PerformancesTable,
  },
  data() {
    return {
      selectedStatus: '',
    }
  },
  computed: {
    filteredPerformances() {
      return this.performances.filter((performance) => {
        return (
          this.selectedStatus === '' ||
          performance.status_id === this.selectedStatus
        )
      })
    },
  },
  methods: {
    filterPerformances() {
      // Этот метод можно оставить пустым, так как фильтрация происходит в computed
    },
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
