<template>
  <div class="flex">
    <form
      @submit.prevent="submit"
      class="flex-1 md:flex-col md:items-start"
      :class="{ 'pointer-events-none opacity-50': isSubmitting }"
    >
      <div class="flex flex-row w-full">
        <div class="flex-1 mb-4 md:mr-4 w-3/5">
          <label class="block text-gray-700 font-semibold mb-1">Название</label>
          <input
            v-model="form.title"
            type="text"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            required
            placeholder="Введите название"
          />
          <label class="block text-gray-700 font-semibold mb-1 mt-4">
            Описание
          </label>
          <textarea
            v-model="form.description"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            required
            placeholder="Введите описание"
            rows="4"
          ></textarea>
          <label class="block text-gray-700 font-semibold mb-1 mt-4">
            Соавторы
          </label>
          <div
            v-for="(coAuthor, index) in form.co_authors"
            :key="index"
            class="flex items-center mb-2"
          >
            <input
              v-model="form.co_authors[index]"
              type="text"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
              placeholder="Введите имя соавтора"
            />
            <button
              type="button"
              @click="removeCoAuthor(index)"
              class="ml-2 text-red-500"
            >
              Удалить
            </button>
          </div>
          <button type="button" @click="addCoAuthor" class="text-blue-500">
            Добавить соавтора
          </button>
        </div>
      </div>
      <div class="flex justify-end mt-4">
        <button
          class="bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition duration-200"
          type="submit"
          :disabled="isSubmitting"
        >
          <span v-if="isSubmitting">
            <svg
              class="animate-spin h-5 w-5 mr-3 text-white inline-block"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
              ></path>
            </svg>
            Отправка...
          </span>
          <span v-else>{{ isEditMode ? 'Обновить' : 'Создать' }}</span>
        </button>
      </div>
    </form>
    <div class="flex-1 mb-4 md:ml-4 w-2/5">
      <FileUpload
        :initialFiles="thesis?.media || []"
        :disabled="isSubmitting"
        @files-updated="handleFilesUpdate"
        @file-removed="handleFileRemove"
      />
    </div>
  </div>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import FileUpload from '@/Components/FileUpload.vue'

export default {
  components: {
    FileUpload,
  },
  props: {
    thesis: Object,
    sectionId: Number,
  },
  data() {
    return {
      form: {
        title: this.thesis ? this.thesis.title : '',
        description: this.thesis ? this.thesis.description : '',
        co_authors:
          this.thesis && this.thesis.co_authors
            ? this.thesis.co_authors.split(',')
            : [''],
      },
      newFiles: [],
      isEditMode: !!this.thesis,
      isSubmitting: false,
    }
  },
  methods: {
    async submit() {
      this.isSubmitting = true

      const formData = new FormData()
      formData.append('title', this.form.title)
      formData.append('description', this.form.description)
      formData.append('co_authors', JSON.stringify(this.form.co_authors))

      this.newFiles.forEach((file) => {
        formData.append('attachments[]', file)
      })

      try {
        if (this.isEditMode) {
          await this.$inertia.post(`/theses/${this.thesis.id}/update`, formData)
        } else {
          await this.$inertia.post(`/theses/${this.sectionId}/apply`, formData)
        }
      } catch (error) {
        console.error('Ошибка при отправке формы:', error)
      } finally {
        this.isSubmitting = false
      }
    },
    handleFilesUpdate(files) {
      this.newFiles = files.filter((file) => file instanceof File)
    },
    handleFileRemove(fileId) {
      // Отправляем запрос на удаление файла, если он уже загружен
      this.$inertia
        .delete(`/theses/${this.thesis.id}/media/${fileId}`)
        .then(() => {
          // Успешно удалено, можно обновить состояние или уведомить пользователя
          console.log(`Файл с ID ${fileId} успешно удален.`)
        })
        .catch((error) => {
          console.error(`Ошибка при удалении файла с ID ${fileId}:`, error)
        })
    },
    addCoAuthor() {
      this.form.co_authors.push('') // Добавляем пустую строку для нового соавтора
    },
    removeCoAuthor(index) {
      this.form.co_authors.splice(index, 1) // Удаляем соавтора по индексу
    },
  },
}
</script>

<style scoped>
/* Добавьте стили для спиннера, если необходимо */
</style>
