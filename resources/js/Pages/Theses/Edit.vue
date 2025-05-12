<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ thesis.title }}</h1>
    <p class="text-gray-600">{{ thesis.description }}</p>
    <p class="text-sm text-gray-500 mt-2">Статус: {{ thesis.status.name }}</p>
    <p class="text-sm text-gray-500 mt-2">Раздел: {{ thesis.section.name }}</p>
    <p class="text-sm text-gray-500 mt-2">
      Пользователь: {{ thesis.user.first_name }}
    </p>

    <h2 class="text-lg font-semibold mt-4">Связанные файлы:</h2>
    <div class="grid grid-cols-2 gap-4 mt-2">
      <div
        v-for="file in thesis.files"
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
    <ThesesForm :thesis="thesis" />
  </div>
</template>

<script>
import ThesesForm from '@/Components/ThesesForm.vue'

export default {
  components: {
    ThesesForm,
  },
  props: {
    thesis: Object,
  },
}
</script>
