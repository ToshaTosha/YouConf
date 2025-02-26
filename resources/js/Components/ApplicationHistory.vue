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
      <p class="text-sm text-gray-500">
        Изменено: {{ formatDate(version.created_at) }}
      </p>

      <!-- Отображение чата и его сообщений -->
      <div v-if="version.chat && version.chat.length > 0" class="mt-4">
        <h4 class="font-semibold">Чат:</h4>
        <div
          v-for="chat in version.chat"
          :key="chat.id"
          class="border-t mt-2 pt-2"
        >
          <div
            v-for="message in chat.messages"
            :key="message.id"
            class="flex items-start mb-2"
          >
            <img
              :src="message.user.avatar"
              alt="Avatar"
              class="w-8 h-8 rounded-full mr-2"
            />
            <div>
              <p class="font-semibold">{{ message.user.first_name }}:</p>
              <p>{{ message.message }}</p>
              <p class="text-sm text-gray-500">
                {{ formatDate(message.created_at) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Отображение связанных файлов -->
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
