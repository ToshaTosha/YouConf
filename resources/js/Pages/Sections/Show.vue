<template>
  <div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Заголовок секции -->
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
      {{ $page.props.section.name }}
    </h1>

    <!-- Полное описание секции -->
    <p class="text-gray-600 mb-6">
      {{ $page.props.section.full_description }}
    </p>

    <!-- Дополнительное описание -->
    <p class="text-gray-600 mb-6">
      Полное описание заявки
    </p>

    <!-- Кнопка для создания заявки -->
    <Link
      :href="`/applications/create/${$page.props.section.id}`"
      class="inline-block px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
    >
      Создать заявку в этой секции
    </Link>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
  name: 'SectionInfo',
  components: {
    Link,
  },
}
</script>
