<template>
  <div class="p-4">
    {{ section_name }}
    <h1 class="text-2xl font-bold mb-4">
      {{ isEditMode ? 'Редактировать тезис' : 'Создать тезис' }}
    </h1>
    <ThesesForm :section-id="section_id" :thesis="thesis" />
  </div>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import ThesesForm from '@/Components/ThesesForm.vue'

export default {
  name: 'ThesisFormPage',
  components: {
    ThesesForm,
  },
  props: {
    section_name: String,
    section_id: {
      type: Number,
      default: null,
    },
    thesis: Object,
  },
  data() {
    return {
      form: reactive({
        title: this.thesis ? this.thesis.title : '',
        description: this.thesis ? this.thesis.description : '',
        files: [],
      }),
      isEditMode: !!this.thesis, // Определяем режим редактирования
    }
  },
}
</script>
