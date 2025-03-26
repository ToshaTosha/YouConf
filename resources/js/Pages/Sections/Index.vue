<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Секции</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="section in sections"
        :key="section.id"
        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow flex flex-col h-full"
      >
        <h3 class="text-lg font-bold">{{ section.name }}</h3>
        <p class="text-gray-600 flex-1 overflow-hidden text-ellipsis">
          {{ section.description }}
        </p>
        <div class="mt-4 flex justify-between">
          <Link
            :href="`/sections/${section.id}`"
            class="text-blue-500 hover:underline"
          >
            Подробнее
          </Link>
          <Link
            :href="`/applications/create/${section.id}`"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          >
            Подать заявку
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: {
    Link,
  },
  props: {
    sections: Array,
    user: Object,
  },
}
</script>

<style scoped>
/* Стили для обрезки текста с троеточием */
p {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3; /* Количество строк, которые нужно показать */
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal; /* Позволяет тексту переноситься на следующую строку */
}
</style>
