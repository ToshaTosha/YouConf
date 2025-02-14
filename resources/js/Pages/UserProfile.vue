<template>
  <div class="p-6">
    <!-- Блок информации о пользователе -->
    <div class="bg-blue-100 p-6 rounded-lg mb-6">
      <h1 class="text-2xl font-bold mb-4">Информация о пользователе</h1>
      <p>
        <strong>Имя:</strong>
        {{ user_data }}
      </p>
      <!-- Добавьте другие поля пользователя -->
    </div>
    <a
      href="/applications"
      class="text-white bg-sky-200 hover:bg-sky-300 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
    >
      Заявки
    </a>
  </div>
</template>

<script>
export default {
  props: {
    user_data: Object,
    applications: Array,
  },
  methods: {
    // openApplication(id) {
    //   this.$inertia.visit(route('application.show', id))
    // },
  },
}
</script>
