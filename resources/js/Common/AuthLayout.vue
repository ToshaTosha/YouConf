<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-500">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <slot></slot>
      <!-- Здесь будет ваша форма авторизации или регистрации -->
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthLayout',
}
</script>

<style scoped>
/* Вы можете добавить дополнительные стили, если это необходимо */
</style>
