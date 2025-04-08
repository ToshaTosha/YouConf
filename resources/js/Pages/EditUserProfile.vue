<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Редактировать профиль</h1>
    <form @submit.prevent="updateProfile">
      <div class="mb-4">
        <label class="block text-gray-700">Имя</label>
        <input
          v-model="form.first_name"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md"
          required
        />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Фамилия</label>
        <input
          v-model="form.last_name"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md"
          required
        />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="mt-1 block w-full border-gray-300 rounded-md"
          required
        />
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
  },
  data() {
    return {
      form: {
        first_name: this.user_data.first_name,
        last_name: this.user_data.last_name,
        email: this.user_data.email,
      },
    }
  },
  methods: {
    updateProfile() {
      Inertia.put(`/user/${this.user_data.id}`, this.form)
    },
  },
}
</script>
``
