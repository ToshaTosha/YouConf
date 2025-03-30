<template>
  <div class="p-4">
    {{ section_name }}
    <h1 class="text-2xl font-bold mb-4">
      {{ isEditMode ? 'Редактировать заявку' : 'Создать заявку' }}
    </h1>
    <PerformancesForm :section-id="section_id" :performance="performance" />
  </div>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import PerformancesForm from '@/Components/PerformancesForm.vue'

export default {
  name: 'PerformanceFormPage',
  components: {
    PerformancesForm,
  },
  props: {
    section_name: String,
    section_id: {
      type: Number,
      default: null,
    },
    performance: Object,
  },
  data() {
    return {
      form: reactive({
        title: this.performance ? this.performance.title : '',
        description: this.performance ? this.performance.description : '',
        files: [],
      }),
      isEditMode: !!this.performance, // Определяем режим редактирования
    }
  },
}
</script>
