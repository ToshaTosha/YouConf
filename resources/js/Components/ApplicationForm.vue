<template>
  <form @submit.prevent="submit" class="flex md:flex-col md:items-start">
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
      </div>
      <div class="flex-none mb-4 md:ml-4 w-2/5">
        <!-- Задаем фиксированную ширину -->
        <FileUpload v-if="!isEditMode" @input="updateFiles" />
      </div>
    </div>
    <div class="flex justify-end mt-4">
      <!-- Контейнер для кнопки -->
      <button
        class="bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition duration-200"
        type="submit"
        :disabled="isSubmitting"
      >
        <span v-if="isSubmitting">Отправка...</span>
        <span v-else>{{ isEditMode ? 'Обновить' : 'Создать' }}</span>
      </button>
    </div>
  </form>
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
    application: Object, // Данные заявки для редактирования
    sectionId: Number, // ID секции для создания заявки
  },
  data() {
    return {
      form: reactive({
        title: this.application ? this.application.title : '',
        description: this.application ? this.application.description : '',
        files: [],
      }),
      isEditMode: !!this.application, // Определяем режим редактирования
      isSubmitting: false, // Индикатор загрузки
    }
  },
  methods: {
    async submit() {
      this.isSubmitting = true // Устанавливаем индикатор загрузки
      const formData = new FormData()
      formData.append('title', this.form.title)
      formData.append('description', this.form.description)
      formData.append('section_id', this.sectionId)
      this.form.files.forEach((file) => {
        formData.append('files[]', file)
      })

      try {
        if (this.isEditMode) {
          // Если в режиме редактирования, используем PUT
          await Inertia.post(
            `/applications/${this.application.id}/update`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            },
          )
        } else {
          // Если в режиме создания, используем POST
          await Inertia.post(`/sections/${this.sectionId}/apply`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
        }
      } catch (error) {
        console.error('Ошибка при отправке формы:', error)
      } finally {
        this.isSubmitting = false // Сбрасываем индикатор загрузки
      }
    },
    updateFiles(files) {
      this.form.files = files
    },
  },
}
</script>

<style scoped>
/* Добавьте стили, если необходимо */
</style>
