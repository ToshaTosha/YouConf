<template>
  <div>
    <input
      type="file"
      @change="handleFileUpload"
      required
      class="border border-gray-300 rounded p-2"
    />
    <div class="grid grid-cols-2 gap-4">
      <div
        v-for="(file, index) in uploadedFiles"
        :key="index"
        class="flex items-center space-x-2"
      >
        <img
          v-if="isImage(file)"
          :src="getPreviewUrl(index)"
          alt="Preview"
          class="w-16 h-16 object-cover rounded"
        />
        <span class="text-gray-700">{{ file.name }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  name: 'FileUpload',
  data() {
    return {
      uploadedFiles: [],
      previewUrls: [],
    }
  },
  methods: {
    handleFileUpload(event) {
      const newFiles = Array.from(event.target.files) // Получаем новые файлы
      this.uploadedFiles.push(...newFiles) // Добавляем новые файлы к уже существующим

      newFiles.forEach((file) => {
        if (this.isImage(file)) {
          // Создаем временный URL для предварительного просмотра
          this.previewUrls.push(URL.createObjectURL(file))
        } else {
          this.previewUrls.push(null) // Если не изображение, добавляем null
        }
      })

      // Передаем загруженные файлы в родительский компонент
      this.$emit('input', this.uploadedFiles)
    },
    // Проверка, является ли файл изображением
    isImage(file) {
      return file && file.type.startsWith('image/')
    },
    // Получение временного URL для предварительного просмотра
    getPreviewUrl(index) {
      return this.previewUrls[index]
    },
    isImage(file) {
      return file && file.type.startsWith('image/')
    },
    getPreviewUrl(index) {
      return this.previewUrls[index]
    },
  },
  beforeUnmount() {
    this.uploadedFiles.forEach((file) => {
      if (file.previewUrl) {
        URL.revokeObjectURL(file.previewUrl)
      }
    })
  },
}
</script>
