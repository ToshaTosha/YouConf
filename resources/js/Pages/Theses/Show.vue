<template>
  <div class="p-4 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
      {{ thesis.title }}
    </h1>

    <p class="text-gray-600 mb-4">{{ thesis.description }}</p>

    <div class="space-y-2 text-sm text-gray-600">
      <p>
        <strong>Статус:</strong>
        {{ thesis.status.name }}
      </p>
      <p>
        <strong>Раздел:</strong>
        {{ thesis.section.name }}
      </p>
      <p>
        <strong>Пользователь:</strong>
        {{ thesis.user.first_name }}
      </p>
    </div>

    <!-- Связанные файлы -->
    <h2 class="text-lg font-semibold mt-6 mb-2 text-gray-800">
      Связанные файлы:
    </h2>
    <div class="grid grid-cols-4 gap-4">
      <div
        v-for="file in mediaFiles"
        :key="file.id"
        class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg cursor-pointer"
        @click="downloadFile(file)"
      >
        <div v-if="isImage(file)" class="mb-2">
          <img
            :src="file.original_url"
            alt="File preview"
            class="w-full h-auto rounded"
          />
        </div>
        <div
          v-else
          class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded"
        >
          <span class="text-gray-500" v-html="getFileIcon(file)"></span>
        </div>
        <div v-if="!isImage(file)" class="flex-1 text-left">
          <span class="font-semibold">{{ file.name }}</span>
        </div>
      </div>
    </div>

    <!-- Кнопки действий -->
    <div class="flex items-center justify-end space-x-4 mt-6">
      <div v-if="isExpert" class="border px-4 py-2">
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
      </div>
      <button
        @click="toggleChat"
        class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition duration-200"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
          />
        </svg>
      </button>

      <!-- Кнопка для редактирования -->
      <Link
        v-if="isThesisOwner"
        :disabled="isDisabled"
        :href="`/theses/${thesis.id}/edit`"
        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200"
      >
        Редактировать
      </Link>
    </div>

    <!-- Модальное окно чата -->
    <div
      v-if="isChatOpen"
      class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    >
      <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 p-6">
        <!-- Компонент чата -->
        <Chat
          v-if="thesis.chat"
          :chat="thesis.chat"
          :messages="thesis.chat.messages"
          :thesis="thesis"
          :isActive="!isDisabled"
        />
        <!-- Кнопка закрытия чата -->
        <button
          @click="toggleChat"
          class="mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200"
        >
          Закрыть чат
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Chat from '@/Components/Chat.vue'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: {
    Chat,
    Link,
  },
  props: {
    thesis: Object,
    messages: Array,
    mediaFiles: Array,
    statuses: Array,
  },
  data() {
    return {
      isChatOpen: false,
    }
  },
  computed: {
    isThesisOwner() {
      return this.$page.props.user_data.id === this.thesis.user.id
    },
    isDisabled() {
      return [2, 4].includes(this.thesis.status_id)
    },
    isExpert() {
      return this.$page.props.role === 'expert'
    },
  },
  methods: {
    // Переключение видимости чата
    toggleChat() {
      this.isChatOpen = !this.isChatOpen
    },
    isImage(file) {
      return file.mime_type.startsWith('image/')
    },
    getFileIcon(file) {
      const ext = file.file_name?.split('.').pop()
      return (
        {
          pdf: '📄',
          doc: '📝',
          docx: '📝',
        }[ext] || '📁'
      )
    },
    downloadFile(file) {
      const link = document.createElement('a')
      link.href = file.original_url
      link.download = file.name
      link.target = '_blank'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
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
