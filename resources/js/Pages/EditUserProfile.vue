<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Редактировать профиль</h1>

    <!-- Отображение ошибок валидации -->
    <div v-if="errors.length" class="mb-4">
      <ul class="text-red-600">
        <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
      </ul>
    </div>

    <form @submit.prevent="updateProfile">
      <div class="mb-4">
        <label class="block text-gray-700">Имя</label>
        <input
          v-model="form.first_name"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md"
          required
        />
        <p v-if="errors.first_name" class="mt-2 text-sm text-red-600">
          {{ getErrorMessage(errors.first_name) }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Фамилия</label>
        <input
          v-model="form.last_name"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md"
          required
        />
        <p v-if="errors.last_name" class="mt-2 text-sm text-red-600">
          {{ getErrorMessage(errors.last_name) }}
        </p>
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
        Сохранить
      </button>
    </form>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  props: {
    user_data: Object,
    errors: Object, // Добавлено свойство для ошибок
  },
  data() {
    return {
      form: {
        first_name: this.user_data.first_name,
        last_name: this.user_data.last_name,
      },
      errors: [], // Инициализация массива для ошибок
    }
  },
  watch: {
    // Слушаем изменения в пропсах ошибок
    errors(newErrors) {
      this.errors = newErrors // Обновляем локальный массив ошибок
    },
  },
  methods: {
    updateProfile() {
      Inertia.put(`/user/${this.user_data.id}`, this.form, {
        onError: (errors) => {
          this.errors = errors // Обновляем ошибки при неудаче
        },
      })
    },
    getErrorMessage(errorArray) {
      if (Array.isArray(errorArray)) {
        return errorArray.join(', ')
      }
      return errorArray
    },
  },
}
</script>
