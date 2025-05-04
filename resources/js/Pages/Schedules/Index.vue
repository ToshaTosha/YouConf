<template>
  <div class="p-6 bg-gray-100 min-h-screen">
    <h2 class="text-2xl font-semibold mb-4">
      Расписание выступлений: {{ sectionName }}
    </h2>
    <div v-if="loading" class="text-center">Загрузка...</div>
    <div v-else>
      <div class="space-y-4">
        <div
          v-for="performance in performances"
          :key="performance.title"
          class="bg-white p-6 rounded-lg shadow-md"
        >
          <h3 class="text-xl font-bold text-blue-600">
            {{ performance.title }}
          </h3>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Участник:</span>
            {{ performance.user.first_name }} {{ performance.user.last_name }}
          </p>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Описание:</span>
            {{ performance.description }}
          </p>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Время:</span>
            {{ performance.start_time }} - {{ performance.end_time }}
          </p>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Место:</span>
            {{ performance?.location?.name }}
          </p>

          <div
            v-for="file in performance?.attachments"
            :key="file.id"
            class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg cursor-pointer"
            @click="downloadFile(file)"
          >
            <div
              v-if="isImage(file)"
              class="w-full h-40 flex items-center justify-center overflow-hidden mb-2"
            >
              <img
                :src="file.original_url"
                alt="File preview"
                class="max-w-full max-h-full object-contain rounded"
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
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    performances: {
      type: Array,
      required: true,
    },
    sectionName: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  methods: {
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
  },
}
</script>
