<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $page.props.section.name }}</h1>
    <p class="text-gray-600">{{ $page.props.section.full_description }}</p>
    <p class="text-sm text-gray-500 mt-2">
      Дата: {{ formatDate($page.props.section.start_date) }} -
      {{ formatDate($page.props.section.end_date) }}
    </p>
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
      <FileUpload @input="updateFiles" />
      <button
        class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600"
        type="submit"
      >
        Отправить
      </button>
    </form>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import FileUpload from '@/Components/FileUpload.vue'

export default {
  name: 'Layout',
  components: {
    Link,
    FileUpload,
  },
  props: {
    sections: Array,
  },
  data() {
    return {
      form: reactive({
        title: null,
        description: null,
        files: [],
      }),
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

      Inertia.post(`/sections/${this.$page.props.section.id}/apply`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString()
    },
    updateFiles(files) {
      this.form.files = files
      console.log(files, this.form.files)
    },
  },
}
</script>
