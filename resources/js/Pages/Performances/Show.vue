<template>
  <div class="p-4 bg-white rounded-lg shadow-md">
    <!-- Заголовок заявки -->
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
      {{ performance.title }}
    </h1>

    <!-- Описание заявки -->
    <p class="text-gray-600 mb-4">{{ performance.description }}</p>

    <!-- Детали заявки -->
    <div class="space-y-2 text-sm text-gray-600">
      <p>
        <strong>Статус:</strong>
        {{ performance.status.name }}
      </p>
      <p>
        <strong>Раздел:</strong>
        {{ performance.section.name }}
      </p>
      <p>
        <strong>Пользователь:</strong>
        {{ performance.user.first_name }}
      </p>
    </div>

    <!-- Связанные файлы -->
    <h2 class="text-lg font-semibold mt-6 mb-2 text-gray-800">
      Связанные файлы:
    </h2>
    <div class="grid grid-cols-4 gap-4">
      <div
        v-for="file in performance.files"
        :key="file.id"
        class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg cursor-pointer"
        @click="downloadFile(file.path)"
      >
        <!-- Иконка файла -->
        <div
          v-if="isImage(file)"
          class="w-16 h-16 flex items-center justify-center"
        >
          <img
            :src="`/storage/${file.path}`"
            alt="Preview"
            class="w-full h-full object-cover rounded"
          />
        </div>
        <div
          v-else
          class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded"
        >
          <span class="text-gray-500" v-html="getFileIcon(file)"></span>
        </div>
        <div class="flex-1 text-left">
          <span class="font-semibold">{{ file.name }}</span>
        </div>
      </div>
    </div>

    <!-- Кнопки действий -->
    <div class="flex items-center justify-end space-x-4 mt-6">
      <!-- Иконка для открытия чата -->
      <button
        @click="toggleChat"
        :disabled="isDisabled"
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
        v-if="isPerformanceOwner"
        as="button"
        :disabled="isDisabled"
        :href="`/performances/${performance.id}/edit`"
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
          v-if="performance.chat"
          :chat="performance.chat"
          :messages="performance.chat.messages"
          :performance="performance"
          :isActive="true"
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
    performance: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      isChatOpen: false, // Состояние для управления видимостью чата
    }
  },
  computed: {
    // Проверка, является ли текущий пользователь владельцем заявки
    isPerformanceOwner() {
      return this.$page.props.user_data.id === this.performance.user.id
    },
    isDisabled() {
      console.log(
        this.performance.status_id,
        this.performance.status_id === 2 || 4,
      )
      return this.performance.status_id === 2 || 4
    },
  },
  methods: {
    // Переключение видимости чата
    toggleChat() {
      this.isChatOpen = !this.isChatOpen
    },
    // Проверка, является ли файл изображением
    isImage(file) {
      const extension = file.name.split('.').pop().toLowerCase()
      return ['png', 'jpg', 'jpeg', 'gif'].includes(extension)
    },
    // Получение иконки для файла
    getFileIcon(file) {
      const extension = file.name.split('.').pop().toLowerCase()
      switch (extension) {
        case 'txt':
          return this.renderIcon('document-text') // Иконка для текстового файла
        case 'doc':
        case 'docx':
          return this.renderIcon('document') // Иконка для документа
        case 'pdf':
          return this.renderIcon('pdf') // Иконка для PDF
        default:
          return this.renderIcon('folder') // Иконка для папки
      }
    },
    // Рендер иконки
    renderIcon(iconName) {
      const icons = {
        'document-text': `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10m-5 4h5" /></svg>`,
        document: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6m-4 4h2m-2-4h2m-2 0V4a1 1 0 011-1h4a1 1 0 011 1v8a1 1 0 01-1 1H9a1 1 0 01-1-1V4a1 1 0 011-1h4" /></svg>`,
        pdf: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" /></svg>`,
        folder: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>`,
      }
      return icons[iconName] || icons['folder'] // Возвращаем иконку по умолчанию, если не найдено
    },
    downloadFile(filePath) {
      const link = document.createElement('a')
      link.href = `/storage/${filePath}`
      link.download = filePath // Устанавливаем имя файла для скачивания
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    },
  },
}
</script>
