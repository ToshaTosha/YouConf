<template>
  <div>
    <div v-if="applications.length === 0" class="text-center p-4 text-gray-600">
      У вас нет заявок.
    </div>
    <table v-else :class="tableClass">
      <thead>
        <tr>
          <th class="py-2 border-b">Название</th>
          <th class="py-2 border-b">Дата</th>
          <th class="py-2 border-b">Статус</th>
          <th v-if="isExpert" class="py-2 border-b">Автор заявки</th>
          <!-- Новая колонка -->
          <th v-if="isExpert" class="py-2 border-b">Изменить статус</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="application in applications"
          :key="application.id"
          class="cursor-pointer hover:bg-gray-100"
        >
          <td class="border px-4 py-2">
            <Link :href="`/applications/${application.id}`">
              {{ application.title }}
            </Link>
          </td>
          <td class="border px-4 py-2">
            {{ formatDate(application.created_at) }}
          </td>
          <td class="border px-4 py-2">{{ application.status.name }}</td>
          <td v-if="isExpert" class="border px-4 py-2">
            {{ application.user.first_name }} {{ application.user.last_name }}
            <!-- Имя автора заявки -->
          </td>
          <td v-if="isExpert" class="border px-4 py-2">
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
      </tbody>
    </table>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
  name: 'ApplicationsTable',
  props: {
    applications: Array,
    statuses: Array,
    role: String,
  },
  components: {
    Link,
  },
  computed: {
    isExpert() {
      return this.role === 'expert'
    },
    tableClass() {
      return this.isExpert
        ? 'min-w-full bg-white border border-gray-200'
        : 'min-w-full bg-white border border-gray-200 shadow-md'
    },
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
