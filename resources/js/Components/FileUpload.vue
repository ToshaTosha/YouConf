<template>
  <div class="p-4">
    <div class="flex flex-col space-y-4">
      <input
        type="file"
        @change="handleFileUpload"
        required
        class="border border-gray-300 rounded p-2 mb-4"
        multiple
      />
      <div
        v-for="(file, index) in uploadedFiles"
        :key="index"
        class="flex items-center space-x-2 p-2 border border-gray-300 rounded"
      >
        <div
          v-if="isImage(file)"
          class="w-16 h-16 flex items-center justify-center"
        >
          <img
            :src="getPreviewUrl(index)"
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
  data() {
    return {
      uploadedFiles: [],
      previewUrls: [],
    }
  },
  methods: {
    handleFileUpload(event) {
      const newFiles = Array.from(event.target.files)
      this.uploadedFiles.push(...newFiles)

      newFiles.forEach((file) => {
        if (this.isImage(file)) {
          this.previewUrls.push(URL.createObjectURL(file))
        } else {
          this.previewUrls.push(null)
        }
      })

      this.$emit('input', this.uploadedFiles)
    },
    isImage(file) {
      return file && file.type.startsWith('image/')
    },
    getPreviewUrl(index) {
      return this.previewUrls[index]
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
      return icons[iconName] || icons['folder'] // Возвращаем иконку по умолчанию, если не найдено
    },
    removeFile(index) {
      this.uploadedFiles.splice(index, 1)
      this.previewUrls.splice(index, 1)
      this.$emit('input', this.uploadedFiles)
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
