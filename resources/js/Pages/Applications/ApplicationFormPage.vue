<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">
      {{ isEditMode ? 'Редактировать заявку' : 'Создать заявку' }}
    </h1>
    <ApplicationForm :section-id="section_id" :application="application" />
  </div>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import ApplicationForm from '@/Components/ApplicationForm.vue'

export default {
  name: 'ApplicationFormPage',
  components: {
    ApplicationForm,
  },
  props: {
    section: Object,
    section_id: {
      type: Number,
      default: null,
    },
    application: Object,
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
}
</script>
