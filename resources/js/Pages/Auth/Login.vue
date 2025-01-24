<template>
  <div class="flex flex-col items-center justify-center min-h-screen">
    <div class="flex space-x-4 mb-6">
      <button
        @click="showLogin = true"
        class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
      >
        Вход
      </button>
      <a href="/auth/vk">Войти через ВКонтакте</a>
    </div>
  </div>
</template>

<script>
import AuthLayout from '../../Common/AuthLayout.vue'

export default {
  layout: AuthLayout,
}
</script>
