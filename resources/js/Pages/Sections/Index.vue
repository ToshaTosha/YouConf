<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Мероприятия</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="section in sections"
        :key="section.id"
        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer"
      >
        <Link :href="`/sections/${section.id}`">View User</Link>
        <h3 class="text-lg font-bold">{{ section.name }}</h3>
        <p class="text-gray-600">{{ section.description }}</p>
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
  mounted() {
    console.log(this.user) // Выведет данные пользователя
  },
}
</script>
