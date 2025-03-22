<template>
  <div class="p-4">
    <ApplicationsTable
      :applications="applications"
      :statuses="statuses"
      :role="$page.props.role"
      @status-updated="handleStatusUpdated"
    />
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/inertia-vue3'
import ApplicationsTable from '@/components/ApplicationsTable.vue'
export default {
  props: {
    applications: Array,
    statuses: Array,
  },
  components: {
    Link,
    ApplicationsTable,
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString() // Форматирование даты
    },
    async updateStatus(applicationId, statusId) {
      try {
        await this.$inertia.post(`/applications/${applicationId}/status`, {
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
