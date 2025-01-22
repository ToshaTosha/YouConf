<template>
  <AppLayout>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">{{ section.name }}</h1>
      <p class="text-gray-600">{{ section.full_description }}</p>
      <p class="text-sm text-gray-500 mt-2">
        Дата: {{ formatDate(section.start_date) }} -
        {{ formatDate(section.end_date) }}
      </p>

      <!-- Форма подачи заявки -->
      <div class="mt-6">
        <h2 class="text-xl font-bold mb-4">Подать заявку</h2>
        <form @submit.prevent="submitApplication">
          <div class="mb-4">
            <label class="block text-gray-700">Ваше имя</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full p-2 border rounded-lg"
              required
            />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Ваш email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full p-2 border rounded-lg"
              required
            />
          </div>
          <button
            type="submit"
            class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600"
          >
            Отправить заявку
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  section: Object,
})

const form = reactive({
  name: '',
  email: '',
})

const submitApplication = () => {
  router.post(`/sections/${props.section.id}/apply`, form)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}
</script>
