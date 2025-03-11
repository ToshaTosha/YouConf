<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $page.props.section.name }}</h1>
    <p class="text-gray-600">{{ $page.props.section.full_description }}</p>
    <p class="text-gray-600">Полное описание заявки</p>
    <ApplicationForm :section-id="section.id" />
    {{ section }}
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import FileUpload from '@/Components/FileUpload.vue'
import ApplicationForm from '@/Components/ApplicationForm.vue'

export default {
  name: 'Layout',
  components: {
    Link,
    FileUpload,
    ApplicationForm,
  },
  props: {
    section: Object,
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
      formData.append('section_id', this.section.id)
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
