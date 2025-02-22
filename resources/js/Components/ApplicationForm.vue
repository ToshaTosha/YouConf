<template>
  <form @submit.prevent="submit">
    <div class="mb-4">
      <label class="block text-gray-700">Название</label>
      <input
        v-model="form.title"
        type="text"
        class="w-full p-2 border rounded-lg"
        required
      />
      <label class="block text-gray-700">Описание</label>
      <input
        v-model="form.description"
        type="text"
        class="w-full p-2 border rounded-lg"
        required
      />
    </div>
    <!-- <FileUpload @input="updateFiles" /> -->
    <button
      class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600"
      type="submit"
    >
      {{ isEditMode ? 'Обновить' : 'Создать' }}
    </button>
  </form>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import FileUpload from '@/Components/FileUpload.vue'

export default {
  name: 'ApplicationForm',
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
    }
  },
  methods: {
    submit() {
      const formData = new FormData()
      formData.append('title', this.form.title)
      formData.append('description', this.form.description)
      this.form.files.forEach((file) => {
        formData.append('files[]', file)
      })

      if (this.isEditMode) {
        // Если в режиме редактирования, используем PUT
        Inertia.post(`/applications/${this.application.id}/update`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
      } else {
        // Если в режиме создания, используем POST
        Inertia.post(`/sections/${this.sectionId}/apply`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
      }
    },
    updateFiles(files) {
      this.form.files = files
    },
  },
}
</script>
