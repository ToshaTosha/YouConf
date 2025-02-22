<template>
  <div class="application-history">
    <h2 class="text-lg font-bold mb-4">История изменений заявки</h2>
    <div v-if="versions.length === 0" class="text-gray-500">Нет изменений.</div>
    <div
      v-for="version in versions"
      :key="version.id"
      class="border p-4 mb-4 rounded-lg shadow"
    >
      <h3 class="font-semibold">{{ version.title }}</h3>
      <p class="text-gray-700">{{ version.description }}</p>
      <!-- <p class="text-sm text-gray-500">Статус: {{ version.status_id }}</p> -->
      <p class="text-sm text-gray-500">
        Изменено: {{ formatDate(version.created_at) }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    versions: {
      type: Array,
      required: true,
    },
  },
  methods: {
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

<style scoped>
.application-history {
  margin-top: 20px;
}
</style>
