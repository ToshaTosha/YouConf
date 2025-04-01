<template>
  <div class="p-4">
    <div class="flex flex-col space-y-4">
      <div class="col-span-full">
        <div
          class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
        >
          <div class="text-center">
            <div class="mt-4 flex text-sm/6 text-gray-600">
              <label
                for="file-upload"
                class="relative cursor-pointer rounded-md font-semibold text-blue-500 focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-blue-400"
              >
                <span>Выберите файлы</span>
                <input
                  id="file-upload"
                  name="file-upload"
                  type="file"
                  class="sr-only"
                  @change="handleFileUpload"
                  multiple
                />
              </label>
              <p class="pl-1">или перетащите файлы сюда или</p>
            </div>
            <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
          </div>
        </div>
      </div>
      <div
        v-for="(file, index) in uploadedFiles"
        :key="file.id || file.name"
        class="flex items-center space-x-2 p-2 border border-gray-300 rounded"
      >
        <div
          v-if="isImage(file)"
          class="w-16 h-16 flex items-center justify-center"
        >
          <img
            :src="getPreviewUrl(file, index)"
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
        <span class="text-gray-700 flex-grow">{{ file.name }}</span>
        <button
          @click="removeFile(index)"
          class="text-red-500 hover:text-red-700"
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
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileUpload',
  props: {
    initialFiles: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      uploadedFiles: [], // Массив для хранения всех файлов
      previewUrls: [], // Массив для хранения URL превью
    }
  },
  watch: {
    initialFiles: {
      immediate: true,
      handler(newFiles) {
        // Инициализируем uploadedFiles существующими файлами
        this.uploadedFiles = [...newFiles]
        this.previewUrls = newFiles.map((file) => this.getFilePreviewUrl(file))
      },
    },
  },
  methods: {
    handleFileUpload(event) {
      console.log(this.uploadedFiles)
      const newFiles = Array.from(event.target.files)
      newFiles.forEach((file) => {
        this.uploadedFiles.push(file) // Добавляем новые файлы
        this.previewUrls.push(this.getFilePreviewUrl(file)) // Генерируем превью
      })
      this.$emit('input', this.uploadedFiles) // Отправляем обновлённый массив файлов
    },
    isImage(file) {
      // if (file instanceof File) {
      //   // return file.type.startsWith('image/')
      // } else if (file.path) {
      //   // Для существующих файлов проверяем расширение
      //   const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']
      //   const extension = file.path.split('.').pop().toLowerCase()
      //   return imageExtensions.includes(extension)
      // }
      return false
    },
    getFilePreviewUrl(file, index) {
      if (file instanceof File) {
        return URL.createObjectURL(file) // Превью для новых файлов
      } else if (file.path) {
        return `/storage/${file.path}` // Превью для существующих файлов
      }
      return null
    },
    getFileIcon(file) {
      const extension = file.name.split('.').pop().toLowerCase()
      switch (extension) {
        case 'txt':
          return this.renderIcon('document-text')
        case 'doc':
        case 'docx':
          return this.renderIcon('document')
        case 'pdf':
          return this.renderIcon('pdf')
        default:
          return this.renderIcon('folder')
      }
    },
    renderIcon(iconName) {
      const icons = {
        'document-text': `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10m-5 4h5" /></svg>`,
        document: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6m-4 4h2m-2-4h2m-2 0V4a1 1 0 011-1h4a1 1 0 011 1v8a1 1 0 01-1 1H9a1 1 0 01-1-1V4a1 1 0 011-1h4" /></svg>`,
        pdf: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" /></svg>`,
        folder: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 4h10a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>`,
      }
      return icons[iconName] || icons['folder']
    },
    removeFile(index) {
      this.uploadedFiles.splice(index, 1)
      this.previewUrls.splice(index, 1)
      this.$emit('input', this.uploadedFiles)
    },
  },
  beforeUnmount() {
    // Освобождаем объекты URL для превью
    this.previewUrls.forEach((url) => {
      if (url) URL.revokeObjectURL(url)
    })
  },
}
</script>
