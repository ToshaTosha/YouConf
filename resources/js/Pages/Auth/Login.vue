<template>
  <h2 class="text-2xl font-bold text-center mb-6">Авторизация</h2>
  <a
    href="/auth/vk"
    class="text-white bg-sky-200 hover:bg-sky-300 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
  >
    Войти через ВКонтакте
  </a>
</template>

<script>
import AuthLayout from '@/Common/AuthLayout.vue'

export default {
  meta: {
    layout: AuthLayout, // Сохраняем информацию о лейауте в meta
  },
}
</script>
