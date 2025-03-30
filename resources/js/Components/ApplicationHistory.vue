<template>
  <div class="performance-history">
    <h2 class="text-lg font-bold mb-4">История изменений заявки</h2>
    <div v-if="versions.length === 0" class="text-gray-500">Нет изменений.</div>
    <div
      v-for="version in versions"
      :key="version.id"
      class="border p-4 mb-4 rounded-lg shadow"
    >
      <h3 class="font-semibold">{{ version.title }}</h3>
      <p class="text-gray-700">{{ version.description }}</p>
      <p class="text-sm text-gray-500">
        Изменено: {{ formatDate(version.created_at) }}
      </p>

      <div v-if="version.files && version.files.length > 0" class="mt-4">
        <h4 class="font-semibold">Связанные файлы:</h4>
        <ul class="list-disc list-inside">
          <li v-for="file in version.files" :key="file.id" class="mt-1">
            <a
              :href="`/storage/${file.path}`"
              target="_blank"
              class="text-blue-500 hover:underline"
            >
              {{ file.name }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  {{ versions }}
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
.performance-history {
  margin-top: 20px;
}
</style>
