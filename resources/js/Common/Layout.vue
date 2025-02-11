<template>
  <div class="flex flex-col min-h-screen">
    <!-- Шапка -->
    <header class="bg-blue-600 text-white p-4">
      <div class="container mx-auto flex justify-between items-center">
        <!-- Иконка сайта -->
        <div class="flex items-center">
          <img src="@img/logo.png" alt="Logo" class="h-8 w-8 mr-2" />
          <span class="text-lg font-semibold">Удивительный Мир</span>
        </div>

        <!-- Кнопка входа или профиль -->
        <div>
          <template v-if="$page.props?.user_data">
            <Link
              :href="'/user/' + $page.props.user_data.id"
              class="flex items-center"
            >
              <img
                :src="$page.props?.user_data?.avatar"
                alt="Avatar"
                class="h-8 w-8 rounded-full mr-2"
              />
              <span>{{ $page.props?.user_data?.first_name }}</span>
            </Link>
          </template>
          <template v-else>
            <Link
              href="/login"
              class="bg-white text-blue-600 px-4 py-2 rounded"
            >
              Войти
            </Link>
          </template>
        </div>
      </div>
    </header>

    <!-- Меню -->
    <nav class="sticky top-0 bg-white shadow z-50">
      <div class="container mx-auto flex justify-center space-x-4 p-2">
        <a href="/" class="text-gray-700 hover:text-blue-600">Главная</a>
        <a href="/rules" class="text-gray-700 hover:text-blue-600">Правила</a>
        <a href="/archive" class="text-gray-700 hover:text-blue-600">Архив</a>
        <a href="/contacts" class="text-gray-700 hover:text-blue-600">
          Контакты
        </a>
        <a href="/sections" class="text-gray-700 hover:text-blue-600">
          Секции
        </a>
      </div>
    </nav>

    <button
      @click="switchUser(1)"
      class="bg-blue-500 text-white p-2 rounded-lg"
    >
      Переключиться на участника
    </button>
    <button
      @click="switchUser(2)"
      class="bg-green-500 text-white p-2 rounded-lg"
    >
      Переключиться на эксперта
    </button>

    <!-- Основной контент -->
    <main class="flex-grow container mx-auto p-4">
      <slot></slot>
    </main>

    <!-- Футер -->
    <footer class="bg-gray-800 text-white p-4">
      <div class="container mx-auto text-center">
        <p>&copy; 2024 Удивительный Мир. Все права защищены.</p>
      </div>
    </footer>
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/inertia-vue3'
export default {
  name: 'Layout',
  components: {
    Link,
  },
  props: {
    sections: Array,
  },
  computed: {
    userProfileLink() {
      console.log(`/user/${this.$page.props?.user_data?.id}`)

      return `/user/${this.$page.props?.user_data?.id}`
    },
  },
  methods: {
    switchUser(userId) {
      this.$inertia.visit(`/switch-user/${userId}`)
    },
  },
}
</script>

<style scoped>
/* Дополнительные стили, если необходимо */
</style>
