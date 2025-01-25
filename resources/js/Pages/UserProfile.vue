<template>
  <div class="p-6">
    <!-- Блок информации о пользователе -->
    <div class="bg-blue-100 p-6 rounded-lg mb-6">
      <h1 class="text-2xl font-bold mb-4">Информация о пользователе</h1>
      <p>
        <strong>Имя:</strong>
        {{ user }}
      </p>
      <!-- Добавьте другие поля пользователя -->
    </div>

    <!-- Таблица заявок -->
    <!-- <div>
      <h2 class="text-xl font-bold mb-4">Статус заявок</h2>
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="py-2">Название</th>
            <th class="py-2">Дата</th>
            <th class="py-2">Статус</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="application in applications"
            :key="application.id"
            @click="openApplication(application.id)"
            class="cursor-pointer hover:bg-gray-100"
          >
            <td class="border px-4 py-2">{{ application.title }}</td>
            <td class="border px-4 py-2">{{ application.date }}</td>
            <td class="border px-4 py-2">{{ application.status }}</td>
          </tr>
        </tbody>
      </table>
    </div> -->
  </div>
</template>

<script>
export default {
  props: {
    user: Object,
    applications: Array,
  },
  methods: {
    // openApplication(id) {
    //   this.$inertia.visit(route('application.show', id))
    // },
  },
}
</script>
