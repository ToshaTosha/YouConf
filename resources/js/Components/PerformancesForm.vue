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
      <div class="flex-none mb-4 md:ml-4 w-2/5">
        <FileUpload
          @input="updateFiles"
          :initialFiles="performance ? performance.files : []"
        />
      </div>
    </div>
    <div class="flex justify-end mt-4">
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
    performance: Object,
    sectionId: Number,
  },
  data() {
    return {
      form: {
        title: this.performance ? this.performance.title : '',
        description: this.performance ? this.performance.description : '',
        co_authors:
          this.performance && this.performance.co_authors
            ? this.performance.co_authors.split(',')
            : [''],
        files: [],
      },
      isEditMode: !!this.performance,
      isSubmitting: false,
    }
  },
  methods: {
    async submit() {
      this.isSubmitting = true
      const formData = new FormData()
      formData.append('title', this.form.title)
      formData.append('description', this.form.description)
      formData.append('section_id', this.sectionId)
      formData.append('co_authors', this.form.co_authors.join(','))
      this.form.files.forEach((file, index) => {
        if (file instanceof File) {
          // Новый файл
          formData.append(`files[${index}]`, file)
        } else {
          // Существующий файл
          formData.append(`files[${index}]`, JSON.stringify(file))
        }
      })

      try {
        if (this.isEditMode) {
          await Inertia.post(
            `/performances/${this.performance.id}/update`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            },
          )
        } else {
          await Inertia.post(
            `/performances/${this.sectionId}/apply`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            },
          )
        }
      } catch (error) {
        console.error('Ошибка при отправке формы:', error)
      } finally {
        this.isSubmitting = false
      }
    },
    updateFiles(files) {
      this.form.files = files
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
