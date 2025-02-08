<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ application.title }}</h1>
    <p class="text-gray-600">{{ application.description }}</p>
    <p class="text-sm text-gray-500 mt-2">
      Статус: {{ application.status.name }}
    </p>
    <p class="text-sm text-gray-500 mt-2">
      Раздел: {{ application.section.name }}
    </p>
    <p class="text-sm text-gray-500 mt-2">
      Пользователь: {{ application.user.first_name }}
    </p>

    <h2 class="text-lg font-semibold mt-4">Связанные файлы:</h2>
    <div class="grid grid-cols-2 gap-4 mt-2">
      <div
        v-for="file in application.files"
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
    {{ application }}
  </div>
</template>

<script>
export default {
  props: {
    application: Object,
  },
}
</script>
