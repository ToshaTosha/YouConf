<template>
  <div class="p-6">
    <div class="bg-blue-100 p-6 rounded-lg mb-6 shadow-md">
      <h1 class="text-2xl font-bold mb-4 text-blue-800">
        Информация о пользователе
      </h1>
      <div class="space-y-3">
        <p class="text-gray-700">
          <strong class="font-semibold">{{ Имя }}:</strong>
          {{ user_data.first_name }}
        </p>
        <p class="text-gray-700">
          <strong class="font-semibold">{{ Фамилия }}:</strong>
          {{ user_data.last_name }}
        </p>
        <p v-if="user_data.email" class="text-gray-700">
          <strong class="font-semibold">{{ Email }}:</strong>
          {{ user_data.email }}
        </p>
        <p class="mb-8"></p>
        <Link
          :disabled="isDisabled"
          :href="`/user/${user_data.id}/edit`"
          class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200"
        >
          Редактировать
        </Link>
      </div>
    </div>
    <PerformancesTable
      v-if="$page.props?.role !== 'expert'"
      :performances="performances"
      :statuses="statuses"
      :role="$page.props?.role"
      @status-updated="handleStatusUpdated"
    />
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import PerformancesTable from '@/Components/PerformancesTable.vue'

const formatDate = (dateString, formatType) => {
  const date = new Date(dateString)
  if (formatType === 'long') {
    return date.toLocaleDateString('ru-RU', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  } else if (formatType === 'short') {
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()
    return `${day}.${month}.${year}`
  }
  return dateString
}

const UserInfo = {
  props: {
    label: String,
    value: String,
  },
  template: `
    <p class="text-gray-700">
      <strong class="font-semibold">{{ label }}:</strong>
      {{ value }}
    </p>
  `,
}

export default {
  name: 'UserProfile',
  props: {
    user_data: Object,
    performances: Array,
  },
  components: {
    Link,
    UserInfo,
    PerformancesTable,
  },
  methods: {
    formatDate,
  },
}
</script>
