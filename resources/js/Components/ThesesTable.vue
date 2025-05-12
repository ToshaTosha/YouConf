<template>
  <div>
    <div v-if="theses.length === 0" class="text-center p-4 text-gray-600">
      У вас нет заявок.
    </div>
    <table v-else :class="tableClass">
      <thead>
        <tr>
          <th class="py-2 border-b">Название</th>
          <th class="py-2 border-b">Секция</th>
          <th class="py-2 border-b">Дата</th>
          <th class="py-2 border-b">Статус</th>
          <th v-if="isExpert" class="py-2 border-b">Автор тезиса</th>
          <!-- Новая колонка -->
          <th v-if="isExpert" class="py-2 border-b">Изменить статус</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="thesis in theses"
          :key="thesis.id"
          class="cursor-pointer hover:bg-gray-100"
        >
          <td class="border px-4 py-2">
            <Link :href="`/theses/${thesis.id}`">
              {{ thesis.title }}
            </Link>
          </td>
          <td class="border px-4 py-2">
            <Link :href="`/theses/${thesis.id}`">
              {{ thesis.section.name }}
            </Link>
          </td>
          <td class="border px-4 py-2">
            {{ formatDate(thesis.created_at) }}
          </td>
          <td class="border px-4 py-2">{{ thesis.status.name }}</td>
          <td v-if="isExpert" class="border px-4 py-2">
            {{ thesis.user.first_name }} {{ thesis.user.last_name }}
          </td>
          <td v-if="isExpert" class="border px-4 py-2">
            <select
              v-model="thesis.status_id"
              @change="updateStatus(thesis.id, thesis.status_id)"
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
  name: 'ThesesTable',
  props: {
    theses: Array,
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
