<template>
  <div class="p-4">
    <table class="min-w-full bg-white border border-gray-200">
      <thead>
        <tr>
          <th class="py-2 border-b">Название</th>
          <th class="py-2 border-b">Дата</th>
          <th class="py-2 border-b">Статус</th>
          <th v-if="$page.props.user_data.role_id === 2" class="py-2 border-b">
            Изменить статус
          </th>
        </tr>
      </thead>
      <tbody>
        <Link :href="`/applications/${application.id}`">
          <tr
            v-for="application in applications"
            :key="application.id"
            class="cursor-pointer hover:bg-gray-100"
          >
            <td class="border px-4 py-2">{{ application.title }}</td>
            <td class="border px-4 py-2">
              {{ formatDate(application.created_at) }}
            </td>
            <td class="border px-4 py-2">{{ application.status.name }}</td>
            <td
              v-if="$page.props.user_data.role_id === 2"
              class="border px-4 py-2"
            >
              <select
                v-model="application.status_id"
                @change="updateStatus(application.id, application.status_id)"
                class="border rounded p-1"
              >
                <option
                  v-for="status in statuses"
                  :key="status.id"
                  :value="status.id"
                >
                  {{ status.name }}
                </option>
              </select>
            </td>
          </tr>
        </Link>
      </tbody>
    </table>
  </div>
</template>

import { Inertia } from '@inertiajs/inertia' import { Link } from
'@inertiajs/inertia-vue3'

<script>
export default {
  props: {
    applications: Array,
    statuses: Array,
  },
  methods: {
    //   openApplication(id) {
    //     this.$router.push({ name: 'applications.show', params: { id } });
    //   },
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

<style scoped>
/* Добавьте свои стили, если необходимо */
</style>
