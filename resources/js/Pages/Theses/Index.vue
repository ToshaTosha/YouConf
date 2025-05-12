<template>
  <div class="p-4">
    <div class="flex justify-between mb-4">
      <div>
        <label for="statusFilter" class="mr-2">Фильтр по статусу:</label>
        <select
          v-model="selectedStatus"
          @change="filterTheses"
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

    <ThesesTable
      :theses="filteredTheses"
      :statuses="statuses"
      :role="$page.props.role"
      @status-updated="handleStatusUpdated"
    />
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import ThesesTable from '@/Components/ThesesTable.vue'

export default {
  props: {
    theses: Array,
    statuses: Array,
  },
  components: {
    Link,
    ThesesTable,
  },
  data() {
    return {
      selectedStatus: '',
    }
  },
  computed: {
    filteredTheses() {
      return this.theses.filter((thesis) => {
        return (
          this.selectedStatus === '' || thesis.status_id === this.selectedStatus
        )
      })
    },
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString() // Форматирование даты
    },
    async updateStatus(thesisId, statusId) {
      try {
        await this.$inertia.post(`/theses/${thesisId}/status`, {
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
