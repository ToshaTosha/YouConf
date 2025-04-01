<template>
  <div class="p-6 bg-gray-100 min-h-screen">
    Расписание выступлений: {{ sectionName }}
    <div v-if="loading" class="text-center">Загрузка...</div>
    <div v-else>
      <!-- Группируем выступления по дате -->
      <div
        v-for="(performances, date) in groupedPerformances"
        :key="date"
        class="mb-8"
      >
        <h2 class="text-2xl font-semibold mb-4">{{ formatDate(date) }}</h2>
        <div class="space-y-4">
          <div
            v-for="performance in performances"
            :key="performance.title"
            class="bg-white p-6 rounded-lg shadow-md"
          >
            <h3 class="text-xl font-bold text-blue-600">
              {{ performance.title }}
            </h3>
            <p class="mt-2 text-gray-700">
              <span class="font-semibold">Участник:</span>
              {{ performance.user.first_name }} {{ performance.user.last_name }}
            </p>
            <p class="mt-2 text-gray-700">
              <span class="font-semibold">Описание:</span>
              {{ performance.description }}
            </p>
            <p class="mt-2 text-gray-700">
              <span class="font-semibold">Время:</span>
              {{ performance.start_time }} - {{ performance.end_time }}
            </p>
            <p class="mt-2 text-gray-700">
              <span class="font-semibold">Место:</span>
              {{ performance.location.name }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    performances: {
      type: Array,
      required: true,
    },
    sectionName: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {
    // Группируем выступления по дате
    groupedPerformances() {
      return this.performances.reduce((groups, performance) => {
        const date = performance.date
        if (!groups[date]) {
          groups[date] = []
        }
        groups[date].push(performance)
        return groups
      }, {})
    },
  },
  methods: {
    // Форматируем дату для отображения
    formatDate(date) {
      return new Date(date).toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    },
  },
}
</script>
