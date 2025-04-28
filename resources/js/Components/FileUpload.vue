<template>
  <div class="file-upload">
    <!-- Поле загрузки с поддержкой drag-and-drop -->
    <div
      @dragover.prevent
      @drop.prevent="handleDrop"
      @click="$refs.fileInput.click()"
      class="border-2 border-dashed border-gray-300 p-4 text-center cursor-pointer hover:bg-gray-100"
      :class="{ 'pointer-events-none opacity-50': disabled }"
    >
      <p class="text-gray-500">
        Перетащите файлы сюда или нажмите, чтобы выбрать
      </p>
      <input
        type="file"
        @change="handleFileUpload"
        multiple
        class="hidden"
        ref="fileInput"
        accept="image/*, .pdf, .doc, .docx"
      />
    </div>

    <!-- Список файлов с превью -->
    <div
      v-for="(file, index) in allFiles"
      :key="file.id || file.name"
      class="flex items-center justify-between mt-2"
    >
      <!-- Превью для изображений -->
      <img
        v-if="isImage(file)"
        :src="getPreviewUrl(file)"
        class="preview-image mr-2"
      />

      <!-- Иконка для документов -->
      <div v-else class="file-icon mr-2">
        {{ getFileIcon(file) }}
      </div>

      <span class="flex-1">{{ file.name || file.file_name }}</span>
      <button @click="removeFile(index)" class="text-red-500">×</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileUpload',
  props: {
    initialFiles: { type: Array, default: () => [] },
    disabled: {
      type: Boolean,
      default: false,
    },
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

    handleDrop(e) {
      const files = Array.from(e.dataTransfer.files)
      this.newFiles = [...this.newFiles, ...files]
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
