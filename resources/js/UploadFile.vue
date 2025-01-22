<template>
  <div class="container">
    <!-- Область для перетаскивания файлов -->
    <div
      class="dropzone"
      :class="{ highlight: isDragging }"
      @dragenter="isDragging = true"
      @dragleave="isDragging = false"
      @dragover.prevent
      @drop="onDrop"
    >
      Перетащите файлы сюда или нажмите кнопку выбора
    </div>

    <!-- Форма для обычной загрузки файлов -->
    <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
      <input type="file" multiple name="files" ref="fileInput" />
      <button type="submit">Загрузить</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      files: [],
      isDragging: false,
    }
  },
  methods: {
    onDrop(event) {
      event.preventDefault()
      this.isDragging = false
      this.files = [...event.dataTransfer.files] // Получаем файлы из события drop
      this.uploadFiles()
    },
    handleSubmit() {
      this.files = [...this.$refs.fileInput.files] // Получаем файлы из input
      this.uploadFiles()
    },
    uploadFiles() {
      if (!this.files.length) return

      const formData = new FormData()

      for (let i = 0; i < this.files.length; i++) {
        formData.append(`files[]`, this.files[i])
      }

      axios
        .post('/upload-files', formData)
        .then((response) => {
          console.log(response.data.message) // Файлы успешно загружены
        })
        .catch((error) => {
          console.error(error.response.data.error) // Не удалось загрузить файлы
        })
    },
  },
}
</script>

<style scoped>
.dropzone {
  width: 100%;
  height: 150px;
  border: 2px dashed #ccc;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 18px;
  color: #999;
  margin-bottom: 20px;
}
.highlight {
  background-color: lightblue;
  border-color: blue;
}
</style>
