<template>
  <div class="relative">
    <button
      @click="toggleDropdown"
      class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative"
    >
      <svg
        class="w-6 h-6 text-white"
        aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        fill="none"
        viewBox="0 0 24 24"
      >
        <path
          stroke="currentColor"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 5.365V3m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.365ZM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 0 0 4.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92Z"
        />
      </svg>

      <span
        v-if="$page.props.unreadNotificationsCount > 0"
        class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
      >
        {{ $page.props.unreadNotificationsCount }}
      </span>
    </button>

    <div
      v-if="isOpen"
      class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
    >
      <div class="px-4 py-2 border-b flex justify-between items-center">
        <p class="text-sm font-medium text-gray-700">Уведомления</p>
        <button
          @click="markAllAsRead"
          class="text-xs text-blue-500 hover:text-blue-700"
          :disabled="$page.props.unreadNotificationsCount === 0"
        >
          Прочитать все
        </button>
      </div>

      <div v-if="notifications.length > 0" class="max-h-96 overflow-y-auto">
        <a
          v-for="notification in notifications"
          :key="notification.id"
          href="#"
          @click.prevent="markAsRead(notification)"
          class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b"
          :class="{ 'bg-gray-50': !notification.read_at }"
        >
          <p>{{ notification.data.message }}</p>
          <p class="text-xs text-gray-500 mt-1">
            {{ formatDate(notification.created_at) }}
          </p>
        </a>
      </div>

      <div v-else class="px-4 py-3 text-sm text-gray-700">
        Нет новых уведомлений
      </div>

      <div class="px-4 py-2 border-t">
        <!-- <Link
          :href="route('notifications.index')"
          class="text-sm font-medium text-blue-500 hover:text-blue-700"
        >
          Просмотреть все
        </Link> -->
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'

const isOpen = ref(false)
const notifications = ref([])

// Форматирование даты
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString()
}

// Отметить как прочитанное
const markAsRead = (notification) => {
  if (!notification.read_at) {
    router.post(
      route('notifications.markAsRead', notification.id),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          notification.read_at = new Date().toISOString()
          isOpen.value = false
        },
      },
    )
  }
}

// Отметить все как прочитанные
const markAllAsRead = () => {
  if ($page.props.unreadNotificationsCount > 0) {
    router.post(
      route('notifications.markAllAsRead'),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          notifications.value.forEach(
            (n) => (n.read_at = n.read_at || new Date().toISOString()),
          )
        },
      },
    )
  }
}

// Переключение dropdown
const toggleDropdown = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value && notifications.value.length === 0) {
    Inertia.reload({
      only: ['notifications'],
      preserveScroll: true,
      onSuccess: (page) => {
        console.log(page)
        notifications.value = page.props.notifications
      },
    })
  }
}
</script>
