<template>
  <div class="file-upload">
    <!-- Поле загрузки -->
    <input
      type="file"
      @change="handleFileUpload"
      multiple
      class="hidden"
      ref="fileInput"
      accept="image/*, .pdf, .doc, .docx"
    />
    <button @click="$refs.fileInput.click()">Выбрать файлы</button>

    <!-- Список файлов с превью -->
    <div v-for="(file, index) in allFiles" :key="file.id || file.name">
      <!-- Превью для изображений -->
      <img
        v-if="isImage(file)"
        :src="getPreviewUrl(file)"
        class="preview-image"
      />

      <!-- Иконка для документов -->
      <div v-else class="file-icon">
        {{ getFileIcon(file) }}
      </div>

      <span>{{ file.name || file.file_name }}</span>
      <button @click="removeFile(index)">×</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    performanceId: Number,
    initialFiles: { type: Array, default: () => [] },
  },

  data() {
    return {
      newFiles: [], // Новые файлы (ещё не загруженные)
    }
  },

  computed: {
    allFiles() {
      return [...this.initialFiles, ...this.newFiles]
    },
  },

  methods: {
    // Проверка, что файл - изображение
    isImage(file) {
      if (file.type) return file.type.startsWith('image/')
      if (file.mime_type) return file.mime_type.startsWith('image/')
      return false
    },

    // URL для превью
    getPreviewUrl(file) {
      if (file instanceof File) {
        return URL.createObjectURL(file) // Превью нового файла
      }
      return file.original_url // Превью из Spatie
    },

    // Иконки для документов
    getFileIcon(file) {
      const ext =
        file.name?.split('.').pop() || file.file_name?.split('.').pop()
      return (
        {
          pdf: '📄',
          doc: '📝',
          docx: '📝',
        }[ext] || '📁'
      )
    },

    handleFileUpload(e) {
      this.newFiles = [...this.newFiles, ...Array.from(e.target.files)]
      this.$emit('files-updated', this.allFiles) // Обновляем список файлов
    },

    removeFile(index) {
      const fileToRemove = this.allFiles[index]
      if (fileToRemove.id) {
        // Если файл уже загружен, отправляем запрос на удаление
        this.$emit('file-removed', fileToRemove.id)
      }
      this.newFiles.splice(index, 1) // Удаляем файл из списка новых файлов
      this.$emit('files-updated', this.allFiles) // Обновляем список файлов
    },
  },
}
</script>

<style>
.preview-image {
  max-width: 100px;
  max-height: 100px;
}
.file-icon {
  font-size: 24px;
}
</style>
