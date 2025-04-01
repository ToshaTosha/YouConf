<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ performance.title }}</h1>
    <p class="text-gray-600">{{ performance.description }}</p>
    <p class="text-sm text-gray-500 mt-2">
      Статус: {{ performance.status.name }}
    </p>
    <p class="text-sm text-gray-500 mt-2">
      Раздел: {{ performance.section.name }}
    </p>
    <p class="text-sm text-gray-500 mt-2">
      Пользователь: {{ performance.user.first_name }}
    </p>

    <h2 class="text-lg font-semibold mt-4">Связанные файлы:</h2>
    <div class="grid grid-cols-2 gap-4 mt-2">
      <div
        v-for="file in performance.files"
        :key="file.id"
        class="flex items-center space-x-2"
      >
        <span class="text-gray-700">{{ file.name }}</span>
        <img
          :src="`/storage/${file.path}`"
          alt="Preview"
          class="w-32 h-32 object-cover rounded"
        />
        <a
          :href="`/storage/${file.path}`"
          target="_blank"
          class="text-blue-500"
        >
          Скачать
        </a>
      </div>
    </div>
    <PerformancesForm :performance="performance" />
  </div>
</template>

<script>
import PerformancesForm from '@/Components/PerformanceForm.vue'

export default {
  components: {
    PerformancesForm,
  },
  props: {
    performance: Object,
  },
}
</script>
