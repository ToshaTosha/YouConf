<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Мероприятия</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="section in sections"
        :key="section.id"
        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer"
        @click="navigateToSsection(section.id)"
      >
        <h3 class="text-lg font-bold">{{ section.name }}</h3>
        <p class="text-gray-600">{{ section.description }}</p>
        <p class="text-sm text-gray-500 mt-2">
          Дата: {{ formatDate(section.start_date) }} -
          {{ formatDate(section.end_date) }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
//import { router } from '@inertiajs/vue3'

const props = defineProps({
  sections: Array,
})

console.log('sections', props.sections)

const navigateToSsection = (sectionId) => {
  router.visit(`/sections/${sectionId}`)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}
</script>
