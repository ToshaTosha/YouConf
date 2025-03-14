<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ application.title }}</h1>
    <p class="text-gray-600">{{ application.description }}</p>
    <p class="text-sm text-gray-500 mt-2">
      Статус: {{ application.status.name }}
    </p>
    <p class="text-sm text-gray-500 mt-2">
      Раздел: {{ application.section.name }}
    </p>
    <p class="text-sm text-gray-500 mt-2">
      Пользователь: {{ application.user.first_name }}
    </p>

    <h2 class="text-lg font-semibold mt-4">Связанные файлы:</h2>
    <div class="grid grid-cols-2 gap-4 mt-2">
      <div
        v-for="file in application.files"
        :key="file.id"
        class="flex items-center space-x-2"
      >
        <span class="text-gray-700">{{ file.name }}</span>

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
        <a
          :href="`/storage/${file.path}`"
          target="_blank"
          class="text-blue-500"
        >
          Скачать
        </a>
      </div>
    </div>

    <ApplicationHistory :versions="application.versions" />

    <!-- Встраиваем компонент чата -->
    <Chat
      :chat="application.chat"
      :messages="application.chat?.messages"
      :application="application"
    />
    {{ application }}
  </div>
</template>

<script>
import Chat from '@/Components/Chat.vue' // Импортируем компонент чата
import ApplicationHistory from '@/Components/ApplicationHistory.vue'

export default {
  components: {
    Chat,
    ApplicationHistory,
  },
  props: {
    application: Object,
  },
  methods: {
    isImage(file) {
      const extension = file.name.split('.').pop().toLowerCase()
      console.log(extension === 'png' || extension === 'jpg')
      return file && (extension === 'png' || extension === 'jpg')
    },
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
    renderIcon(iconName) {
      const icons = {
        'document-text': `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10m-5 4h5" /></svg>`,
        document: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6m-4 4h2m-2-4h2m-2 0V4a1 1 0 011-1h4a1 1 0 011 1v8a1 1 0 01-1 1H9a1 1 0 01-1-1V4a1 1 0 011-1h4" /></svg>`,
        pdf: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" /></svg>`,
        folder: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>`,
      }
      return icons[iconName] || icons['folder'] // Возвращаем иконку по умолчанию, если не найдено
    },
  },
}
</script>
