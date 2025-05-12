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

        <!-- Правая часть шапки -->
        <div class="flex items-center space-x-4">
          <!-- Компонент уведомлений -->
          <NotificationsDropdown v-if="$page.props?.user_data" />

          <!-- Кнопка входа или профиль -->
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
            <a href="/login" class="bg-white text-blue-600 px-4 py-2 rounded">
              Войти
            </a>
          </template>
        </div>
      </div>
    </header>

    <!-- Меню -->
    <nav class="sticky top-0 bg-white shadow z-50">
      <div class="container mx-auto flex justify-center space-x-4 p-2">
        <!-- Встраиваем все страницы в меню -->
        <template v-if="$page.props?.allPages">
          <template v-for="page in $page.props.allPages" :key="page.slug">
            <a
              :href="`/${page.slug}`"
              class="text-gray-700 hover:text-blue-600"
            >
              {{ page.title }}
            </a>
          </template>
        </template>
        <a href="/sections" class="text-gray-700 hover:text-blue-600">Секции</a>
        <a href="/schedules" class="text-gray-700 hover:text-blue-600">
          Раписание
        </a>
        <a
          v-if="$page.props?.role === 'expert'"
          href="/theses"
          class="text-gray-700 hover:text-blue-600"
        >
          Тезисы
        </a>
      </div>
    </nav>

    <!-- <button
      @click="switchUser(11)"
      class="bg-blue-500 text-white p-2 rounded-lg"
    >
      Переключиться на участника
    </button>
    <button
      @click="switchUser(2)"
      class="bg-green-500 text-white p-2 rounded-lg"
    >
      Переключиться на эксперта
    </button> -->

    <!-- Основной контент -->
    <main class="flex-grow container mx-auto p-4">
      <slot></slot>
    </main>

    <!-- Футер -->
    <footer class="bg-gray-800 text-white p-4">
      <div class="container mx-auto text-center"></div>
    </footer>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import NotificationsDropdown from '@/Components/NotificationsDropdown.vue'
export default {
  name: 'DefaultLayout',
  components: {
    Link,
    NotificationsDropdown,
  },
  props: {
    sections: Array,
  },
  computed: {
    userProfileLink() {
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
